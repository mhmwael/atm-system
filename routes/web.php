<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    
    // CRUD Operations (Example: Accounts)
    Route::resource('accounts', App\Http\Controllers\AccountController::class);
    
    // Ajax Endpoints
    Route::get('/api/balance', [DashboardController::class, 'getBalance']);
    
    // File Upload
    Route::post('/upload-photo', [DashboardController::class, 'uploadPhoto'])->name('upload.photo');

    // Biometrics (Fingerprint)
    Route::post('/register-fingerprint', [DashboardController::class, 'registerFingerprint'])->name('fingerprint.register');
});