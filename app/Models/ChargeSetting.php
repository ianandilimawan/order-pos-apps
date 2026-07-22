<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ChargeSetting extends Model
{
    use HasFactory;

    protected $table = 'charge_settings';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name', 'type', 'value', 'applies_to', 'is_active', 'sort'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    
}
