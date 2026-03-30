<?php

namespace App\Services\Api;

use App\Models\User\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentService
{
    public function createStripeSession(Order $order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session =  Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order #' . $order->order_number,
                    ],
                    'unit_amount' => (int)($order->total*100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('custom.frontend_url'). '/order-confirmation/' . $order->order_number . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('custom.frontend_url'). '/checkout',
            'metadata' => [
                'order_number' => $order->order_number,
            ],
        ]);

        return $session;
    }
}
