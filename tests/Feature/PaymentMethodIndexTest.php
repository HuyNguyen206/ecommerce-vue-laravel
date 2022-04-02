<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_user_unauthenticate()
    {
        $response = $this->getJson('api/payment-methods')->assertStatus(401);

    }

    public function test_it_return_collection_payment_methods()
    {
        $user = User::factory()->create();
        $user->paymentMethods()->saveMany($paymentMethods = PaymentMethod::factory(4)->make());
        $this->jsonAs($user, 'get','api/payment-methods')->assertJsonFragment([
            'id' => ($payment = $paymentMethods->random())->id,
            'card_type' => $payment->card_type
        ]);
    }
}
