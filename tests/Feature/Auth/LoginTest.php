<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_required_an_email()
    {
        $response = $this->postJson('api/auth/login',[
         'password' => 'password'
        ]);

        $response->assertStatus(422)
                  ->assertJsonValidationErrors('email');
    }

    public function test_it_required_an_password()
    {
        $response = $this->postJson('api/auth/login',[
         'email' => 'ss@gal.com'
        ]);

        $response->assertStatus(422)
                  ->assertJsonValidationErrors('password');
    }

    public function test_it_return_error_credential_when_provide_invalid_crenditial()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com'
        ]);
        $response = $this->postJson('api/auth/login',[
            'email' => $user->email,
            'password' => 'passwordd'
        ]);

        $response->assertStatus(422)
                  ->assertJsonValidationErrors('email');

        $response2 = $this->postJson('api/auth/login',[
            'email' => 'hihi@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_it_return_token_when_provide_valid_credential()
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/auth/login',[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'meta' => [
                    'access_token'
                ]
            ]);

    }

    public function test_it_return_a_user_when_provide_valid_credential()
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/auth/login',[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' =>  $user->email
            ]);

    }
}
