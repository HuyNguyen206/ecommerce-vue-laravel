<?php

namespace Tests\Feature\Collection;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductVatiationCollectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_get_a_syncing_array()
    {
       $user = User::factory()->create();
       $user->cart()->attach($product = ProductVariation::factory()->create([
           'price' => 100
       ]), [
           'quantity' => 2
       ]);
       $cart = new Cart($user);
       $syncingArray = $cart->products()->forSyncing();
       $this->assertArrayHasKey($product->id, $syncingArray);
    }
}
