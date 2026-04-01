<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {

        Log::info('Stripe webhook received - Raw payload: ' . $request->getContent());
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        Log::info('Webhook signature header: ' . $sig_header);
        Log::info('Webhook secret used: ' . ($endpoint_secret ? 'SET' : 'MISSING'));
        if (!$endpoint_secret) {
            Log::error('Stripe webhook secret is not configured in .env');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            Log::info('Stripe webhook received: ' . $event->type);
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderNumber = $session->metadata->order_number ?? null;

            if ($orderNumber) {
                $order = Order::where('order_number', $orderNumber)->first();

                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                    ]);

                    Log::info("Order {$orderNumber} successfully marked as PAID via webhook");
                } else {
                    Log::warning("Order not found for number: " . $orderNumber);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
