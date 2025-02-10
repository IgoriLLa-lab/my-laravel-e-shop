<?php

namespace App\Components;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BasketLogic
{
    protected $order;

    /**
     * @param bool $createOrder
     */
    public function __construct(bool $createOrder = false)
    {
        $orderId = session('orderId');

        if (is_null($orderId) && $createOrder) {
            $this->order = Order::create();
            session(['orderId' => $this->order->id]);
        } else {
            $this->order = Order::find($orderId);
        }
    }

    /**
     * @return Order
     */
    public function getOrder(): Order|null
    {
        return $this->order ?? null;
    }

    /**
     * @param string $name
     * @param string $phone
     * @return bool
     */
    public function saveOrder(string $name, string $phone): bool
    {
        if (!$this->countAvailable(true)) {
            return false;
        }
        return $this->order->saveOrder($name, $phone);
    }

    /**
     * @param Product $product
     * @return true
     */
    public function addProduct(Product $product): bool
    {
        if ($this->order->products->contains($product->id)) {
            $pivotRow = $this->getPivotRow($product);

            $pivotRow->count++;

            if ($pivotRow->count > $product->count) {
                return false;
            }

            $pivotRow->update();

        } else {
            if ($product->count == 0) {
                return false;
            }
            $this->order->products()->attach($product->id);
        }

        if (Auth::check()) {
            $this->order->user_id = Auth::id();
            $this->order->save();
        }

        return true;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function removeProduct(Product $product): void
    {
        if ($this->order->products->contains($product->id)) {
            $pivotRow = $this->getPivotRow($product);

            if ($pivotRow->count < 2) {
                $this->order->products()->detach($product->id);

            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }
        }

        (new \App\Models\Order)->getTotalCost();
    }

    public function countAvailable(bool $updateCount = false): bool
    {
        foreach ($this->order->products as $product) {
            if ($product->count < $this->getPivotRow($product)->count) {
                return false;
            }

            if ($updateCount) {
                $product->count -= $this->getPivotRow($product)->count;
            }
        }

        if ($updateCount) {
            $this->order->products->map->save();
        }

        return true;
    }

    public function getPivotRow(Product $product)
    {
        return $this->order->products()->where('product_id', $product->id)->first()->pivot;
    }


}
