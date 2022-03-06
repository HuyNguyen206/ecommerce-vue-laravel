<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_show_collection_product()
    {
        $products = Product::factory(5)->create();
        $response = $this->getJson('api/products');
        $products->each(function ($product) use($response){
            $response->assertJsonFragment([
                'slug' => $product->slug
            ]);
        });
        $response->assertStatus(200);
    }

    public function test_it_has_pagination_data()
    {
        $response = $this->getJson('api/products');
        $response->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
    }
}
