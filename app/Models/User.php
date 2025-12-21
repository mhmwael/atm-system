<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'fingerprint_id',
        'profile_image',
        'card_number',
        'card_pin',
        'latitude',
        'longitude',
        'date_of_birth',
        'address',
        'city',
        'country',
    ];

    protected $hidden = [
        'password',
        'card_pin',
        'fingerprint_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function fraudLogs()
    {
        return $this->hasMany(FraudLog::class);
    }

    // Helper method to get average spending
    public function getAverageSpending()
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->where('transaction_type', '!=', 'deposit')
            ->avg('amount') ?? 0;
    }

    // Check if PIN is correct
    public function checkPin($pin)
    {
        return hash('sha256', $pin) === $this->card_pin;
    }
}
