<?php

namespace App\Api\V1\Responses;

use App\Enum\Api\ApiErrorCode;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
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

    public function toSuccess(ApiData $data, int $status = Response::HTTP_OK): static
    {
        $this->status = $status;
        $this->data = array_filter([
            'data' => $data->toArray(),
            'links' => $this->links() === [] ? null : $this->links()
        ]);

        return $this;
    }

    /**
     * @param Collection<array-key, ApiData> $items
     * @return $this
     */
    public function toCollection(Collection $items, int $status = Response::HTTP_OK): static
    {
        $this->status = $status;
        $this->data = array_filter([
            'data' => $items->toArray(),
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
