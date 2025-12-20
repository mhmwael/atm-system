<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all accounts for the authenticated user
        $accounts = $user->accounts;
        
        // Get recent transactions for all user's accounts
        $accountIds = $accounts->pluck('id')->toArray();
        $transactions = Transaction::whereIn('from_account_id', $accountIds)
                                    ->orWhereIn('to_account_id', $accountIds)
                                    ->orderBy('transaction_date', 'desc')
                                    ->limit(10)
                                    ->get();
        
        return view('dashboard.index', [
            'accounts' => $accounts,
            'transactions' => $transactions,
        ]);
    }
}