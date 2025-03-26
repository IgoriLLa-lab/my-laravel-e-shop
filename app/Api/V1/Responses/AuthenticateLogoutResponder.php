<?php

namespace App\Api\V1\Responses;

use App\Api\V1\Contracts\ResponseResolverContract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthenticateLogoutResponder extends AbstractApiResponder
{
    private function __construct(
        private ResponseFactory $response,
    )
    {

    }


    public function type(): string
    {
        return 'tokens';
    }

    public function links(): array
    {
        return [];
    }

    public function respond(ResponseResolverContract $resolver): Response
    {
        $resolver->resolve();

        return $this->response->noContent();
    }

    public function errors(): JsonResponse
    {
        return $this->response->json(
            $this->errorResponse('unauthenticated', 'Unauthenticated'),
            Response::HTTP_UNAUTHORIZED
        );
    }

}
