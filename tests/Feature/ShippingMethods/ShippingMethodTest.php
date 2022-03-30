<?php

namespace Tests\Feature\ShippingMethods;

use App\Cart\Money;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_return_money_instance_for_a_price()
    {
        $shippingMethod = ShippingMethod::factory()->create();
        $this->assertInstanceOf(Money::class, $shippingMethod->price);
    }

    public function test_it_belong_to_many_countries()
    {
        $shippingMethod = ShippingMethod::factory()->create();
        $shippingMethod->countries()->attach(
            Country::factory(4)->create()
        );
        $this->assertEquals(4, $shippingMethod->loadCount('countries as count')->count);
    }



}
