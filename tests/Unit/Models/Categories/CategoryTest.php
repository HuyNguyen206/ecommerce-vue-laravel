<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_many_children()
    {
       $category = Category::factory()->create();
       $category->childrens()->save(Category::factory()->make());

       $this->assertInstanceOf(Category::class, $category->childrens()->first());
    }

    public function test_it_can_fetch_only_parents()
    {
        $category = Category::factory()->create();
        $category->childrens()->save(Category::factory()->make());

        $this->assertEquals(1, Category::parent()->count());
    }

    public function test_it_is_orderable_by_a_number_order()
    {
        $category = Category::factory()->create([
            'order' => 2
        ]);
        $anotherCategory = Category::factory()->create([
            'order' => 1
        ]);
        self::assertEquals($anotherCategory->id, Category::ordered()->value('id'));
    }

    public function test_it_has_many_products()
    {
        $category = Category::factory()->create([
            'name' => 'Category A'
        ]);
        $category->products()->attach(Product::factory(4)->create());
         $this->assertEquals(4, $category->loadCount('products as count')->count);
    }
}
