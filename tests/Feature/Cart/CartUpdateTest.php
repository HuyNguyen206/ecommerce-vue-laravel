<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
//        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->putJson('api/carts/3', [
            'products' => [
                [
                    'id' => 1,
                    'quantity' => 2
                ],
                [
                    'id' => 2,
                    'quantity' => 3
                ]
            ]
        ]);

        $response->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_product_can_not_found()
    {
//
        $response = $this->jsonAs(User::factory()->create(), 'put', 'api/carts/1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_require_quantity()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 1
        ]);
        $response = $this->jsonAs($user, 'put', "api/carts/$product->id");
        $response->assertStatus(422)
        ->assertJsonValidationErrors('quantity');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_require_nummeric_quantity()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 1
        ]);
        $response = $this->jsonAs($user, 'put', "api/carts/$product->id", [
            'quantity' => 'two'
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('quantity');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_require_quantity_at_least_one()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 1
        ]);
        $response = $this->jsonAs($user, 'put', "api/carts/$product->id", [
            'quantity' => 0
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('quantity');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_update_quantity()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 1
        ]);
        $response = $this->jsonAs($user, 'put', "api/carts/$product->id", [
            'quantity' => 3
        ]);
        $this->assertEquals(3, $user->fresh()->cart->first()->pivot->quantity);
    }
}
