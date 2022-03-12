<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unauthenticated()
    {
//        $this->actingAs(\App\Models\User::factory()->create());
        $response = $this->postJson('api/carts', [
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

    public function test_it_required_product()
    {
        $response = $this->jsonAs(\App\Models\User::factory()->create(), 'post', 'api/carts');
        $response->assertStatus(422)
        ->assertJsonValidationErrors('products');
    }

    public function test_it_required_product_to_be_array()
    {
        $response = $this->jsonAs(\App\Models\User::factory()->create(), 'post', 'api/carts', [
            'products' => 'test'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('products');
    }

    public function test_it_require_product_which_have_id()
    {
        $response = $this->jsonAs(\App\Models\User::factory()->create(), 'post', 'api/carts', [
            'products' => [
                [
                    'quantity' => 2
                ]
            ]
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('products.0.id');
    }

    public function test_it_require_product_to_exist()
    {
        $response = $this->jsonAs(\App\Models\User::factory()->create(), 'post', 'api/carts', [
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
        $response->assertStatus(422)
            ->assertJsonValidationErrors('products.0.id');
    }

    public function test_it_require_product_quantity_to_be_nummeric()
    {
        $response = $this->jsonAs(\App\Models\User::factory()->create(), 'post', 'api/carts', [
            'products' => [
                [
                    'id' => 1,
                    'quantity' =>'one'
                ],
                [
                    'id' => 2,
                    'quantity' => 3
                ]
            ]
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('products.0.quantity');
    }

    public function test_it_require_product_quantity_to_be_at_least_one()
    {
        $response = $this->jsonAs(\App\Models\User::factory()->create(), 'post', 'api/carts', [
            'products' => [
                [
                    'id' => 1,
                    'quantity' => 0
                ],
                [
                    'id' => 2,
                    'quantity' => 3
                ]
            ]
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors('products.0.quantity');
    }
    public function test_it_can_add_product_to_user_cart()
    {
        $product = ProductVariation::factory(2)->create();
        $response = $this->jsonAs($user = \App\Models\User::factory()->create(), 'post', 'api/carts', [
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
        $this->assertCount(2,  $user->cart);
        $this->assertDatabaseCount('cart_user', 2);
        $this->assertDatabaseHas('cart_user', [
            'quantity' => 2,
            'user_id' => $user->id,
            'product_variation_id' => 1
        ]);
        $this->assertDatabaseHas('cart_user', [
            'quantity' => 3,
            'user_id' => $user->id,
            'product_variation_id' => 2
        ]);
    }


}
