<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'order_number',
        'dining_table_id',
        'order_type',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'total',
        'notes',
        'paid_at'
    ];
    protected $casts = [
        'deleted_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function diningTable()
    {
        return $this->belongsTo(\App\Models\DiningTable::class, 'dining_table_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'order_id');
    }

    public function charges()
    {
        return $this->hasMany(\App\Models\OrderCharge::class, 'order_id');
    }
}
