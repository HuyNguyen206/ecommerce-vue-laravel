<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;


class CartTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_can_add_product_to_cart()
    {
        $products = ProductVariation::factory(3)->create()
            ->map(function ($product){
                $product = $product->only(['id']);
                $product['quantity'] =  random_int(1,4);
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $this->assertDatabaseCount('cart_user', 3);
        $this->assertCount(3, $user->cart);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_can_increase_quantity_when_add_more_product_to_cart()
    {
        $products = ProductVariation::factory(3)->create()
            ->map(function ($product){
                $product = $product->only(['id']);
                $product['quantity'] =  2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $user->refresh();
        $productsAdd = ProductVariation::all()
            ->map(function ($product){
                $product = $product->only(['id']);
                $product['quantity'] =  1;
                return $product;
            })->toArray();
//        dd($productsAdd);
        $cart->add($productsAdd);
        $user->refresh();
        $this->assertDatabaseCount('cart_user', 3);
        $this->assertEquals(3, $user->cart->first()->pivot->quantity);
    }
    public function test_it_can_update_quantity_in_the_cart()
    {
        $products = ProductVariation::factory(3)->create()
            ->map(function ($product){
                $product = $product->only(['id']);
                $product['quantity'] =  2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $cart->update(1, 4);
        $this->assertEquals(4, $user->fresh()->cart->first()->pivot->quantity);
    }

    public function test_it_can_delete_product_in_the_cart()
    {
        $products = ProductVariation::factory(3)->create()
            ->map(function ($product){
                $product = $product->only(['id']);
                $product['quantity'] =  2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $cart->destroy(1);
        $this->assertEquals(2, $user->fresh()->cart->count());
    }

    public function test_it_can_empty_the_cart()
    {
        $products = ProductVariation::factory(3)->create()
            ->map(function ($product){
                $product = $product->only(['id']);
                $product['quantity'] =  2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $cart->emptyCart();
        $this->assertEquals(0, $user->fresh()->cart->count());
    }
}
