<?php

namespace App\Api\V1\Controllers\Cart;

use App\Api\V1\Responses\ApiData;
use App\Api\V1\Responses\CartResponse;
use App\Api\V1\Services\CartManager;
use App\Models\Product;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class CartController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(CartManager $cart, CartResponse $response): CartResponse
    {
        return $response->toCollection(
            $cart->items()
        );

//        return $response->toCollection(
//            $cart->items()->map(fn(CartItem $item) => new ApiData(
//                $response->type(),
//                $item->getKey(),
//                $item->toArray()
//            ))
//        );
    }

    public function add(int $id, CartManager $cart): \Illuminate\Http\Response
    {
        $product = Product::query()->findOrFail($id);
        $cart->add($product);

        return $cart->response();
    }
}
