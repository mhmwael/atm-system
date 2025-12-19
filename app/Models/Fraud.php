<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fraud
 * 
 * @property string $reason
 * @property string $status
 * @property string $detected_at
 */
class Fraud extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'reason',
        'status',
        'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
