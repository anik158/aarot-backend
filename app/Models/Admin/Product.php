<?php

namespace App\Models\Admin;

use App\Models\Category;
use App\Models\User\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public function attributeValues(): belongsToMany {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
    }

    public function coupons(): belongsToMany {
        return $this->belongsToMany(Coupon::class);
    }

    public function orders(): belongsToMany {
        return $this->belongsToMany(Order::class);
    }

    public function reviews(): hasMany {
        return $this->hasMany(Review::class)->with('user')->where('approved', Review::APPROVED)->latest();
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
