<?php

namespace App\Models\Admin;

use App\Models\User\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = ['id'];

    public function colors(): belongsToMany {
        return $this->belongsToMany(Color::class);
    }

    public function sizes(): belongsToMany {
        return $this->belongsToMany(Size::class, 'size_product');
    }

    public function coupons(): belongsToMany {
        return $this->belongsToMany(Coupon::class);
    }

    public function orders(): belongsToMany {
        return $this->belongsToMany(Order::class);
    }

    public function reviews(): hasMany {
        return $this->hasMany(Review::class)->with('users')->where('approved', 1)->latest();
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}
