<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;

class ATMController extends Controller
{
    public function index()
    {
        return view('atm.index');
    }

    public function showWithdraw()
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        
        return view('atm.withdraw', [
            'accounts' => $accounts,
        ]);
    }

    public function processWithdraw(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:10|max:5000'
        ]);

        $accountId = $request->input('account_id');
        $amount = $request->input('amount');
        $user = Auth::user();
        
        // Get the account and verify it belongs to the user
        $account = Account::find($accountId);
        
        if (!$account || $account->user_id !== $user->id) {
            return back()->with('error', 'Account not found.');
        }
        
        // Check if account has sufficient balance
        if (!$account->hasSufficientBalance($amount)) {
            return back()->with('error', 'Insufficient balance for this withdrawal.');
        }
        
        // Deduct the amount from account
        $account->deduct($amount);
        
        // Create transaction record
        Transaction::create([
            'transaction_id' => Transaction::generateTransactionId(),
            'user_id' => $user->id,
            'from_account_id' => $account->id,
            'to_account_id' => null,
            'transaction_type' => 'withdrawal',
            'amount' => $amount,
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', "Successfully withdrawn $$amount from your " . ucfirst($account->account_type) . " account!");
    }

    public function showTransfer()
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        
        return view('atm.transfer', [
            'accounts' => $accounts,
        ]);
    }

    public function processTransfer(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_number' => 'required|string|min:10|max:16',
            'amount' => 'required|numeric|min:1|max:10000'
        ]);

        $fromAccountId = $request->input('from_account_id');
        $toAccountNumber = $request->input('to_account_number');
        $amount = $request->input('amount');
        $user = Auth::user();
        
        // Get the from account and verify it belongs to the user
        $fromAccount = Account::find($fromAccountId);
        
        if (!$fromAccount || $fromAccount->user_id !== $user->id) {
            return back()->with('error', 'From account not found.');
        }
        
        // Get the to account by account number
        $toAccount = Account::where('account_number', $toAccountNumber)->first();
        
        if (!$toAccount) {
            return back()->with('error', 'Recipient account not found.');
        }
        
        // Check if account has sufficient balance
        if (!$fromAccount->hasSufficientBalance($amount)) {
            return back()->with('error', 'Insufficient balance for this transfer.');
        }
        
        // Deduct from source account
        $fromAccount->deduct($amount);
        
        // Add to destination account
        $toAccount->deposit($amount);
        
        // Create transaction record
        Transaction::create([
            'transaction_id' => Transaction::generateTransactionId(),
            'user_id' => $user->id,
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'transaction_type' => 'transfer',
            'amount' => $amount,
            'status' => 'completed',
            'transaction_date' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', "Successfully transferred $$amount to account ending in " . substr($toAccountNumber, -4) . "!");
    }

    // NEW HISTORY METHOD
    public function showHistory()
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        $accountIds = $accounts->pluck('id')->toArray();
        
        // Fetch all transactions for user's accounts
        $transactions = Transaction::where('user_id', $user->id)
                                   ->orderBy('transaction_date', 'desc')
                                   ->get();
        
        return view('atm.history', [
            'transactions' => $transactions,
            'accounts' => $accounts,
            'accountIds' => $accountIds,
        ]);
    }
}