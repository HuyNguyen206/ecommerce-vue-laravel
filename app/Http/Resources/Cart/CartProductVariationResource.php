<?php

namespace App\Http\Resources\Cart;

use App\Cart\Money;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductVariationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductVariationResource extends ProductVariationResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $quantity = $this->pivot->quantity;
        $total = $this->getTotal($quantity, $this->price);
        return array_merge(parent::toArray($request), [
            'product' => new ProductIndexResource($this->product),
            'quantity' => $quantity,
            'total' => $total->formatted()
        ]);
    }

}
