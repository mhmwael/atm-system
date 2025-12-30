<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FraudLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('accounts');
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        
        $users = $query->orderBy('id', 'asc')->get();
        return view('admin.index', ['users' => $users]);
    }

    public function showCreateForm()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'card_pin' => 'required|numeric|digits:4',
        ]);

        // Generate random 16-digit card number
        $cardNumber = $this->generateCardNumber();

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'card_pin' => hash('sha256', $validated['card_pin']),
            'card_number' => $cardNumber,
            'password' => bcrypt('DefaultPassword123!'), // Default password
        ]);

        return redirect()->route('admin.index')->with('success', "User '{$user->name}' created successfully! Card Number: {$cardNumber}");
    }

    private function generateCardNumber()
    {
        // Generate a random 16-digit card number
        do {
            $cardNumber = '';
            for ($i = 0; $i < 16; $i++) {
                $cardNumber .= mt_rand(0, 9);
            }
            // Ensure the card number is unique
        } while (User::where('card_number', $cardNumber)->exists());

        return $cardNumber;
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Cannot delete the currently logged-in user.');
        }

        DB::transaction(function () use ($user) {
            FraudLog::where('user_id', $user->id)->delete();
            $accountIds = Account::where('user_id', $user->id)->pluck('id')->toArray();
            Transaction::where('user_id', $user->id)->delete();
            Transaction::whereIn('to_account_id', $accountIds)->delete();
            Transaction::whereIn('from_account_id', $accountIds)->delete();
            Account::where('user_id', $user->id)->delete();
            $user->delete();
        });

        return back()->with('success', 'User deleted successfully.');
    }

    // Account Management Methods

    public function manageAccounts($userId)
    {
        $user = User::findOrFail($userId);
        return view('admin.manage-accounts', ['user' => $user]);
    }

    public function storeAccount(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Validate input
        $validated = $request->validate([
            'account_type' => 'required|in:current,savings,gold',
            'initial_balance' => 'nullable|numeric|min:0',
        ]);

        // Check if user already has an account of this type
        if (Account::where('user_id', $user->id)->where('account_type', $validated['account_type'])->exists()) {
            return back()->with('error', "User already has a {$validated['account_type']} account. Maximum 3 accounts allowed (one of each type).");
        }

        // Generate random 20-digit account number
        $accountNumber = $this->generateAccountNumber();

        // Create the account
        $account = Account::create([
            'user_id' => $user->id,
            'account_number' => $accountNumber,
            'account_type' => $validated['account_type'],
            'balance' => $validated['initial_balance'] ?? 0,
            'status' => 'active',
            'opened_date' => now()->toDateString(),
        ]);

        return redirect()->route('admin.accounts.manage', $user->id)
            ->with('success', "Account created successfully! Account Number: {$accountNumber}");
    }

    private function generateAccountNumber()
    {
        // Generate a random 20-digit account number
        do {
            $accountNumber = '';
            for ($i = 0; $i < 20; $i++) {
                $accountNumber .= mt_rand(0, 9);
            }
            // Ensure the account number is unique
        } while (Account::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }

    public function depositAccount(Request $request, $userId, $accountId)
    {
        $user = User::findOrFail($userId);
        $account = Account::findOrFail($accountId);

        // Verify the account belongs to the user
        if ($account->user_id !== $user->id) {
            return back()->with('error', 'Account does not belong to this user.');
        }

        // Validate input
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Deposit money
        $account->deposit($validated['amount']);

        return redirect()->route('admin.accounts.manage', $user->id)
            ->with('success', "Deposited \${$validated['amount']} to {$account->account_type} account.");
    }

    public function freezeAccount($userId, $accountId)
    {
        $user = User::findOrFail($userId);
        $account = Account::findOrFail($accountId);

        // Verify the account belongs to the user
        if ($account->user_id !== $user->id) {
            return back()->with('error', 'Account does not belong to this user.');
        }

        $account->freeze();

        return redirect()->route('admin.accounts.manage', $user->id)
            ->with('success', "{$account->account_type} account has been frozen.");
    }

    public function unfreezeAccount($userId, $accountId)
    {
        $user = User::findOrFail($userId);
        $account = Account::findOrFail($accountId);

        // Verify the account belongs to the user
        if ($account->user_id !== $user->id) {
            return back()->with('error', 'Account does not belong to this user.');
        }

        $account->unfreeze();

        return redirect()->route('admin.accounts.manage', $user->id)
            ->with('success', "{$account->account_type} account has been unfrozen.");
    }
}
