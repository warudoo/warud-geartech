<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'featuredProducts' => Product::query()->active()->with('category')->where('featured', true)->latest()->take(6)->get(),
            'latestProducts' => Product::query()->active()->with('category')->latest()->take(8)->get(),
            'categories' => Category::query()->active()->withCount('products')->get(),
        ]);
    }
}
