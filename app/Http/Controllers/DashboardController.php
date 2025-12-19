<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Services\AppLogger;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Eager load account if exists
        $account = $user->account;
        
        $transactions = collect();
        $stats = ['deposits' => 0, 'withdrawals' => 0];

        if ($account) {
            $transactions = $account->transactions()->latest()->take(10)->get();
            $stats['deposits'] = $account->transactions()->where('type', 'deposit')->sum('amount');
            $stats['withdrawals'] = $account->transactions()->where('type', 'withdrawal')->sum('amount');
        }

        return view('dashboard', compact('user', 'account', 'transactions', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load('fingerprints');
        return view('profile', compact('user'));
    }

    public function registerFingerprint(Request $request)
    {
        $user = Auth::user();
        
        // Simulate biometric template generation
        $template = "BIOMETRIC_DATA_" . bin2hex(random_bytes(16));

        $user->fingerprints()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'template' => $template,
                'is_active' => true,
                'last_used' => now(),
            ]
        );

        AppLogger::getInstance()->log("Fingerprint registered for user: " . $user->email);

        return back()->with('success', 'Fingerprint registered successfully!');
    }

    public function getBalance()
    {
        $user = Auth::user();
        if ($user->account) {
            return response()->json(['balance' => $user->account->balance]);
        }
        return response()->json(['balance' => 0.00]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:1024',
        ]);

        $user = Auth::user();
        
        if ($request->file('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        return back()->with('success', 'Photo uploaded successfully!');
    }
}
