<?php

namespace App\Models;

use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
