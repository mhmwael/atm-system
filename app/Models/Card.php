<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 * 
 * @property string $card_number
 * @property int $cvv
 * @property string $expiry_date
 */
class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'card_number',
        'cvv',
        'expiry_date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
