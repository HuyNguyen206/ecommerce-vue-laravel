<?php

namespace Database\Factories;

use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 100),
            'product_variation_id' => ProductVariation::factory()->create()
        ];
    }
}
