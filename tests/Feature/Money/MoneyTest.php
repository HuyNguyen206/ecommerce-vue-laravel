<?php

namespace Tests\Feature\Money;

use App\Cart\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_get_raw_amount()
    {
        $money = new Money(1000);
        $this->assertEquals(1000,$money->amount());
    }

    public function test_it_can_add_up()
    {
        $money = new Money(1000);
        $this->assertEquals(1200,$money->add(new Money(200))->amount());
    }


}
