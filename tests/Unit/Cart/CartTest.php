<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\Address;
use App\Models\Country;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
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
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = random_int(1, 4);
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
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $user->refresh();
        $productsAdd = ProductVariation::all()
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 1;
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
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 2;
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
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 2;
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
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $cart->emptyCart();
        $this->assertEquals(0, $user->fresh()->cart->count());
    }

    public function test_it_can_check_if_the_cart_is_empty_of_quantity()
    {
        $products = ProductVariation::factory(3)->create()
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 0;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $this->assertTrue($cart->isEmpty());
    }

    public function test_it_return_subtotal()
    {
        $products = ProductVariation::factory(3)->create([
            'price' => 100
        ])
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 2;
                return $product;
            })->toArray();
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $user->refresh();
        $this->assertEquals(600, $cart->subTotal()->amount());
    }

    public function test_it_return_total()
    {
        $products = ProductVariation::factory(3)->create([
            'price' => 100
        ])
            ->map(function ($product) {
                $product = $product->only(['id']);
                $product['quantity'] = 2;
                return $product;
            })->toArray();
//        dd($products);
        $cart = new Cart($user = User::factory()->create());
        $cart->add($products);
        $user->refresh();
        $this->assertEquals(600, $cart->total()->amount());
    }


    public function test_it_sync_the_cart_to_update_quantity()
    {
        $product = ProductVariation::factory()->create([
            'price' => 100
        ]);
        $products = [
                [
                    "id" => $product->id,
                    "quantity" => 10
                ]
        ];

        $cart = new Cart($user = User::factory()->create());
        $product->stocks()->create([
            'quantity' => 5
        ]);
        $cart->add($products);
        $user->refresh();
        $cart->sync();
        $this->assertTrue($cart->isChanged());
    }

    public function test_it_doesnt_change_cart_quantity()
    {
        $product = ProductVariation::factory()->create([
            'price' => 100
        ]);
        $products = [
                [
                    "id" => $product->id,
                    "quantity" => 10
                ]
        ];

        $cart = new Cart($user = User::factory()->create());
        $product->stocks()->create([
            'quantity' => 20
        ]);
        $cart->add($products);
        $user->refresh();
        $cart->sync();
        $this->assertFalse($cart->isChanged());
    }

    public function test_it_can_return_the_correct_total_without_shipping()
    {
        $product = ProductVariation::factory()->create([
            'price' => 100
        ]);
        $products = [
                [
                    "id" => $product->id,
                    "quantity" => 10
                ]
        ];

        $cart = new Cart($user = User::factory()->create());
        $product->stocks()->create([
            'quantity' => 20
        ]);
        $cart->add($products);
        $user->refresh();
        $this->assertEquals(1000, $cart->total()->amount());
    }

    public function test_it_can_return_the_correct_total_with_shipping()
    {
        $product = ProductVariation::factory()->create([
            'price' => 100
        ]);
        $products = [
                [
                    "id" => $product->id,
                    "quantity" => 10
                ]
        ];

        $cart = new Cart($user = User::factory()->create());
        $product->stocks()->create([
            'quantity' => 20
        ]);
        $cart->add($products);
        Address::factory()->create([
            'user_id' => $user->id,
            'country_id' => ($country = Country::factory()->create())->id
        ]);
        $country->shippingMethods()->attach($shipping = ShippingMethod::factory()->create(
            [
                'price' => 100
            ]
        ));
        $user->refresh();
        $this->assertEquals(1100, $cart->withShipping($shipping->id)->total()->amount());
    }

    public function test_it_return_products_in_cart()
    {
        $cart = new Cart($user = User::factory()->create());
        $user->cart()->attach($product = ProductVariation::factory()->create([
            'price' => 100
        ]), [
            'quantity' => 3
        ]);
        $this->assertInstanceOf(ProductVariation::class, $cart->products()->first());
    }


}
