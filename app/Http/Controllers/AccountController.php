<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AppLogger;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Simple listing
        $accounts = Account::with('user')->get();
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::doesntHave('account')->get();
        return view('accounts.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:accounts',
            'type' => 'required',
            'amount' => 'numeric|min:0'
        ]);

        $account = Account::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'balance' => $request->amount ?? 0,
            'account_number' => rand(1000000000, 9999999999), 
        ]);

        AppLogger::getInstance()->log("Account created for User ID: " . $request->user_id);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'type' => 'required',
            'balance' => 'numeric'
        ]);

        $account->update($request->only(['type', 'balance']));
        
        AppLogger::getInstance()->log("Account updated: " . $account->account_number);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        
        AppLogger::getInstance()->log("Account deleted: " . $account->account_number);
        
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}
