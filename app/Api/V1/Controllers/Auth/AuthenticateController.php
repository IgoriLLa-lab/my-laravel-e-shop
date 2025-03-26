<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Responses\AuthenticateLogoutResolver;
use App\Api\V1\Responses\AuthenticateLogoutResponder;
use App\Api\V1\Responses\AuthenticateResolver;
use App\Api\V1\Responses\AuthenticateResponder;
use App\Http\Requests\Api\AuthenticateFormRequest;
use Illuminate\Http\Request;

class AuthenticateController
{
    public function authenticate(AuthenticateFormRequest $request, AuthenticateResolver $resolver, AuthenticateResponder $responder): \Illuminate\Http\JsonResponse
    {

        try {
            return $responder->respond(
                $resolver->with($request->toDto())
            );
        }catch (\Throwable $e){
            return $responder->errors($e);
        }

    }

    public function logout(AuthenticateLogoutResolver $resolver, AuthenticateLogoutResponder $responder, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $responder->respond(
                $resolver->with(
                    $request->bearerToken()
                )
            );
        }catch (\Throwable $e){
            return $responder->errors($e);
        }
    }

}
