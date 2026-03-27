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

            $order = Order::create([
                'user_id'          => $user->id,
                'subtotal'         => $subTotal,
                'shipping_cost'    => 0,
                'discount_amount'  => 0,
                'total'            => $subTotal,
                'status'           => 'pending',
                'payment_status'   => 'pending',
                'payment_method'   => 'cash_on_delivery',
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
