<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Actions\RefreshTokenAction;
use App\Api\V1\Actions\TokenCreateAction;
use App\Api\V1\Auth\Exceptions\JWTExpiredException;
use App\Api\V1\Auth\Exceptions\JWTParserException;
use App\Api\V1\Auth\Exceptions\JWTValidatorException;
use App\Api\V1\Responses\TokenResponse;
use App\Enum\Api\ApiErrorCode;
use App\Http\Requests\Api\AuthenticateFormRequest;
use App\Http\Requests\Api\RefreshTokenFormRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateController
{
    public function authenticate(AuthenticateFormRequest $request, TokenCreateAction $action, TokenResponse $response): TokenResponse
    {
        [$token, $refresh] = $action->handle($request->toDto());

        if ($token === null) {
            return $response->toFailure(ApiErrorCode::CREDENTIALS_INVALID, Response::HTTP_UNAUTHORIZED); //либо Response::HTTP_UNPROCESSABLE_ENTITY
        }

        return $response->withTokens($token, $refresh);
    }

    public function logout(): \Illuminate\Http\Response
    {
        auth()->logout();

        return response()->noContent();
    }

    /**
     * @throws JWTValidatorException
     * @throws JWTParserException
     * @throws JWTExpiredException
     */
    public function refresh(RefreshTokenFormRequest $request, TokenResponse $response, RefreshTokenAction $action): TokenResponse
    {
        [$token, $refresh] = $action->handle(
            $request->input('refresh_token')
        );

        if ($token === null) {
            return $response->toFailure(
                ApiErrorCode::TOKEN_REFRESH_FAILED,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $response->withTokens($token, $refresh);
    }

}
