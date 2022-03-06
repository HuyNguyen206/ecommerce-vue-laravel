<?php

namespace Tests\Feature\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductScopingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_scope_by_category()
    {
        $product = Product::factory()->create([
            'name' => 'Test product'
        ]);
        $category = Category::factory()->create([
            'name' => 'test category'
        ]);
        $category2 = Category::factory()->create([
            'name' => 'test category 2'
        ]);
        $product->categories()->attach($category);
        $this->getJson("api/products?category={$category->slug}")
              ->assertJsonFragment([
                  'name' => 'Test product'
              ]);
        $this->getJson("api/products?category=$category2->slug")
              ->assertJsonCount(0, 'data');
    }
}
