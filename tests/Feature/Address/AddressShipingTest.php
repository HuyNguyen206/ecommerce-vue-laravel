<?php

namespace Address;

use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class AddressShipingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_user_is_unauthenticate()
    {
        $response = $this->getJson("api/addresses/1/shippings");

        $response->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_can_find_shipping_methods_when_address_is_not_exist()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'get', "api/addresses/1/shippings");

        $response->assertStatus(404);
    }

    public function test_it_fail_if_user_is_not_own_address()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $address = Address::factory()->create([
             'user_id' => $user2->id
         ]);
        $response = $this->jsonAs($user, 'get', "api/addresses/$address->id/shippings");

        $response->assertStatus(403);
    }

    public function test_it_show_shipping_methods_for_the_given_address()
    {
        $user2 = User::factory()->create();
        $address = Address::factory()->create([
             'user_id' => $user2->id
         ]);
        $address->country->shippingMethods()->attach($shippingMethods = ShippingMethod::factory()->create());
        $response = $this->jsonAs($user2, 'get', "api/addresses/$address->id/shippings");

        $response->assertJsonFragment([
            'id' => $shippingMethods->id,
            'name' => $shippingMethods->name,
        ]);
    }
}
