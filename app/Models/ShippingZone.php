<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'countries',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'countries' => 'json',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class);
    }

    public function activeMethods()
    {
        return $this->methods()->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function containsCountry(string $countryCode): bool
    {
        $countries = $this->countries ?? [];
        return in_array(strtoupper($countryCode), array_map('strtoupper', $countries));
    }
}
