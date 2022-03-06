<?php

namespace Models\Product;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_use_slug_for_route_key_name()
    {
        $this->assertEquals((new Product())->getRouteKeyName(), 'slug');
    }

    public function test_it_has_many_categories()
    {
        $product = Product::factory()->create([
            'name' => 'Procduct A'
        ]);
        $product->categories()->attach(Category::factory(4)->create());
        $this->assertEquals(4, $product->loadCount('categories as count')->count);
    }

    public function test_it_has_many_variations()
    {
        $product = Product::factory()->create();
        $product->variations()->saveMany(ProductVariation::factory(4)->make());
        $this->assertEquals(4, $product->loadCount('variations as count')->count);
    }

    public function test_it_return_a_money_instance_for_the_price()
    {
        $product = Product::factory()->create();
        $this->assertInstanceOf(Money::class, $product->price);
    }

    public function test_it_return_a_formatted_price()
    {
        $product = Product::factory()->create(
            [
                'price' => 12
            ]
        );
        $this->assertEquals('£0.12', $product->formattedPrice);
    }
}
