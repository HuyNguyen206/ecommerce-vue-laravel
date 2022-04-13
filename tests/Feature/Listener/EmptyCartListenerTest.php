<?php

namespace Tests\Feature\Listener;

use App\Cart\Cart;
use App\Listeners\EmptyCart;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmptyCartListenerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_should_clear_the_cart()
    {
       $cart = new Cart($user = User::factory()->create());
        $user->cart()->attach(ProductVariation::factory()->create(),[
            'quantity' => 2
        ]);
        $listener = new EmptyCart($cart);
        $listener->handle();
        $this->assertTrue($user->fresh('cart')->cart->isEmpty());
    }
}
