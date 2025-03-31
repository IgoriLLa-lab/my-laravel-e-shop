<?php

namespace App\Api\V1\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CartManager
{
    protected string $sessionKey = 'cart';

    /**
     * Получить все товары в корзине.
     *
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function items(): Collection
    {
        $cart = session()->get($this->sessionKey, []);
        return response()->json(['data' => $cart]);
    }

    /**
     * Добавить товар в корзину.
     *
     * @param Product $product
     * @return void
     */
    public function add(Product $product): void
    {
        $cart = session()->get($this->sessionKey, []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put($this->sessionKey, $cart);
    }

    /**
     * Создать ответ без содержимого с указанным статусом.
     *
     * @return \Illuminate\Http\Response
     */
    public function response(): \Illuminate\Http\Response
    {
        return response()->noContent(Response::HTTP_CREATED);
    }
}
