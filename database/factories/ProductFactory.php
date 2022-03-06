<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariationType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $name = $this->faker->unique()->name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(5),
            'price' => $this->faker->numberBetween(10, 2000),
        ];
    }
}
