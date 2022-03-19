<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
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
    public function test_it_show_if_cart_is_empty()
    {
        $user = User::factory()->create();
        $user->cart()->attach($product = ProductVariation::factory()->create(), [
            'quantity' => 0
        ]);
        $response = $this->jsonAs($user, 'get', "api/carts");
        $response->assertJsonFragment([
                    'id' => $product->id,
        ])->assertJsonFragment([
            'is_empty' => true
        ]);
    }
    public function test_it_sync_the_cart()
    {
        $product = ProductVariation::factory()->create([
            'price' => 100
        ]);
        $products = [
            [
                "id" => $product->id,
                "quantity" => 30
            ]
        ];

        $cart = new Cart($user = User::factory()->create());
        $product->stocks()->create([
            'quantity' => 20
        ]);
        $cart->add($products);

        $response = $this->jsonAs($user, 'get', "api/carts");
        $response->assertJsonFragment([
                    'changed' =>true,
        ]);
    }

}
