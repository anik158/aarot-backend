<?php

namespace App\Services\Api;

use App\Models\Admin\Product;
use App\Models\User\Order;
use App\Models\User\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutService
{

    public function processCheckout($user, array $validatedData) {
        return DB::transaction(function () use ($user, $validatedData) {

            $cartItems = $validatedData['cart_items'];
            $paymentMethod = $validatedData['payment_method'];
            $subTotal = 0;

            foreach ($cartItems as $cartItem) {
                $product = Product::findOrFail($cartItem['productId']);

                if ($product->status === Product::STATUS_INACTIVE) {
                    throw new \Exception("Product '{$product->name}' is not available for purchase.");
                }

                if ($product->qty < $cartItem['qty']) {
                    throw new \Exception(
                        "Insufficient stock for '{$product->name}'. Only {$product->qty} items available."
                    );
                }
                $subTotal += $cartItem['price'] * $cartItem['qty'];
            }

            $discountAmount = 0;
            $couponId = $validatedData['coupon_id'] ?? null;

            if ($couponId) {
                $coupon = \App\Models\Admin\Coupon::find($couponId);
                if ($coupon && $coupon->isValid()) {
                    if ($coupon->type === 'fixed') {
                        $discountAmount = (float)$coupon->value;
                    } else {
                        $discountAmount = $subTotal * ($coupon->value / 100);
                    }

                    // Increment the usage count
                    $coupon->increment('used_count');
                }
            }

            $total = ($subTotal - $discountAmount);
            if ($total < 0) $total = 0;

            $order = Order::create([
                'user_id'          => $user->id,
                'subtotal'         => $subTotal,
                'shipping_cost'    => 0,
                'coupon_id'        => $couponId,
                'discount_amount'  => $discountAmount,
                'total'            => $total,
                'status'           => 'pending',
                'payment_status'   => $paymentMethod === 'cod' ? 'pending' : 'unpaid',
                'payment_method'   => $paymentMethod,
                'shipping_name'    => $validatedData['name'],
                'shipping_phone'   => $validatedData['phone'],
                'shipping_address' => $validatedData['address'],
                'shipping_city'    => $validatedData['city'],
            ]);

            foreach ($cartItems as $item) {
                $product = Product::find($item['productId']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['productId'],
                    'color_id'   => $item['colorId'] ?? null,
                    'size_id'    => $item['sizeId'] ?? null,
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                ]);

                $product->decrement('qty', $item['qty']);

                $product->refresh();

                if ($product->qty <= 0) {
                    $product->status = Product::STATUS_INACTIVE;
                    $product->save();
                }
            }

            return $order;
        });
    }
}
