<?php

namespace Tests\Unit\Models\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashed_the_password_when_creating()
    {
       $user = User::factory()->create(['password' => 'password']);
       $this->assertTrue(Hash::check('password', $user->password));

    }
}
