<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User\Order;
use App\Services\Api\PaymentService;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ResponseStatus;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function createStripeSession(Request $request)
    {
        $order = Order::where('order_number', $request->order_number)
            ->where('user_id', auth('api')->id())
            ->first();

        if (!$order) {
            return $this->error('Order not found', 404);
        }

        if ($order->payment_status === 'paid') {
            return $this->error('Order already paid', 400);
        }

        try {
            $session = $this->paymentService->createStripeSession($order);

            return $this->success([
                'session_id' => $session->id,
                'url' => $session->url,
            ], 'Stripe session created successfully');

        } catch (\Exception $e) {
            \Log::error('Stripe Session Error: ' . $e->getMessage());
            return $this->error('Failed to create payment session', 500);
        }
    }
}
