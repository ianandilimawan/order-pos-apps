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

    public function setValidUntilAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['valid_until'] = null;
            return;
        }

        try {
            // Check if it's already a standard format or Carbon can parse it natively
            $this->attributes['valid_until'] = \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            try {
                // Try d/m/Y H:i
                $this->attributes['valid_until'] = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $value);
            } catch (\Exception $e2) {
                try {
                    // Try d/m/Y
                    $this->attributes['valid_until'] = \Carbon\Carbon::createFromFormat('d/m/Y', $value)->startOfDay();
                } catch (\Exception $e3) {
                    // Just set null if it completely fails
                    $this->attributes['valid_until'] = null;
                }
            }
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
