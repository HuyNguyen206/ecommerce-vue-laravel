<?php

namespace Tests\Unit\Models\Address;


use App\Models\Address;
use App\Models\Country;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_belong_to_one_country()
    {
       $address = Address::factory()->create();
       $this->assertEquals(1, $address->loadCount('country as count')->count);
    }

    public function test_it_belong_to_user()
    {
       $address = Address::factory()->create();
       $this->assertEquals(1, $address->loadCount('user as count')->count);
    }
}
