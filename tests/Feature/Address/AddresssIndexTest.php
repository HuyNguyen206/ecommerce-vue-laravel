<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddresssIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_not_authenticate()
    {
        $response = $this->getJson('api/addresses')->assertStatus(401);

    }
    public function test_it_show_addresses()
    {
        $user = User::factory()->create();
        $user->addresses()->save($address = Address::factory()->make());
        $response = $this->jsonAs($user, 'get', 'api/addresses')->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $address->id,
            'city' => $address->city,
            'country_id' => $address->country_id
        ]);
    }
}
