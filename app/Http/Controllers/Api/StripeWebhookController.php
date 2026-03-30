<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        if (!$endpoint_secret) {
            Log::error('Stripe webhook secret not configured');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $orderNumber = $session->metadata->order_number ?? null;

                if ($orderNumber) {
                    $order = Order::where('order_number', $orderNumber)->first();

                    if ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed',
                        ]);

                        Log::info("Order {$orderNumber} marked as paid via Stripe webhook");
                    }
                }
                break;

            default:
                Log::info("Unhandled Stripe event: " . $event->type);
        }

        return response()->json(['status' => 'success']);
    }
}
