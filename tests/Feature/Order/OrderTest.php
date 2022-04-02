<?php

namespace Order;

use App\Cart\Money;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_belong_to_user()
    {
        $order = Order::factory()->create();
        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_it_belong_to_address()
    {
        $order = Order::factory()->create();
        $this->assertInstanceOf(Address::class, $order->address);
    }

    public function test_it_belong_to_shipping_method()
    {
        $order = Order::factory()->create();
        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }

    public function test_it_has_defalt_pending_state()
    {
        $order = Order::factory()->create();
        $this->assertEquals(Order::PENDING, $order->state);
    }

    public function test_it_has_many_products()
    {
        $order = Order::factory()->create();
        $order->products()->attach(ProductVariation::factory(4)->create(), [
           'quantity' => 2
        ]);
        $this->assertCount(4, $order->products);
    }

    public function test_it_has_many_products_with_quantity()
    {
        $order = Order::factory()->create();
        $order->products()->attach(ProductVariation::factory(4)->create(), [
           'quantity' => 2
        ]);
        $this->assertEquals(8, $order->products->sum('pivot.quantity'));
    }

    public function test_it_return_a_money_instance_for_the_subtotal()
    {
        $order = Order::factory()->create();
        $this->assertInstanceOf(Money::class, $order->subtotal);
    }

    public function test_it_return_a_money_instance_for_the_total()
    {
        $order = Order::factory()->create();
        $this->assertInstanceOf(Money::class, $order->total);
    }
    public function test_it_add_shipping_price_to_the_total()
    {
        $order = Order::factory()->create();
        $this->assertEquals($order->total->amount(), $order->subtotal->add($order->shippingMethod->price)->amount());
    }


}
