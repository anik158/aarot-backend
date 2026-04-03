<?php

namespace App\Models\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected  $guarded = ['id'];

    const APPROVED = 1;
    const REJECTED = 2;


    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->diffForHumans();
    }
}
