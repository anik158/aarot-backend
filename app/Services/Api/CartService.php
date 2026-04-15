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
    private function getCurrentUser()
    {
        return auth('api')->user();
    }

    private function getCartKey($guestToken = null)
    {
        $user = $this->getCurrentUser();
        if ($user) {
            return "cart:user:{$user->id}";
        }

        // Guest
        return "cart:guest:" . ($guestToken ?? Str::uuid()->toString());
    }


    public function addToCart($productId, $options = [], $qty = 1, $guestToken = null)
    {
        $product = Product::with(['attributeValues.attribute'])->findOrFail($productId);

        if ($product->status !== 1) {
            throw new \Exception("Product is not available.");
        }

        if ($product->qty < $qty) {
            throw new \Exception("Insufficient stock for {$product->name}. Only {$product->qty} left.");
        }

        // Sort options to ensure consistent matching
        ksort($options);
        $optionsJson = json_encode($options);

        $user = $this->getCurrentUser();
        if ($user) {
            // === Logged-in User → Database ===
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            $cartItem = CartItem::where([
                'cart_id'    => $cart->id,
                'product_id' => $productId,
                'options'    => $optionsJson,
            ])->first();

            if ($cartItem) {
                $cartItem->update([
                    'quantity'   => $qty,
                    'price'      => $product->price,
                    'title'      => $product->name,
                    'image'      => $product->first_image ? asset($product->first_image) : null,
                ]);
            } else {
                $cartItem = CartItem::create([
                    'cart_id'    => $cart->id,
                    'product_id' => $productId,
                    'options'    => $optionsJson,
                    'quantity'   => $qty,
                    'price'      => $product->price,
                    'title'      => $product->name,
                    'image'      => $product->first_image ? asset($product->first_image) : null,
                ]);
            }

            return $cartItem;
        } else {
            // === Guest User → Redis ===
            $key = $this->getCartKey($guestToken);
            $cartData = Redis::get($key);
            $cart = $cartData ? json_decode($cartData, true) : [];

            $itemKey = $productId . '_' . md5($optionsJson);

            if (isset($cart[$itemKey])) {
                $cart[$itemKey]['qty'] = $qty;
            } else {
                $cart[$itemKey] = [
                    'productId'  => $productId,
                    'options'    => $options,
                    'qty'        => $qty,
                    'price'      => $product->price,
                    'title'      => $product->name,
                    'image'      => $product->first_image ? asset($product->first_image) : null,
                ];
            }

            Redis::setex($key, 60 * 60 * 24, json_encode($cart));

            return $cart[$itemKey];
        }
    }


    public function getCart($guestToken = null)
    {
        $user = $this->getCurrentUser();
        if ($user) {
            $cart = Cart::with(['items.product'])
                ->where('user_id', $user->id)
                ->first();

            if (!$cart) return [];

            return $cart->items->map(function ($item) {
                return [
                    'productId' => $item->product_id,
                    'options'   => json_decode($item->options, true) ?? [],
                    'qty'       => $item->quantity,
                    'price'     => $item->price,
                    'title'     => $item->product ? $item->product->name : 'Unknown',
                    'image'     => ($item->product && $item->product->first_image) ? asset($item->product->first_image) : null,
                ];
            });
        } else {
            $key = $this->getCartKey($guestToken);
            $cartData = Redis::get($key);
            $cart = $cartData ? json_decode($cartData, true) : [];

            $formattedCart = array_values($cart);
            foreach ($formattedCart as &$item) {
                if (!empty($item['image']) && !str_starts_with($item['image'], 'http')) {
                    $item['image'] = asset($item['image']);
                }
            }
            return $formattedCart;
        }
    }


    public function clearCart($guestToken = null)
    {
        $user = $this->getCurrentUser();
        if ($user) {
            CartItem::whereHas('cart', fn($q) => $q->where('user_id', $user->id))->delete();
        } else {
            $key = $this->getCartKey($guestToken);
            Redis::del($key);
        }
    }

    public function updateQuantity($productId, $options = [], $qty = 1, $guestToken = null)
    {
        ksort($options);
        $optionsJson = json_encode($options);

        $user = $this->getCurrentUser();
        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart) return null;

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->where('options', $optionsJson)
                ->first();

            if ($cartItem) {
                if ($qty <= 0) {
                    $cartItem->delete();
                } else {
                    $cartItem->update(['quantity' => $qty]); // Set exact quantity
                }
            }
            return $cartItem;
        } else {
            // Guest - Redis
            $key = $this->getCartKey($guestToken);
            $cartData = Redis::get($key);
            $cart = $cartData ? json_decode($cartData, true) : [];

            $itemKey = $productId . '_' . md5($optionsJson);

            if (isset($cart[$itemKey])) {
                if ($qty <= 0) {
                    unset($cart[$itemKey]);
                } else {
                    $cart[$itemKey]['qty'] = $qty; // Set exact quantity
                }
            }

            Redis::setex($key, 60 * 60 * 24 * 7, json_encode($cart));
            return $cart[$itemKey] ?? null;
        }
    }


    public function removeItem($productId, $options = [], $guestToken = null)
    {
        return $this->updateQuantity($productId, $options, 0, $guestToken);
    }


    public function mergeGuestCart($guestToken)
    {
        $user = $this->getCurrentUser();
        if (!$user || !$guestToken) {
            return false;
        }

        $key = "cart:guest:{$guestToken}";
        $guestCartData = Redis::get($key);

        if (!$guestCartData) {
            return true; // no guest cart to merge
        }

        $guestCart = json_decode($guestCartData, true);

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart as $item) {
            $options = $item['options'] ?? [];
            ksort($options);
            $optionsJson = json_encode($options);

            CartItem::updateOrCreate(
                [
                    'cart_id'    => $cart->id,
                    'product_id' => $item['productId'],
                    'options'    => $optionsJson,
                ],
                [
                    'quantity'   => DB::raw("quantity + " . ($item['qty'] ?? 1)),
                    'price'      => $item['price'],
                    'title'      => $item['title'] ?? null,
                    'image'      => $item['image'] ?? null,
                ]
            );
        }

        Redis::del($key);

        return true;
    }
}
