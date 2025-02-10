<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * @param Request $request
     * @return View|Factory|Application
     */
    public function index(Request $request): View|Factory|Application
    {
        $productsQuery = Product::query();

        if ($request->filled('price_from')) {
            $productsQuery->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $productsQuery->where('price', '<=', $request->price_to);
        }

        if ($request->has('hit')) {
            $productsQuery->where('hit', true);
        }
        if ($request->has('new')) {
            $productsQuery->where('new', true);
        }
        if ($request->has('recommended')) {
            $productsQuery->where('recommended', true);
        }

        $products = $productsQuery->with('category')->paginate(6)->withPath("?" . $request->getQueryString());

        return view('index', compact('products'));
    }

    /**
     * @return View|Factory|Application
     */
    public function categories(): View|Factory|Application
    {
        $categories = Category::get();
        return view('categories', compact('categories'));
    }

    public function category(string $category): View|Factory|Application
    {
        $category = Category::where('code', $category)->firstOrFail();

        return view('category', compact('category'));
    }

    /**
     * @param string $category
     * @param string $productsName
     * @return View|Factory|Application
     */
    public function product(string $category, string $productsName): View|Factory|Application
    {
        $product = Product::byCode($productsName)->firstOrFail();

        return view('product', compact('product'));
    }

    public function subscribe(Request $request, Product $product)
    {
        Subscription::create([
            'email' => $request->email,
            'product_id' => $product->id
        ]);


        return redirect()->back()->with('success', 'Мы свяжемся с вами как только товар появиться в наличии');

    }

}
