<?php

namespace App\Http\Middleware\Cart\Payment\Gateways;

use App\Exceptions\PaymentFaildException;
use App\Http\Middleware\Cart\Payment\GatewayCustomerInterface;
use App\Models\PaymentMethod;
use Stripe\Customer as StripeCustomer;
use Stripe\StripeClient;
use Stripe\Charge as StripeCharge;

class StripeGatewayCustomer implements GatewayCustomerInterface
{
    protected $stripeGateway, $customer;
    public function __construct(StripeGateway $stripeGateway, StripeCustomer $customer)
    {
        $this->stripeGateway = $stripeGateway;
        $this->customer = $customer;
    }

    /**
     * @param PaymentMethod $card
     * @param $amount
     * @return mixed
     */
    public function charge(PaymentMethod $card, $amount)
    {
        try {
            StripeCharge::create([
                'currency' => 'gbp',
                'amount' => $amount,
                'customer' => $this->customer->id,
                'source' => $card->provider_id
            ]);

        }catch (\Throwable $ex) {
            throw new PaymentFaildException();
        }
    }

    /**
     * @param $token
     * @return mixed
     */
    public function addCard($token)
    {
        $card = app(StripeClient::class)->customers->createSource($this->customer->id,
            [
                'source' => $token
            ]
        );
        $this->customer->default_source = $card->id;
        $this->customer->save();
        return $this->stripeGateway->user()->paymentMethods()->create([
            'provider_id' =>  $card->id,
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'is_default' => true
        ]);
    }

    public function id(): string
    {
        return $this->customer->id;
    }
}
