<?php

namespace App\Http\Controllers;

use App\Cart\Cart;
use App\Events\OrderCreated;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderControler extends Controller
{
    private $cart;
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
        $this->middleware('auth');
    }

    public function store(OrderStoreRequest $request)
    {
        $this->cart->sync();
        if ($this->cart->isEmpty()) {
            abort(400,'Please order at least one product with quantity');
        }
        $order = $this->createOrder($request);
        $order->products()->sync($this->cart->products()->forSyncing());
//        $order->load('shippingMethod');
        event(new OrderCreated($order));
        return new OrderResource($order);
    }

    private function createOrder(Request $request)
    {
        return $request->user()->orders()->create(array_merge($request->validated(), [
            'subtotal' => $this->cart->subTotal()->amount()
        ]));
    }
}
