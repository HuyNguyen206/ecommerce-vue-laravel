<?php

namespace App\Models;

use App\Cart\Money;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory, HasPrice;

    public function getPriceAttribute($value)
    {
        return $value ? new Money($value) : $this->product->price;
    }


    public function type()
    {
        return $this->belongsTo(ProductVariationType::class, 'product_variation_type_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function priceVaries()
    {
        return $this->price->amount() !== $this->product->price->amount();
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

}
