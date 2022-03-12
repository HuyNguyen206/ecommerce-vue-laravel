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

    public function stock()
    {
        return $this->belongsToMany(
            ProductVariation::class,
            'product_variation_stock_view'
        )->withPivot([
            'quantity', 'quantity_orderd', 'quantity_left',
            'in_stock'
        ]);
    }

    public function getStock()
    {
        return $this->stock->first()->pivot;
    }

    public function buyer()
    {
        return $this->belongsToMany(User::class, 'cart_user');
    }

}
