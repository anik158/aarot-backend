<?php

namespace App\Models\User;

use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'options',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'options' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
