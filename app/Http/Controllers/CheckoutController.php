<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty. Add some products before checkout.');
        }

        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $shipping = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $coupon = $this->cartService->getCoupon();
        $user = Auth::user();

        return view('checkout.index', compact('items', 'subtotal', 'discount', 'shipping', 'total', 'coupon', 'user'));
    }

    public function store(Request $request)
    {
        $items = $this->cartService->getItems();
        if (empty($items)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'payment_method' => 'required|in:cod,card',
            'notes' => 'nullable|string|max:1000',
        ]);

        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $shipping = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $couponData = $this->cartService->getCoupon();

        $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6));

        $order = DB::transaction(function () use ($validated, $orderNumber, $subtotal, $discount, $shipping, $total, $couponData, $items) {
            $paymentStatus = ($validated['payment_method'] === 'card') ? 'paid' : 'pending';

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'country' => $validated['country'] ?? 'Sri Lanka',
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'status' => 'confirmed',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'coupon_code' => $couponData['code'] ?? null,
                'shipping_fee' => $shipping,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Save order items & decrement stock
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $item['name'],
                    'variant_name' => $item['variant_name'] ?? null,
                    'sku' => $item['sku'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                    'attributes_snapshot' => $item['attributes'] ?? null,
                ]);

                // Decrement product or variant inventory
                if (!empty($item['variant_id'])) {
                    ProductVariant::where('id', $item['variant_id'])->decrement('stock', $item['quantity']);
                } else {
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }

            // If coupon used, increment coupon usage
            if (!empty($couponData['code'])) {
                Coupon::where('code', $couponData['code'])->increment('used_count');
            }

            return $order;
        });

        // Clear cart
        $this->cartService->clear();

        // Send confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPlacedMail($order));
        } catch (\Throwable $e) {
            // Mail logging / fallback
            \Illuminate\Support\Facades\Log::warning('Order confirmation email could not be sent: ' . $e->getMessage());
        }

        return redirect()->route('orders.success', ['order_number' => $order->order_number])
            ->with('success', 'Your order has been placed successfully!');
    }
}
