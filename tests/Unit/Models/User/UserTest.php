<?php

namespace Tests\Unit\Models\User;

use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashed_the_password_when_creating()
    {
       $user = User::factory()->create(['password' => 'password']);
       $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_user_can_have_many_product_variation_in_cart()
    {
        $user = User::factory()->create();
        $user->cart()->attach(ProductVariation::factory(3)->create(),  ['quantity' => 4]);
        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    public function test_user_have_quantity_for_each_cart_product()
    {
        $user = User::factory()->create();
        $user->cart()->attach(ProductVariation::factory(3)->create(),  ['quantity' => 4]);
        $user->cart->each(function ($productVariation) {
            $this->assertEquals(4, $productVariation->pivot->quantity);
        });
//        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    public function test_user_have_many_addresses()
    {
        $user = User::factory()->create();
        $user->addresses()->saveMany(Address::factory(3)->make());

        $this->assertCount(3,$user->addresses);
    }

    public function test_user_have_many_orders()
    {
        $user = User::factory()->create();
        $user->orders()->saveMany(Order::factory(3)->make());

        $this->assertEquals(3,$user->orders()->count());
    }

    public function test_it_has_many_payment_methods()
    {
        $user = User::factory()->create();
        $user->paymentMethods()->saveMany(PaymentMethod::factory(3)->make());
        $this->assertEquals(3, $user->paymentMethods()->count());
    }



}
