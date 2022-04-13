<?php

namespace App\Http\Middleware\Cart\Payment;

use App\Models\User;

interface Gateway
{
    public function withUser(User $user);
    public function createCustomer();
}
