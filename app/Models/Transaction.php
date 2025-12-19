<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property string $status
 * @property string $location
 * @property string $ip_address
 * @property string $device_info
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'to_account_id',
        'from_account_id',
        'amount',
        'type',
        'status',
        'location',
        'ip_address',
        'device_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function fraud()
    {
        return $this->hasOne(Fraud::class);
    }
}
