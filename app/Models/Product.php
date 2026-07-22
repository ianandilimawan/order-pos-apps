<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'image', 'is_available', 'sort', 'show'
    ];
    protected $casts = [
        'deleted_at' => 'datetime',
        'is_available' => 'boolean',
        'show' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }
}
