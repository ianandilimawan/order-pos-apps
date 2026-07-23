<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];
}
