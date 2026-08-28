<?php

namespace App\Services;

use App\Models\ShippingMethod;
use App\Models\ShippingZone;

class ShippingService
{
    /**
     * Determine the shipping zone for a given country
     */
    public function getZoneForCountry(string $countryCode): ?ShippingZone
    {
        $countryCode = strtoupper($countryCode);

        $zones = ShippingZone::active()
            ->with(['methods' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        foreach ($zones as $zone) {
            if ($zone->containsCountry($countryCode)) {
                return $zone;
            }
        }

        return null;
    }

    /**
     * Get available shipping methods for a country
     */
    public function getAvailableMethods(string $countryCode): array
    {
        $zone = $this->getZoneForCountry($countryCode);

        if (!$zone) {
            return [];
        }

        return $zone->activeMethods()->get()->toArray();
    }

    /**
     * Calculate shipping cost for a specific method
     */
    public function calculateCost(
        ShippingMethod $method,
        float $orderSubtotal,
        ?float $weight = null
    ): float {
        return $method->calculateShippingCost($orderSubtotal, $weight);
    }

    /**
     * Check if free shipping is available
     */
    public function isFreeShippingAvailable(
        ShippingMethod $method,
        float $orderSubtotal
    ): bool {
        if (!$method->free_shipping_threshold) {
            return false;
        }

        return $orderSubtotal >= (float) $method->free_shipping_threshold;
    }

    /**
     * Get the best (cheapest) shipping method for a country
     */
    public function getCheapestMethod(string $countryCode, float $orderSubtotal): ?array
    {
        $methods = $this->getAvailableMethods($countryCode);

        if (empty($methods)) {
            return null;
        }

        $cheapest = null;
        $lowestCost = PHP_FLOAT_MAX;

        foreach ($methods as $method) {
            $cost = $method->calculateShippingCost($orderSubtotal);
            if ($cost < $lowestCost) {
                $lowestCost = $cost;
                $cheapest = $method;
            }
        }

        return $cheapest ? [
            'method' => $cheapest,
            'cost' => $lowestCost,
        ] : null;
    }

    /**
     * Validate if a country is served
     */
    public function isCountryServed(string $countryCode): bool
    {
        return $this->getZoneForCountry($countryCode) !== null;
    }
}
