<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
//        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->deleteJson('api/carts/1', [
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
        $response = $this->jsonAs(User::factory()->create(), 'delete', 'api/carts/1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_remove_product()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 1
        ]);
        $this->jsonAs($user, 'delete', "api/carts/$product->id");
        $this->assertCount(0, $user->fresh()->cart);
        $this->assertDatabaseMissing('cart_user', [
           'product_variation_id' => $product->id
        ]);
    }
}
