<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::whereIn('status', ['new', 'processing', 'confirmed'])->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        $recentOrders = Order::with('user')->latest()->take(7)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'totalCustomers',
            'lowStockCount',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
