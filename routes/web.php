<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Authentication Routes (you already have these)
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/fingerprint', [AuthController::class, 'loginWithFingerprint']);

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ATM Routes (we'll create these next)
    Route::get('/atm', function() {
        return view('atm.index');
    });
    
    Route::get('/atm/withdraw', function() {
        return view('atm.withdraw');
    });
    
    Route::get('/atm/transfer', function() {
        return view('atm.transfer');
    });
    
    Route::get('/atm/balance', function() {
        return view('atm.balance');
    });
    
    Route::get('/atm/history', function() {
        return view('atm.history');
    });
    
    // Fraud Detection Routes (we'll create these later)
    Route::get('/fraud/dashboard', function() {
        return redirect('/dashboard');
    });
    
    Route::get('/fraud/alerts', function() {
        return redirect('/dashboard');
    });
});