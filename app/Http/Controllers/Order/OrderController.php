<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->where('status', 1)->get();

        return view('dashboard', compact('orders'));
    }

    public function show(Order $order)
    {
        if (!Auth::user()->orders->contains($order)) {
            return false;
        }

        return view('profile.order.orders-users-form', compact('order'));
    }
}
