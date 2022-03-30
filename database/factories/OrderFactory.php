<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subtotal' => $this->faker->numberBetween(1, 1000),
            'user_id' => User::factory()->create(),
            'address_id' => Address::factory()->create(),
            'shipping_method_id' => ShippingMethod::factory()->create()
        ];
    }
}
