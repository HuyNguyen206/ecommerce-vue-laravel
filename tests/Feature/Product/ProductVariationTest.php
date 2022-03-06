<?php

namespace Tests\Feature\Product;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_belong_to_one_variation_type()
    {
        $product = Product::factory()->create();
        $productVariationType = ProductVariationType::factory()->create();
        $productVariation = ProductVariation::factory()->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $productVariationType->id
        ]);
        $this->assertInstanceOf(ProductVariationType::class, $productVariation->type);
//        $productVariation->
    }

    public function test_it_belong_to_one_product()
    {
        $product = Product::factory()->create();
        $productVariationType = ProductVariationType::factory()->create();
        $productVariation = ProductVariation::factory()->create([
            'product_id' => $product->id,
            'product_variation_type_id' => $productVariationType->id
        ]);
        $this->assertInstanceOf(Product::class, $productVariation->product);
    }

    public function test_it_return_a_money_instance_for_the_price()
    {
        $variation = ProductVariation::factory()->create();
        $this->assertInstanceOf(Money::class, $variation->price);
    }

    public function test_it_return_a_formatted_price()
    {
        $variation = ProductVariation::factory()->create(
            [
                'price' => 12
            ]
        );
        $this->assertEquals('£0.12', $variation->formattedPrice);
    }

    public function test_it_return_a_base_formatted_price_if_price_is_null()
    {
        $product = Product::factory()->create();
        $variation = ProductVariation::factory()->create(
            [
                'price' => null,
                'product_id' => $product->id
            ]
        );
        $this->assertEquals($variation->price->amount(), $product->price->amount());
    }

    public function test_it_can_check_variation_price_is_true_if_different_to_product()
    {
        $product = Product::factory()->create([
            'price' => 1000
        ]
        );
        $variation = ProductVariation::factory()->create(
            [
                'price' => 2000,
                'product_id' => $product->id
            ]
        );
        $this->assertTrue($variation->priceVaries());
    }

    public function test_it_has_many_stocks()
    {
        $product = ProductVariation::factory()->create();
        $product->stocks()->saveMany(Stock::factory(5)->make());
        $this->assertEquals(5, $product->loadCount('stocks as count')->count);
    }


}
