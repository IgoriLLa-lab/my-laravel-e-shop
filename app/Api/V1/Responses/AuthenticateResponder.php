<?php

namespace App\Api\V1\Responses;

use App\Api\V1\Contracts\ResponseResolverContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class AuthenticateResponder extends AbstractApiResponder
{
    private function __construct(
        private readonly ResponseFactory $response,
    )
    {

    }

    public function type(): string
    {
        return 'tokens';
    }

    public function links(): array
    {
        return [
            'self' => route('api.authenticate'),
            'logout' => route('api.logout')
        ];
    }

    public function respond(ResponseResolverContract $resolver): JsonResponse
    {
        $token = $resolver->resolve();

        if ($token === null) {
            return $this->response->json([
                $this->errorResponse('user_credentials', 'Credentials is not valid'), //сделать enum с кодами
                Response::HTTP_UNAUTHORIZED
            ]);
        }

        return $this->response->json([
            $this->successResponse($token, [
                'token' => $token,
            ])
        ]);
    }

    public function errors(): JsonResponse
    {
        return $this->response->json(
            $this->errorResponse('unauthenticated', 'Unauthenticated'),
            Response::HTTP_UNAUTHORIZED
        );
    }

}
