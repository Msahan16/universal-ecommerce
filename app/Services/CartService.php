<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'shopping_cart';

    public function getCart(): array
    {
        return Session::get($this->sessionKey, [
            'items' => [],
            'coupon' => null,
        ]);
    }

    public function saveCart(array $cart): void
    {
        Session::put($this->sessionKey, $cart);
    }

    public function add(int $productId, ?int $variantId = null, int $quantity = 1, array $customAttributes = []): array
    {
        $product = Product::with(['variants', 'category', 'brand'])->findOrFail($productId);
        $variant = $variantId ? ProductVariant::find($variantId) : null;

        $cart = $this->getCart();
        $itemKey = $productId . ($variantId ? '_' . $variantId : '');

        $unitPrice = $variant ? (float) $variant->price : (float) $product->price;
        $name = $product->name;
        $variantName = $variant ? $variant->name : null;
        $sku = $variant ? $variant->sku : $product->sku;
        $thumbnail = $variant && $variant->image ? $variant->image : $product->thumbnail_url;
        $maxStock = $variant ? $variant->stock : $product->stock;

        if (isset($cart['items'][$itemKey])) {
            $newQuantity = $cart['items'][$itemKey]['quantity'] + $quantity;
            if ($maxStock > 0 && $newQuantity > $maxStock) {
                $newQuantity = $maxStock;
            }
            $cart['items'][$itemKey]['quantity'] = $newQuantity;
            $cart['items'][$itemKey]['total'] = $newQuantity * $unitPrice;
        } else {
            $finalQty = ($maxStock > 0 && $quantity > $maxStock) ? $maxStock : $quantity;
            $cart['items'][$itemKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'name' => $name,
                'variant_name' => $variantName,
                'sku' => $sku,
                'price' => $unitPrice,
                'quantity' => $finalQty,
                'thumbnail' => $thumbnail,
                'max_stock' => $maxStock,
                'attributes' => $variant ? $variant->attributes_json : $customAttributes,
                'total' => $finalQty * $unitPrice,
            ];
        }

        $this->saveCart($cart);
        return $cart;
    }

    public function update(string $itemKey, int $quantity): array
    {
        $cart = $this->getCart();

        if (isset($cart['items'][$itemKey])) {
            if ($quantity <= 0) {
                unset($cart['items'][$itemKey]);
            } else {
                $maxStock = $cart['items'][$itemKey]['max_stock'] ?? 999;
                if ($maxStock > 0 && $quantity > $maxStock) {
                    $quantity = $maxStock;
                }
                $cart['items'][$itemKey]['quantity'] = $quantity;
                $cart['items'][$itemKey]['total'] = $quantity * $cart['items'][$itemKey]['price'];
            }
            $this->saveCart($cart);
        }

        return $cart;
    }

    public function remove(string $itemKey): array
    {
        $cart = $this->getCart();
        if (isset($cart['items'][$itemKey])) {
            unset($cart['items'][$itemKey]);
            $this->saveCart($cart);
        }
        return $cart;
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    public function getItems(): array
    {
        return $this->getCart()['items'] ?? [];
    }

    public function getCount(): int
    {
        $items = $this->getItems();
        return array_sum(array_column($items, 'quantity'));
    }

    public function getSubtotal(): float
    {
        $items = $this->getItems();
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        return (float) $subtotal;
    }

    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();
        $subtotal = $this->getSubtotal();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        if (!$coupon->isValid($subtotal)) {
            if ($coupon->min_spend > $subtotal) {
                return [
                    'success' => false,
                    'message' => 'Minimum spend of ' . SiteSetting::get('currency_symbol', 'Rs. ') . number_format($coupon->min_spend, 2) . ' is required for this coupon.'
                ];
            }
            return ['success' => false, 'message' => 'This coupon is expired or inactive.'];
        }

        $cart = $this->getCart();
        $cart['coupon'] = [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'max_discount' => $coupon->max_discount,
        ];
        $this->saveCart($cart);

        return ['success' => true, 'message' => 'Coupon ' . $coupon->code . ' applied successfully!'];
    }

    public function removeCoupon(): void
    {
        $cart = $this->getCart();
        $cart['coupon'] = null;
        $this->saveCart($cart);
    }

    public function getCoupon(): ?array
    {
        return $this->getCart()['coupon'] ?? null;
    }

    public function getDiscount(): float
    {
        $couponData = $this->getCoupon();
        if (!$couponData) {
            return 0.00;
        }

        $subtotal = $this->getSubtotal();
        if ($couponData['type'] === 'percentage') {
            $discount = ($subtotal * $couponData['value']) / 100;
            if (!empty($couponData['max_discount']) && $discount > $couponData['max_discount']) {
                return (float) $couponData['max_discount'];
            }
            return (float) $discount;
        }

        return (float) min($couponData['value'], $subtotal);
    }

    public function getShippingFee(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.00;
        }

        $freeThreshold = (float) SiteSetting::get('free_shipping_threshold', 15000);
        if ($freeThreshold > 0 && $subtotal >= $freeThreshold) {
            return 0.00;
        }

        return (float) SiteSetting::get('flat_shipping_rate', 450);
    }

    public function getTotal(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.00;
        }
        $discount = $this->getDiscount();
        $shipping = $this->getShippingFee();
        return max(0, ($subtotal - $discount) + $shipping);
    }
}
