<?php

namespace App\Listeners;

use App\Cart\Cart;
use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmptyCart implements ShouldQueue
{
    protected $cart;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
       $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle()
    {
        $this->cart->emptyCart();
    }
}
