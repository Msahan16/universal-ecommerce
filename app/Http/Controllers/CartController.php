<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $shipping = $this->cartService->getShippingFee();
        $total = $this->cartService->getTotal();
        $coupon = $this->cartService->getCoupon();

        return view('cart.index', compact('items', 'subtotal', 'discount', 'shipping', 'total', 'coupon'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = (int) $request->input('quantity', 1);
        $variantId = $request->filled('variant_id') ? (int) $request->input('variant_id') : null;

        $this->cartService->add((int) $request->product_id, $variantId, $quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to your cart!',
                'count' => $this->cartService->getCount(),
                'subtotal' => $this->cartService->getSubtotal(),
                'total' => $this->cartService->getTotal(),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to your cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->update($request->item_key, (int) $request->quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count' => $this->cartService->getCount(),
                'subtotal' => $this->cartService->getSubtotal(),
                'discount' => $this->cartService->getDiscount(),
                'shipping' => $this->cartService->getShippingFee(),
                'total' => $this->cartService->getTotal(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request, string $key)
    {
        $this->cartService->remove($key);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed.',
                'count' => $this->cartService->getCount(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $result = $this->cartService->applyCoupon($request->coupon_code);

        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function removeCoupon()
    {
        $this->cartService->removeCoupon();
        return redirect()->back()->with('success', 'Coupon removed.');
    }
}
