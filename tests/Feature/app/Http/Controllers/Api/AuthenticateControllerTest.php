<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use App\Api\V1\Responses\TokenResponse;
use App\Enum\Api\ApiErrorCode;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
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
            'password' => $invalid ? 'password111' : 'password',
        ]);
    }


    public function test_successfully_authenticate(): void
    {
        $response = $this->tokenRequest();

        $response
            ->assertCreated()
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

        $response->assertJson(
            app(TokenResponse::class)->toSuccess(
                $token,
                ['token' => $token],
                Response::HTTP_CREATED
            )->toArray()
        );
    }

    public function test_failed_authenticate(): void
    {
        $response = $this->tokenRequest(true);

        $response
            ->assertUnprocessable()
            ->assertJson(
                app(TokenResponse::class)
                    ->toFailure(ApiErrorCode::CREDENTIALS_INVALID,
                        Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->toArray()
            );
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
            ->assertUnauthorized()
            ->assertJson(
                app(TokenResponse::class)->toFailure(
                    ApiErrorCode::TOKEN_EXPIRED,
                    Response::HTTP_UNAUTHORIZED
                )->toArray()
            );
    }

    public function test_token_is_invalid(): void
    {
        $this
            ->withToken('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30')
            ->deleteJson(route('api.logout'))
            ->assertUnauthorized()
            ->assertJsonStructure([
                'errors'
            ])
            ->assertJson(
                app(TokenResponse::class)->toFailure(
                    ApiErrorCode::TOKEN_INVALID,
                    Response::HTTP_UNAUTHORIZED
                )->toArray()
            );
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

    public function test_token_refresh_successful(): void
    {
        $response = $this->tokenRequest();

        $refreshToken = $response->json('data.attributes.refresh_token');

        $this->travelTo(now()->toImmutable()->addHours(2));

        $response = $this->putJson(route('api.refresh_token'), [
            'refresh_token' => $refreshToken,
        ]);

        $token = $response->json('data.id');
        $refreshToken = $response->json('data.attributes.refresh_token');

        $response->assertJson(
            app(TokenResponse::class)->withTokens($token, $refreshToken)->toArray()
        );
    }
}
