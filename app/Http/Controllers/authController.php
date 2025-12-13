<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Add authentication logic here
        
        return redirect()->route('dashboard');
    }

    public function loginWithFingerprint(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'fingerprint_data' => 'required',
        ]);

        // Add fingerprint authentication logic here
        
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}*/


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Allow any credentials: find or create a user by email and log them in
        $email = $request->input('email');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $email,
                'password' => bcrypt('password'),
            ]
        );

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }

    public function loginWithFingerprint(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'fingerprint_data' => 'required',
        ]);

        // Allow fingerprint login for any provided user id: try to find the user, else create a placeholder
        $userId = $request->input('user_id');

        $user = User::find($userId);

        if (! $user) {
            $user = User::create([
                'name' => 'User ' . $userId,
                'email' => 'user' . $userId . '@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Fingerprint verified successfully!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}