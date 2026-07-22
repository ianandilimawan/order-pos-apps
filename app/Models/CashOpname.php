<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CashOpname extends Model
{
    use HasFactory;

    protected $table = 'cash_opnames';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id', 'opname_date', 'expected_cash', 'actual_cash', 'expected_qris', 'actual_qris', 'expected_transfer', 'actual_transfer', 'difference', 'status', 'notes'
    ];
    protected $casts = [
        'opname_date' => 'datetime',
        'status' => \App\Enums\CashOpnameStatusEnum::class
    ];

    

    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
