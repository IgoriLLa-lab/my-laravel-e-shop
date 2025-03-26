<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AuthenticateControllerTest extends TestCase
{
    use RefreshDatabase;

    private function tokenRequest(bool $invalid = false): TestResponse
    {
        $user = UserFactory::new([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ])->create();

        return $this->postJson(route('api.authenticate'), [
            'email' => $user->email,
            'password' => $invalid ? 'password' : 'password111',
        ]);
    }


    public function test_successfully_authenticate(): void
    {
        $response = $this->tokenRequest();

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'token'
                    ]
                ],
                'links'
            ]);

        $token = $response->json('data.id');

        $this->assertStringContainsString('.', $token);
    }

    public function test_failed_authenticate(): void
    {
        $response = $this->tokenRequest(true);

        $response->assertUnauthorized();
    }

    public function test_expired_token(): void
    {
        $response = $this->tokenRequest();

        $token = $response->json('data.id');

        $this
            ->withToken($token)
            ->deleteJson(route('api.logout'))
            ->assertNoContent();

        $this->travelTo(now()->addDay());

        $this
            ->withToken($token)
            ->deleteJson(route('api.logout'))
            ->assertUnauthorized();
    }

    public function test_token_is_invalid(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30')
            ->deleteJson(route('api.logout'))
            ->assertUnauthorized()
            ->assertJsonStructure([
                'errors'
            ]);
    }


    public function test_successful_logout(): void
    {
        $response = $this->tokenRequest();

        $token = $response->json('data.id');

        $this
            ->withToken($token)
            ->deleteJson(route('api.logout'))
            ->assertNoContent();
    }

    public function test_token_in_blacklist(): void
    {
        $response = $this->tokenRequest();

        $token = $response->json('data.id');

        $this
            ->withToken($token)
            ->deleteJson(route('api.logout'))
            ->assertNoContent();

        $this
            ->withToken($token)
            ->deleteJson(route('api.logout'))
            ->assertUnauthorized();
    }
}
