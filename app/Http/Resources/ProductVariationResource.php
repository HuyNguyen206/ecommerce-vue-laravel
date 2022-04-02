<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection) {
            return ProductVariationResource::collection($this->resource);
        }
        $stockData = $this->getStock();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->formattedPrice,
            'price_varies' => $this->priceVaries(),
            'stock_count' => (int) $stockData->quantity,
            'in_stock' => $stockData->in_stock,
            'type' => $this->type->name,
            'product' => new ProductIndexResource($this->product)
        ];
    }
}
