<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'from_account_id',
        'to_account_id',
        'transaction_type',
        'amount',
        'latitude',
        'longitude',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'transaction_date' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function fraudLogs()
    {
        return $this->hasMany(FraudLog::class, 'transaction_id', 'transaction_id');
    }

    // Generate unique transaction ID
    public static function generateTransactionId()
    {
        return str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }
}