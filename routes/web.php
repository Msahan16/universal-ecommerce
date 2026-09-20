<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Orders & Tracking
Route::get('/order/success/{order_number}', [OrderTrackingController::class, 'success'])->name('orders.success');
Route::get('/order/track', [OrderTrackingController::class, 'track'])->name('orders.track');

// Custom Quotations
Route::get('/quotation/request', [\App\Http\Controllers\QuotationController::class, 'create'])->name('quotations.create');
Route::post('/quotation/request', [\App\Http\Controllers\QuotationController::class, 'store'])->name('quotations.store');
Route::get('/quotation/track', [\App\Http\Controllers\QuotationController::class, 'track'])->name('quotations.track');
Route::get('/quotation/{reference}', [\App\Http\Controllers\QuotationController::class, 'show'])->name('quotations.show');
Route::post('/quotation/{reference}/accept', [\App\Http\Controllers\QuotationController::class, 'accept'])->name('quotations.accept');

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderTrackingController::class, 'myOrders'])->name('customer.orders');
    Route::get('/my-orders/{id}', [OrderTrackingController::class, 'show'])->name('customer.orders.show');
    
    Route::get('/dashboard', function () {
        return redirect()->route('customer.orders');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes (Dedicated & Isolated from Customers)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Management Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', AdminProductController::class);

        // Categories
        Route::resource('categories', AdminCategoryController::class);

        // Brands
        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
        Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

        // Attributes (Dynamic)
        Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
        Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');
        Route::delete('attributes/{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
        Route::post('attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
        Route::delete('attributes/values/{value}', [AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');

        // Orders
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Coupons
        Route::get('coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
        Route::post('coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
        Route::delete('coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

        // Website Customization / CMS
        Route::get('cms', [CmsController::class, 'index'])->name('cms.index');
        Route::post('cms', [CmsController::class, 'update'])->name('cms.update');

        // Custom Quotations
        Route::get('quotations', [\App\Http\Controllers\Admin\QuotationController::class, 'index'])->name('quotations.index');
        Route::get('quotations/{quotation}', [\App\Http\Controllers\Admin\QuotationController::class, 'show'])->name('quotations.show');
        Route::post('quotations/{quotation}/generate', [\App\Http\Controllers\Admin\QuotationController::class, 'generate'])->name('quotations.generate');
        Route::post('quotations/{quotation}/status', [\App\Http\Controllers\Admin\QuotationController::class, 'updateStatus'])->name('quotations.status');

        // Customers
        Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    });

require __DIR__.'/auth.php';