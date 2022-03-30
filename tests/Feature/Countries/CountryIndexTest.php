<?php

namespace Tests\Feature\Countries;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_return_countries()
    {
        $countries = Country::factory(20)->create();
        $country = $countries->random();
        $this->getJson('api/countries')->assertJsonFragment([
            'id' => $country->id,
            'name' => $country->name
        ]);
//        $response->assertStatus(200);
    }
}
