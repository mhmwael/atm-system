<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create a test user
        $user = User::create([
            'name' => 'Wael Alsherbiny',
            'email' => 'wael@example.com',
            'phone' => '+1234567899',
            'card_number' => '1234567890123455',
            'card_pin' => hash('sha256', '1234'), // PIN: 1234
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'date_of_birth' => '1990-01-01',
            'address' => '123 Main Street',
            'city' => 'Cairo',
            'country' => 'Egypt',
        ]);

        // Create 3 accounts for the user
        Account::create([
            'user_id' => $user->id,
            'account_number' => '10001234567890123455',
            'account_type' => 'savings',
            'balance' => 45280.50,
            'status' => 'active',
            'opened_date' => now(),
        ]);

        Account::create([
            'user_id' => $user->id,
            'account_number' => '20001234567890123455',
            'account_type' => 'current',
            'balance' => 12450.00,
            'status' => 'active',
            'opened_date' => now(),
        ]);

        Account::create([
            'user_id' => $user->id,
            'account_number' => '30001234567890123455',
            'account_type' => 'gold',
            'balance' => 78900.25,
            'status' => 'active',
            'opened_date' => now(),
        ]);

        echo "✅ Test user created!\n";
        echo "Email: waelmple.com\n";
        echo "PIN: 1234\n";
    }
}
