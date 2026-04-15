<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\CartService;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ResponseStatus;

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add(Request $request)
    {
        try {
            $guestToken = $request->header('X-Guest-Token');

            $cartItem = $this->cartService->addToCart(
                $request->product_id,
                $request->options ?? [],
                $request->qty ?? 1,
                $guestToken
            );

            return $this->success($cartItem, 'Item added to cart successfully');
        } catch (\Exception $e) {
            \Log::error('Cart Add Error: ' . $e->getMessage());
            return $this->error("Something went wrong", 422);
        }
    }

    public function index(Request $request)
    {
        $guestToken = $request->header('X-Guest-Token');
        $cartItems = $this->cartService->getCart($guestToken);

        return $this->success($cartItems, 'Cart retrieved successfully');
    }

    public function clear(Request $request)
    {
        $guestToken = $request->header('X-Guest-Token');
        $this->cartService->clearCart($guestToken);

        return $this->success(null, 'Cart cleared successfully');
    }

    public function updateQuantity(Request $request)
    {
        try {
            $guestToken = $request->header('X-Guest-Token');

            $this->cartService->updateQuantity(
                $request->product_id,
                $request->options ?? [],
                $request->qty ?? 1,
                $guestToken
            );

            return $this->success(null, 'Quantity updated successfully');
        } catch (\Exception $e) {
            \Log::error('Cart Update Error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 422);
        }
    }

    public function remove(Request $request)
    {
        try {
            $guestToken = $request->header('X-Guest-Token');

            $this->cartService->removeItem(
                $request->product_id,
                $request->options ?? [],
                $guestToken
            );

            return $this->success(null, 'Item removed from cart');
        } catch (\Exception $e) {
            \Log::error('Cart Remove Error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 422);
        }
    }

    public function merge(Request $request)
    {
        try {
            $guestToken = $request->header('X-Guest-Token');

            $this->cartService->mergeGuestCart($guestToken);

            return $this->success(null, 'Cart merged successfully');
        } catch (\Exception $e) {
            \Log::error('Cart Merge Error: ' . $e->getMessage());
            return $this->error('Failed to merge cart', 422);
        }
    }
}
