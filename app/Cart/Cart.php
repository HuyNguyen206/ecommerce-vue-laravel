<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    protected $user;

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
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    public function subTotal()
    {
        $subTotal = $this->user->cart->sum(function ($product) {
            return $product->getTotal($product->pivot->quantity,$product->price)->amount();
        });
        return (new Money($subTotal));
    }

    public function total()
    {
        return $this->subTotal();
    }

    public function sync()
    {
        $user = $this->user;
        $user->cart->each(function ($product){
            $quantity = $product->minStock($originQuantity = $product->pivot->quantity);
            if ($quantity !== $originQuantity) {
                $product->pivot->update([
                    'quantity' => $quantity
                ]);
            }
        });
    }
}
