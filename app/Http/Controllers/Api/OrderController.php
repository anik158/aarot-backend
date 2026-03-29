<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User\Order;
use App\Traits\ResponseStatus;

class OrderController extends Controller
{
    use ResponseStatus;

    public function showByOrderNumber($orderNumber)
    {
        $order = Order::with(['items.product', 'items.color', 'items.size'])
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return $this->error('Order not found', 404);
        }

        // Security check
        if ($order->user_id !== auth('api')->id()) {
            return $this->error('Unauthorized', 403);
        }

        // Transform image paths to full URLs
        $order->items->each(function ($item) {
            if ($item->image) {
                $item->image = asset($item->image);
            } elseif ($item->product && $item->product->first_image) {
                $item->image = asset($item->product->first_image);
            } else {
                $item->image = null;
            }

            // Also make sure title is available
            if (empty($item->title) && $item->product) {
                $item->title = $item->product->name;
            }
        });

        return $this->success($order, 'Order retrieved successfully');
    }
}
