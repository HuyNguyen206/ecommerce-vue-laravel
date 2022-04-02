<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'card_type' => Arr::random(['visa', 'uob']),
            'last_four' => 1234,
            'user_id' => User::factory()->create(),
            'provider_id' => $this->faker->unique()->uuid(),
            'is_default' => true
        ];
    }
}
