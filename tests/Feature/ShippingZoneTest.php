<?php

namespace Tests\Feature;

use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingZoneTest extends TestCase
{
    use RefreshDatabase;

    private ShippingService $shippingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shippingService = new ShippingService();

        // Create shipping zones
        $usZone = ShippingZone::create([
            'name' => 'United States',
            'countries' => ['US'],
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $usZone->id,
            'name' => 'Standard International Shipping',
            'flat_rate' => 9.99,
            'free_shipping_threshold' => 100,
            'estimated_delivery_time' => '7-14 business days',
            'is_active' => true,
        ]);

        $euZone = ShippingZone::create([
            'name' => 'European Union',
            'countries' => ['DE', 'FR', 'IT', 'ES'],
            'is_active' => true,
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $euZone->id,
            'name' => 'Standard International Shipping',
            'flat_rate' => 12.99,
            'free_shipping_threshold' => 150,
            'estimated_delivery_time' => '10-18 business days',
            'is_active' => true,
        ]);
    }

    public function test_us_shipping_zone_is_determined_for_us_country(): void
    {
        $zone = $this->shippingService->getZoneForCountry('US');

        $this->assertNotNull($zone);
        $this->assertEquals('United States', $zone->name);
    }

    public function test_eu_shipping_zone_is_determined_for_germany(): void
    {
        $zone = $this->shippingService->getZoneForCountry('DE');

        $this->assertNotNull($zone);
        $this->assertEquals('European Union', $zone->name);
    }

    public function test_unknown_country_returns_no_zone(): void
    {
        $zone = $this->shippingService->getZoneForCountry('JP');

        $this->assertNull($zone);
    }

    public function test_shipping_cost_is_calculated_correctly(): void
    {
        $method = ShippingMethod::where('flat_rate', 9.99)->first();

        $cost = $this->shippingService->calculateCost($method, 50.00);

        $this->assertEquals(9.99, $cost);
    }

    public function test_free_shipping_applied_above_threshold(): void
    {
        $method = ShippingMethod::where('flat_rate', 9.99)->first();

        $this->assertTrue($this->shippingService->isFreeShippingAvailable($method, 100.00));
        $this->assertTrue($this->shippingService->isFreeShippingAvailable($method, 150.00));
    }

    public function test_free_shipping_not_applied_below_threshold(): void
    {
        $method = ShippingMethod::where('flat_rate', 9.99)->first();

        $this->assertFalse($this->shippingService->isFreeShippingAvailable($method, 50.00));
    }

    public function test_available_methods_returned_for_country(): void
    {
        $methods = $this->shippingService->getAvailableMethods('US');

        $this->assertNotEmpty($methods);
        $this->assertCount(1, $methods);
    }

    public function test_country_served_check(): void
    {
        $this->assertTrue($this->shippingService->isCountryServed('US'));
        $this->assertTrue($this->shippingService->isCountryServed('DE'));
        $this->assertFalse($this->shippingService->isCountryServed('JP'));
    }
}
