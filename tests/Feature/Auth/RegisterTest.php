<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_required_a_name()
    {
        $response = $this->postJson('/api/auth/register');

        $response->assertJsonValidationErrors([
                'name'
        ]);
    }

    public function test_it_required_a_email()
    {
        $response = $this->postJson('/api/auth/register');

        $response->assertJsonValidationErrors([
                'email'
        ]);
    }

    public function test_it_required_a_password()
    {
        $response = $this->postJson('/api/auth/register');

        $response->assertJsonValidationErrors([
            'password'
        ]);
    }


    public function test_it_required_a_valid_email()
    {
        $response = $this->postJson('/api/auth/register', [
           'email' => 'gssd'
        ]);

        $response->assertJsonValidationErrors([
            'email'
        ]);
    }

    public function test_it_required_a_unique_email()
    {
        $response = $this->postJson('/api/auth/register', [
           'email' => 'huy@gmail.com',
            'name' => 'huy',
            'password' => 'password'
        ]);
        $response2 = $this->postJson('/api/auth/register', [
           'email' => 'huy@gmail.com'
        ]);

        $response2->assertJsonValidationErrors([
            'email'
        ]);
    }

    public function test_it_can_register_user()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'huy@gmail.com',
            'name' => 'huy',
            'password' => 'password'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'email' => 'huy@gmail.com'
        ]);
        $this->assertDatabaseHas('users', [
                'email' => 'huy@gmail.com',
                'name' => 'huy',
        ]);
    }
}
