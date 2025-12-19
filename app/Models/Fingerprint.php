<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fingerprint
 * 
 * @property string $image_path
 * @property bool $is_active
 * @property string $last_used
 */
class Fingerprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template',
        'image_path',
        'is_active',
        'last_used',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
