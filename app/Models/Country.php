<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected static $unguarded = true;
    use HasFactory;

    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class, 'country_shipping_method');
    }


}
