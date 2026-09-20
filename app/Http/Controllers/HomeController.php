<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredProducts = Product::with(['category', 'brand', 'variants'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::with(['category', 'brand', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::where('is_active', true)->take(8)->get();

        return view('welcome', compact('featuredCategories', 'featuredProducts', 'latestProducts', 'brands'));
    }
}
