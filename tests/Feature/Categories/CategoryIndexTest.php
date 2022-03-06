<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_return_a_collection_of_categories()
    {
        $categories = Category::factory(5)->create();
        $response = $this->get('api/categories');
        $categories->each(function ($category) use($response){
            $response->assertJsonFragment([
                'slug' => $category->slug
            ]);
        });
//             $assertData = $categories->map(function ($category) use($response){
//            return [
//                'slug' => $category->slug
//            ];
//        })->toArray();
//             $response->assertJsonFragment(...$assertData);
        $response->assertStatus(200);
    }

    public function test_it_return_only_parent_category()
    {
        $categories = Category::factory(3)->create();
        $children1 = Category::factory()->create([
            'parent_id' => $categories->random()->id
        ]);
        $children2 = Category::factory()->create([
            'parent_id' => $categories->random()->id
        ]);
        $children3 = Category::factory()->create([
            'parent_id' => $categories->random()->id
        ]);
        $response = $this->getJson('api/categories');
        $response->assertJsonCount(3,'data');
    }

    public function test_it_return_order_by_their_order()
    {
        $cat1 = Category::factory()->create([
            'order' => 2
        ]);
        $cat2 = Category::factory()->create([
            'order' => 1
        ]);
        $response = $this->getJson('api/categories');
        $response->assertSeeInOrder([
            $cat2->order,
            $cat1->order
        ]);
    }
}
