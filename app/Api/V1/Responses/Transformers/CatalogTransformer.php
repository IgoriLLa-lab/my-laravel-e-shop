<?php

namespace App\Api\V1\Responses\Transformers;

use App\Api\V1\Responses\ApiData;
use App\Models\Product;
use Illuminate\Contracts\Support\Arrayable;

final readonly class CatalogTransformer implements Arrayable
{
    public function __construct(
        private Product $product,
    )
    {
    }

    public function toArray(): array
    {
        return (new ApiData(
            'catalog',
            $this->product->getKey(),
            [
                'id' => $this->product->getKey()
            ]
        ))->toArray();
    }
}
