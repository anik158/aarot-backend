<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $fillable = ['name', 'slug', 'status'];
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'attribute_category');
    }
}
