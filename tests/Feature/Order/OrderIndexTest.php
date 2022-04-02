<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_user_unauthenticate()
    {
        $response = $this->getJson('api/orders')->assertStatus(401);

    }

    public function test_it_return_order_collection()
    {
        $user = User::factory()->create();
        $user->orders()->saveMany($orders = Order::factory(5)->make());
        $this->jsonAs($user, 'get', 'api/orders')
            ->assertJsonFragment([
                'id' => $orders->random()->id
            ]);
    }

    public function test_it_return_order_collection_by_the_latest_first()
    {
        $user = User::factory()->create();
        $user->orders()->save($order = Order::factory()->make());
        $user->orders()->save($lastOrder = Order::factory()->make());
        $this->jsonAs($user, 'get', 'api/orders')
            ->assertSeeInOrder([
                $lastOrder->created_at->toDateTimeString(),
                $order->created_at->toDateTimeString()
            ]);
    }

    public function test_it_has_pagination()
    {
        $user = User::factory()->create();
        $user->orders()->saveMany($orders = Order::factory(5)->make());
        $this->jsonAs($user, 'get', 'api/orders')
            ->assertJsonStructure([
                'links',
                 'meta'
            ]);
    }


}
