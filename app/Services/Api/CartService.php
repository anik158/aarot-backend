<?php

namespace App\Services\Api;

use App\Models\Admin\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class CartService
{
    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }


    private function getCartKey($guestToken = null)
    {
        if ($this->user) {
            return "cart:user:{$this->user->id}";
        }

        // Guest
        return "cart:guest:" . ($guestToken ?? Str::uuid()->toString());
    }


    public function addToCart($productId, $colorId = null, $sizeId = null, $qty = 1, $guestToken = null)
    {
        $product = Product::with(['colors', 'sizes'])->findOrFail($productId);

        if ($product->status !== 1) {
            throw new \Exception("Product is not available.");
        }

        if ($product->qty < $qty) {
            throw new \Exception("Insufficient stock for {$product->name}. Only {$product->qty} left.");
        }

        $colorName = null;
        $sizeName = null;

        if ($colorId) {
            $color = $product->colors->firstWhere('id', $colorId);
            $colorName = $color ? $color->name : null;
        }

        if ($sizeId) {
            $size = $product->sizes->firstWhere('id', $sizeId);
            $sizeName = $size ? $size->name : null;
        }

        if ($this->user) {
            // === Logged-in User → Database ===
            $cart = Cart::firstOrCreate(['user_id' => $this->user->id]);

            $cartItem = CartItem::updateOrCreate(
                [
                    'cart_id'    => $cart->id,
                    'product_id' => $productId,
                    'color_id'   => $colorId,
                    'size_id'    => $sizeId,
                ],
                [
                    'quantity'   => DB::raw("quantity + {$qty}"),
                    'price'      => $product->price,
                    // Store rich data for display
                    'title'      => $product->name,
                    'image'      => $product->first_image,
                    'color_name' => $colorName,
                    'size_name'  => $sizeName,
                ]
            );

            return $cartItem;
        } else {
            // === Guest User → Redis ===
            $key = $this->getCartKey($guestToken);
            $cartData = Redis::get($key);
            $cart = $cartData ? json_decode($cartData, true) : [];

            $itemKey = "{$productId}_{$colorId}_{$sizeId}";

            if (isset($cart[$itemKey])) {
                $cart[$itemKey]['qty'] += $qty;
            } else {
                $cart[$itemKey] = [
                    'productId'  => $productId,
                    'colorId'    => $colorId,
                    'sizeId'     => $sizeId,
                    'qty'        => $qty,
                    'price'      => $product->price,
                    'title'      => $product->name,
                    'image'      => $product->first_image,
                    'colorName'  => $colorName,
                    'sizeName'   => $sizeName,
                ];
            }

            Redis::setex($key, 60 * 60 * 24, json_encode($cart));

            return $cart[$itemKey];
        }
    }


    public function getCart($guestToken = null)
    {
        if ($this->user) {
            $cart = Cart::with(['items.product', 'items.color', 'items.size'])
                ->where('user_id', $this->user->id)
                ->first();

            return $cart ? $cart->items : [];
        } else {
            $key = $this->getCartKey($guestToken);
            $cartData = Redis::get($key);
            return $cartData ? json_decode($cartData, true) : [];
        }
    }


    public function clearCart($guestToken = null)
    {
        if ($this->user) {
            CartItem::whereHas('cart', fn($q) => $q->where('user_id', $this->user->id))->delete();
        } else {
            $key = $this->getCartKey($guestToken);
            Redis::del($key);
        }
    }

    public function updateQuantity($productId, $colorId = null, $sizeId = null, $qty = 1, $guestToken = null)
    {
        if ($this->user) {
            $cart = Cart::where('user_id', $this->user->id)->first();
            if (!$cart) return null;

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->where('color_id', $colorId)
                ->where('size_id', $sizeId)
                ->first();

            if ($cartItem) {
                if ($qty <= 0) {
                    $cartItem->delete();
                } else {
                    $cartItem->update(['quantity' => $qty]);
                }
            }
            return $cartItem;
        } else {
            // Guest - Redis
            $key = $this->getCartKey($guestToken);
            $cartData = Redis::get($key);
            $cart = $cartData ? json_decode($cartData, true) : [];

            $itemKey = "{$productId}_{$colorId}_{$sizeId}";

            if (isset($cart[$itemKey])) {
                if ($qty <= 0) {
                    unset($cart[$itemKey]);
                } else {
                    $cart[$itemKey]['qty'] = $qty;
                }
            }

            Redis::setex($key, 60 * 60 * 24 * 7, json_encode($cart));
            return $cart[$itemKey] ?? null;
        }
    }


    public function removeItem($productId, $colorId = null, $sizeId = null, $guestToken = null)
    {
        return $this->updateQuantity($productId, $colorId, $sizeId, 0, $guestToken);
    }
}
