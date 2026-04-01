<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Services\Api\CheckoutService;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    use ResponseStatus;
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function store(CheckoutRequest $request)
    {
        \Log::info('Checkout');
        try {
            $order = $this->checkoutService->processCheckout(
                $request->user(),
                $request->validated()
            );

            $data = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total
            ];

            return $this->success($data,'Order created successfully', 201);

        } catch (\Exception $e) {
            \Log::error($e->getMessage(), [
                'line' => $e->getLine(),
            ]);
            return $this->error('Something went error', 500);
        }
    }
}
