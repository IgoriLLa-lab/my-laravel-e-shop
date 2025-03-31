<?php

namespace App\Api\V1\Controllers\Catalog;

use App\Api\V1\Responses\ApiData;
use App\Api\V1\Responses\Transformers\CatalogTransformer;
use App\Models\Product;

class CatalogController
{
    public function index(CatalogViewModel $model)
    {
        $products =  $model->products();

        $products->getCollection()->transform(fn(Product $product) => new CatalogTransformer($product));

        return $products;
    }
}
