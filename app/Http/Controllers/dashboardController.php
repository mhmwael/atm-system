<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        
        $user = Auth::user();
        $file = $request->file('profile_image');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = 'user_' . $user->id . '_' . time() . '.' . $ext;
        
        $destination = public_path('profile_pics');
        if (!is_dir($destination)) {
            @mkdir($destination, 0755, true);
        }
        
        $file->move($destination, $filename);
        $relativePath = 'profile_pics/' . $filename;
        
        // Optionally remove previous image file if exists and is within our folder
        if (!empty($user->profile_image) && str_starts_with($user->profile_image, 'profile_pics/')) {
            @unlink(public_path($user->profile_image));
        }
        
        $user->profile_image = $relativePath;
        $user->save();
        
        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}
