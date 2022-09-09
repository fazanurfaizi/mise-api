<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itShouldReturnJwtInResponse()
    {
        $user = User::factory()->create();

        $response = $this->json('POST', route('api.auth.login'), [
            'username' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('access_token', $response->json('data'));
    }

    /**
     * @test
     */
    public function itShouldReturnAuthenticatedUser()
    {
        $user = User::factory()->create();

        $response = $this->json('POST', route('api.auth.login'), [
            'username' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    }
}
