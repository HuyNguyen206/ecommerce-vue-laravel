<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_set_old_payment_methods_to_non_default_when_create_new_one()
    {
        $paymentMethod = PaymentMethod::factory()->create();
        PaymentMethod::factory()->create([
            'user_id' => $paymentMethod->user_id
        ]);
        $paymentMethod->refresh();
        $this->assertFalse($paymentMethod->is_default);
    }

    public function test_it_belong_to_user()
    {
        $paymentMethod = PaymentMethod::factory()->create();
        $this->assertInstanceOf(User::class,$paymentMethod->user);
    }
}
