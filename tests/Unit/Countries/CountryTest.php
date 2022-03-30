<?php

namespace Tests\Unit\Countries;



use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;

class CountryTest extends TestCase
{

    public function test_it_has_many_shipping_methods()
    {
        $country = Country::factory()->create();
        $country->shippingMethods()->attach(
            ShippingMethod::factory(4)->create()
        );
        $this->assertEquals(4, $country->loadCount('shippingMethods as count')->count);
    }
}
