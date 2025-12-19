<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@atm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular User
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@atm.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        
        // Create Account for User
        Account::create([
            'user_id' => $user->id,
            'account_number' => '1000000001',
            'balance' => 1000.00,
            'account_type' => 'checking'
        ]);
    }
}
