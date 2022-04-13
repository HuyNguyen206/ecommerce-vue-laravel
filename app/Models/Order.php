<?php

namespace App\Models;

use App\Cart\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const PAYMENT_FAIL = 'payment_fail';
    const COMPLETED = 'completed';
    protected static $unguarded = true;
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->state = self::PENDING;
        });
    }

    public function products()
    {
        return $this->belongsToMany(ProductVariation::class, 'product_variation_order')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getSubtotalAttribute($value)
    {
        return (new Money($value));
    }

    public function getTotalAttribute()
    {
        return $this->subtotal->add($this->shippingMethod->price);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}

