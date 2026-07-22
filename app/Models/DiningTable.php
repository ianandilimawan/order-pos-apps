<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiningTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dining_tables';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'number', 'capacity', 'status', 'qr_code', 'show'
    ];
    protected $casts = [
        'deleted_at' => 'datetime',
        'show' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    
}
