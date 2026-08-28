<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id',
        'name',
        'description',
        'flat_rate',
        'free_shipping_threshold',
        'estimated_delivery_time',
        'is_active',
        'sort_order',
        'min_weight',
        'max_weight',
        'per_kg_rate',
    ];

    protected $casts = [
        'flat_rate' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'per_kg_rate' => 'decimal:2',
    ];

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function calculateShippingCost(float $orderSubtotal, ?float $weight = null): float
    {
        // Check free shipping threshold
        if ($this->free_shipping_threshold && $orderSubtotal >= $this->free_shipping_threshold) {
            return 0.00;
        }

        $cost = (float) $this->flat_rate;

        // Add weight-based cost if applicable
        if ($weight && $this->per_kg_rate) {
            $cost += $weight * (float) $this->per_kg_rate;
        }

        return round($cost, 2);
    }

    public function getFormattedFlatRateAttribute(): string
    {
        return '$' . number_format((float) $this->flat_rate, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
