<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_number',
        'account_type',
        'balance',
        'status',
        'opened_date',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'opened_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isFrozen()
    {
        return $this->status === 'frozen';
    }

    public function freeze()
    {
        $this->update(['status' => 'frozen']);
    }

    public function unfreeze()
    {
        $this->update(['status' => 'active']);
    }

    // Check if sufficient balance
    public function hasSufficientBalance($amount)
    {
        return $this->balance >= $amount;
    }

    // Deduct amount
    public function deduct($amount)
    {
        if ($this->hasSufficientBalance($amount)) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }

    // Add amount
    public function deposit($amount)
    {
        $this->increment('balance', $amount);
    }
}