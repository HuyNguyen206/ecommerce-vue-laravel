<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class MeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_if_user_isnot_authenticated()
    {
        $response = $this->getJson('api/auth/me');

        $response->assertStatus(401);
    }

    public function test_it_return_detail_user_info_if_user_is_authenticated()
    {
        $user = User::factory()->create();
//        $response = $this->postJson('api/auth/login', [
//           'email' => $user->email,
//           'password' => 'password'
//        ]);
//        $data = json_decode($response->content(), true);
//        $accessToken = $data['meta']['access_token'];
        $response = $this->jsonAs($user, 'get', 'api/auth/me');
//        $response = $this->getJson('api/auth/me', [
//            'Authorization' => "Bearer $accessToken"
//        ]);

        $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $user->id,
            'email' => $user->email
        ]);
    }
}
