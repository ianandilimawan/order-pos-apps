<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderCharge extends Model
{
    use HasFactory;

    protected $table = 'order_charges';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'order_id', 'charge_setting_id', 'charge_name', 'charge_type', 'charge_rate', 'charge_amount'
    ];
    

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id');
    }

    public function chargeSetting()
    {
        return $this->belongsTo(\App\Models\ChargeSetting::class, 'charge_setting_id');
    }
}
