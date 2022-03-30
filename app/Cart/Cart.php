<?php

namespace App\Cart;

use App\Models\ShippingMethod;
use App\Models\User;

class Cart
{
    protected $user;
    protected $changed = false;
    protected $shippingId;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function add($products)
    {
       $this->user->cart()->syncWithoutDetaching($this->getStorePayload($products));
    }

    protected function getStorePayload($products)
    {
//        dd($products);
        $this->user->fresh('cart');
        return collect($products)->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ];
        });
    }

    protected function getCurrentQuantity($productId)
    {
        if ($product = $this->user->cart->where('id', $productId)->first()) {
            return $product->pivot->quantity;
        }
        return 0;
    }

    public function update($productId, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity
        ]);
    }

    public function destroy($productId)
    {
        $this->user->cart()->detach($productId);
    }

    public function emptyCart()
    {
        $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        $this->user->loadMissing('cart');
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    public function subTotal()
    {
        $this->user->loadMissing('cart');
        $subTotal = $this->user->cart->sum(function ($product) {
            return $product->getTotal($product->pivot->quantity,$product->price)->amount();
        });
        return (new Money($subTotal));
    }

    public function total()
    {
        $subTotal = $this->subTotal();
        if ($this->shippingId) {
            $subTotal->add(ShippingMethod::find($this->shippingId)->price);
        }
        return $subTotal;
    }

    public function sync()
    {
        $user = $this->user;
        $user->cart->load('stock')->each(function ($product){
            $quantity = $product->minStock($originQuantity = $product->pivot->quantity);
            if ($quantity !== $originQuantity) {
                $this->changed = true;
                $product->pivot->update([
                    'quantity' => $quantity
                ]);
            }
        });
    }

    public function isChanged()
    {
        return $this->changed;
    }

    public function withShipping($shippingId)
    {
        $this->shippingId = $shippingId;
        return $this;
    }

    public function products()
    {
        return $this->user->cart;
    }

}
