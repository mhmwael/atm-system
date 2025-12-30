<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ATMController;

// Authentication Routes
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/fingerprint', [AuthController::class, 'loginWithFingerprint']);

// WebAuthn Routes
Route::get('/webauthn/challenge', [AuthController::class, 'generateChallenge']);
Route::post('/webauthn/verify', [AuthController::class, 'verifyCredential']);

// WebAuthn Registration (protected route)
Route::middleware(['auth'])->group(function () {
    Route::get('/webauthn/register/options', [AuthController::class, 'getRegistrationOptions']);
    Route::post('/webauthn/register', [AuthController::class, 'registerCredential']);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/atm', [ATMController::class, 'index'])->name('atm.index');
    Route::get('/atm/withdraw', [ATMController::class, 'showWithdraw'])->name('atm.withdraw');
    Route::post('/atm/withdraw', [ATMController::class, 'processWithdraw']);
    Route::get('/atm/transfer', [ATMController::class, 'showTransfer'])->name('atm.transfer');
    Route::post('/atm/transfer', [ATMController::class, 'processTransfer']);
    Route::get('/atm/history', [ATMController::class, 'showHistory'])->name('atm.history');
    Route::get('/api/account-holder/{accountNumber}', [ATMController::class, 'getAccountHolder']);
    Route::post('/profile/upload', [DashboardController::class, 'uploadProfileImage'])->name('profile.upload');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin-only Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/users/create', [\App\Http\Controllers\AdminController::class, 'showCreateForm'])->name('admin.users.create');
    Route::post('/admin/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/admin/users/{id}/delete', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Account Management Routes
    Route::get('/admin/users/{userId}/accounts', [\App\Http\Controllers\AdminController::class, 'manageAccounts'])->name('admin.accounts.manage');
    Route::post('/admin/users/{userId}/accounts', [\App\Http\Controllers\AdminController::class, 'storeAccount'])->name('admin.accounts.store');
    Route::post('/admin/users/{userId}/accounts/{accountId}/deposit', [\App\Http\Controllers\AdminController::class, 'depositAccount'])->name('admin.accounts.deposit');
    Route::post('/admin/users/{userId}/accounts/{accountId}/freeze', [\App\Http\Controllers\AdminController::class, 'freezeAccount'])->name('admin.accounts.freeze');
    Route::post('/admin/users/{userId}/accounts/{accountId}/unfreeze', [\App\Http\Controllers\AdminController::class, 'unfreezeAccount'])->name('admin.accounts.unfreeze');
});
