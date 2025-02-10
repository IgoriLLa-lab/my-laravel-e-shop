<?php

namespace App\Http\Controllers\Basket;

use App\Components\BasketLogic;
use App\Http\Controllers\Controller;
use App\Http\Services\BasketService;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket(): View|Factory|Application
    {
        $order = (new BasketLogic())->getOrder();

        return view('basket', compact('order'));
    }

    /**
     * @return Factory|Application|View|RedirectResponse
     */
    public function basketPlace(): Factory|Application|View|RedirectResponse
    {
        $basket = new BasketLogic();

        $order = $basket->getOrder();

        if (!$basket->countAvailable()) {
            session()->flash('warning', '  Товар недоступен для заказа');
            return redirect()->route('basket');
        }


        return view('order', compact('order'));
    }

    public function addToBasket(Product $product): RedirectResponse
    {
        $result = (new BasketLogic(true))->addProduct($product);

        if ($result) {
            session()->flash('success', 'Товар ' . $product->name . ' добавлен');
        } else {
            session()->flash('warning', 'Товар ' . $product->name . ' недоступен для заказа');
        }

        return redirect()->route('basket');

    }

    public function removeFromBasket(Product $product): RedirectResponse
    {
        (new BasketLogic())->removeProduct($product);

        session()->flash('warning', 'Удален товар ' . $product->name);

        return redirect()->route('basket');
    }

    public function basketConfirm(Request $request): RedirectResponse
    {
        $basket = new BasketLogic();

        $order = $basket->getOrder();

        if ($basket->saveOrder($request->name, $request->phone)) {
            session()->flash('result', 'Ваш заказ № ' . $order->id . ' принят в обработку');
        } else {
            session()->flash('warning', 'Товар недоступен для заказа');
        }

        return redirect()->route('index');
    }

}
