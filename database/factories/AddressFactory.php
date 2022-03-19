<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'country_id' => Country::factory()->create(),
            'name' => $this->faker->name,
            'postal_code' => $this->faker->postcode,
            'address_1' => $this->faker->address,
            'city' => $this->faker->city
        ];
    }
}
