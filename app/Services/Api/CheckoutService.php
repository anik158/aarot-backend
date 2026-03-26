<?php

namespace App\Services\Api;

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
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['productId'],
                    'color_id'   => $item['colorId'] ?? null,
                    'size_id'    => $item['sizeId'] ?? null,
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                ]);
            }

            return $order;
        });
    }
}
