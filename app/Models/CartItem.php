<?php

namespace App\Models;

use App\Models\Admin\Color;
use App\Models\Admin\Product;
use App\Models\Admin\Size;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'color_id', 'size_id',
        'quantity', 'price', 'title', 'image', 'color_name', 'size_name'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
