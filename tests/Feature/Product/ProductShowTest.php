<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_a_product_cant_be_found()
    {
        $response = $this->getJson('api/products/not-exist');

        $response->assertStatus(404);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_be_found()
    {
        $product = Product::factory()->create();
        $response = $this->getJson("api/products/$product->slug");
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => $product->name
        ]);
        $response->assertStatus(200);
    }
}
