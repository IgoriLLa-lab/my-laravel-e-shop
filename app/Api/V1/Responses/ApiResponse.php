<?php

namespace App\Api\V1\Responses;

use App\Enum\Api\ApiErrorCode;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use phpseclib3\Math\PrimeField\Integer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiResponse implements Responsable, Arrayable
{
    private array $data = [];

    private int $status = Response::HTTP_OK;

    public function __construct(
        private readonly ResponseFactory $response,
    )
    {
    }

    abstract public function type(): string;

    abstract public function links(): array;

    public function toSuccess(string $id, array $attributes, int $status = Response::HTTP_OK): static
    {
        $this->status = $status;
        $this->data = array_filter([
            'data' => [
                'type' => $this->type(),
                'id' => $id,
                'attributes' => $attributes,
            ],
            'links' => $this->links() === [] ? null : $this->links()
        ]);

        return $this;
    }

    public function toFailure(ApiErrorCode $code, int $status = Response::HTTP_BAD_REQUEST)
    {
        $this->status = $status;
        $this->data = [
            'errors' => [
                'id' => $this->type(),
                'code' => $code->value,
                'title' => $code->toString(),
            ]
        ];
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function toResponse($request)
    {
        // TODO: Implement toResponse() method.
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}
