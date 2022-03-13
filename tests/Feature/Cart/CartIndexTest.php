<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
//        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->getJson('api/carts', [
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

    public function test_it_show_user_product_in_cart()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 1
        ]);
        $response = $this->jsonAs($user, 'get', "api/carts");
        $response->assertJsonFragment([
                    'id' => $product->id,
        ])->assertJsonFragment([
            'quantity' => 1
        ]);
    }


}
