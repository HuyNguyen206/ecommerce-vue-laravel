<?php

namespace Tests\Feature\Order;

use App\Cart\Cart;
use App\Events\OrderCreated;
use App\Models\Address;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_not_authenticated()
    {
       $this->postJson('api/orders')->assertStatus(401);
    }

    public function test_it_required_address_id()
    {
        $user = User::factory()->create();
       $this->jsonAs($user, 'post', 'api/orders')
           ->assertJsonValidationErrors('address_id');
    }
    public function test_it_required_address_id_belong_to_authenticated_user()
    {
        $user = User::factory()->create();
        $user->addresses()->save(
           $address = Address::factory()->make()
        );
        $addressNot = Address::factory()->create();
       $this->jsonAs($user, 'post', 'api/orders', [
           'address_id' => $addressNot->id
       ])
           ->assertJsonValidationErrors('address_id');
    }
    public function test_it_required_shipping_method_id()
    {
        $user = User::factory()->create();
       $this->jsonAs($user, 'post', 'api/orders')
           ->assertJsonValidationErrors('shipping_method_id');
    }
    public function test_it_required_shipping_method_id_exist()
    {
        $user = User::factory()->create();
        $user->addresses()->save(
            $address = Address::factory()->make()
        );

        $this->jsonAs($user, 'post', 'api/orders', [
           'shipping_method_id' => 1,
           'address_id' => $address->id
       ])->assertJsonValidationErrors('shipping_method_id');
    }

    public function test_it_required_shipping_method_id_valid_for_given_address()
    {
        $user = User::factory()->create();
        $user->addresses()->save(
            $address = Address::factory()->make()
        );
//        $address->country->shippingMethods()->attach($shipping = ShippingMethod::factory()->create());

        $this->jsonAs($user, 'post', 'api/orders', [
           'shipping_method_id' => ShippingMethod::factory()->create()->id,
           'address_id' => $address->id
       ])->assertJsonValidationErrors('shipping_method_id');
    }

    public function test_it_can_create_order()
    {
        $user = User::factory()->create();
//        list($shipping, $address) = $this->orderDependencies($user);
        $product = $this->createProductWithStock($user);
        $user->refresh();
        list($shipping, $address) = $this->orderDependencies($user);
        $this->jsonAs($user, 'post', 'api/orders', [
           'shipping_method_id' => $shipping->id,
           'address_id' => $address->id
       ])->assertStatus(201);
    }

    protected function orderDependencies(User $user)
    {
        $user->addresses()->save(
            $address = Address::factory()->make()
        );
        $address->country->shippingMethods()->attach($shipping = ShippingMethod::factory()->create());
        return [$shipping, $address];
    }

    public function test_it_attach_the_products_to_the_order()
    {
        $user = User::factory()->create();
        $product = $this->createProductWithStock($user);
        $user->refresh();
        list($shipping, $address) = $this->orderDependencies($user);
        $res = $this->jsonAs($user, 'post', 'api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id
        ]);
        $this->assertDatabaseHas('product_variation_order', [
            'order_id' => json_decode($res->getContent())->data->id,
            'product_variation_id' => $product->id,
            'quantity' => 10
        ]);
    }

    public function test_it_fire_order_created_event()
    {
        Event::fake();
        $user = User::factory()->create();
        $product = $this->createProductWithStock($user);
        $user->refresh();
        list($shipping, $address) = $this->orderDependencies($user);
        $res = $this->jsonAs($user, 'post', 'api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id
        ]);
       Event::assertDispatched(OrderCreated::class, function ($event) use($res){
           return $event->order->id === json_decode($res->getContent())->data->id;
       });
    }

    public function test_it_empty_the_cart_when_order()
    {
        $user = User::factory()->create();
        $product = $this->createProductWithStock($user);
        $user->refresh();
        list($shipping, $address) = $this->orderDependencies($user);
        $this->jsonAs($user, 'post', 'api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id
        ])->assertStatus(200);
        $user->refresh();
        $this->assertTrue($user->cart->isEmpty());

    }

    protected function createProductWithStock($user)
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
        $product->stocks()->create([
            'quantity' => 20
        ]);
        $cart = new Cart($user);
        $cart->add($products);
        return $product;
    }

    public function test_it_fail_to_create_order_if_cart_is_empty()
    {
        $user = User::factory()->create();
        $user->refresh();
        list($shipping, $address) = $this->orderDependencies($user);
        $this->jsonAs($user, 'post', 'api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id
        ])->assertStatus(400);
    }


}
