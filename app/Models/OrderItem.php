<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price', 'subtotal', 'notes'
    ];
    

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
