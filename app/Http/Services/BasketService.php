<?php

namespace App\Http\Services;

use App\Components\BasketLogic;
use App\Models\Product;

class BasketService
{
    protected BasketLogic $logic;

    public function __construct(BasketLogic $logic)
    {
        $this->logic = $logic;
    }

    public function addProductToBasket(Product $product): void
    {
        $this->logic->addProduct();
    }

    public function removeProductFromBasket(Product $product): void
    {
        $this->logic->removeProduct($product);
    }

    public function confirmBasketOrder(string $name, string $phone): bool
    {
        return $this->logic->confirmOrder($name, $phone);
    }

}
