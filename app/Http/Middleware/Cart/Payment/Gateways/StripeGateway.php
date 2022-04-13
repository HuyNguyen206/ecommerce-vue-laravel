<?php

namespace App\Http\Middleware\Cart\Payment\Gateways;

use App\Http\Middleware\Cart\Payment\Gateway;
use App\Models\User;
use Stripe\Customer as StripeCustomer;
use Stripe\StripeClient;

class StripeGateway implements Gateway
{

    protected $user;
    /**
     * @param User $user
     * @return mixed
     */
    public function withUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function createCustomer()
    {
        if ($this->user->gateway_customer_id) {
            return $this->getCustomer();
        }
        $customer = new StripeGatewayCustomer($this, $this->createStripeCustomer());
        $this->user->update([
           'gateway_customer_id' =>  $customer->id()
        ]);
        return $customer;
    }

    protected function createStripeCustomer()
    {
        $stripeClient = app(StripeClient::class);
        return $stripeClient->customers->create([
            'email' => $this->user->email
        ]);
    }

    public function getCustomer()
    {
        return new StripeGatewayCustomer($this, StripeCustomer::retrieve($this->user->gateway_customer_id));
    }
}
