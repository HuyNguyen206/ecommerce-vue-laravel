<?php

namespace App\Http\Middleware\Cart\Payment;

use App\Models\PaymentMethod;

interface GatewayCustomerInterface
{
    public function charge(PaymentMethod $card, $amount);
    public function addCard($token);
    public function id();
}
