<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'transaction_amount',
        'user_avg_spending',
        'deviation_percentage',
        'transaction_latitude',
        'transaction_longitude',
        'usual_latitude',
        'usual_longitude',
        'distance_km',
        'pin_attempts',
    ];

    protected $casts = [
        'transaction_amount' => 'decimal:2',
        'user_avg_spending' => 'decimal:2',
        'deviation_percentage' => 'decimal:2',
        'transaction_latitude' => 'decimal:8',
        'transaction_longitude' => 'decimal:8',
        'usual_latitude' => 'decimal:8',
        'usual_longitude' => 'decimal:8',
        'distance_km' => 'decimal:2',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}