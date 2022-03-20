<?php

namespace Tests\Feature\Address;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdressStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_unauthenticated()
    {
        $response = $this->postJson('api/addresses');

        $response->assertStatus(401);
    }

    public function test_it_required_name()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'post', 'api/addresses');

        $response->assertJsonValidationErrors('name');
    }

    public function test_it_required_address_line_one()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'post', 'api/addresses');

        $response->assertJsonValidationErrors('address_1');
    }

    public function test_it_required_address_city()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'post', 'api/addresses');

        $response->assertJsonValidationErrors('city');
    }

    public function test_it_required_postal_code()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'post', 'api/addresses');

        $response->assertJsonValidationErrors('postal_code');
    }

    public function test_it_required_valid_country()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'post', 'api/addresses', [
            'country_id' => 1
        ]);

        $response->assertJsonValidationErrors('country_id');
    }

    public function test_it_store_address()
    {
        $user = User::factory()->create();
        $response = $this->jsonAs($user, 'post', 'api/addresses', $paylaod = [
            'country_id' => Country::factory()->create()->id,
            'name' => 'Test',
            'address_1' => 'test_address',
            'city' => 'SaiGOn',
            'postal_code' => 12345
        ]);

        $response->assertStatus(201)->assertJsonFragment(array_merge($paylaod, [
            'id' => json_decode($response->getContent())->data->id
        ]));
        $this->assertDatabaseHas('addresses', $paylaod);
    }
}
