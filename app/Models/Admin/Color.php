<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    protected $guarded = ['id'];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
