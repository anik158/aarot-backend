<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $productId)
    {
        $user = $request->user();
        $favorite = \App\Models\Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'is_favorite' => false]);
        }

        \App\Models\Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);

        return response()->json(['success' => true, 'is_favorite' => true]);
    }

    public function checkStatus(Request $request, $productId)
    {
        $user = $request->user();
        $isFavorite = \App\Models\Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        return response()->json(['success' => true, 'is_favorite' => $isFavorite]);
    }
}
