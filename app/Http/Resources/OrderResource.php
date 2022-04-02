<?php

namespace App\Http\Resources;

use App\Cart\Money;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'created_at' => $this->created_at->toDateTimeString(),
            'subtotal' => $this->subtotal->formatted(),
            'total' => $this->total->formatted(),
            'products' => ProductVariationResource::collection($this->whenLoaded('products')),
            'address' => AddressResource::make($this->whenLoaded('address')),
            'shipping_method' => ShippingMethodResource::make($this->whenLoaded('shippingMethod'))
        ];
    }
}
