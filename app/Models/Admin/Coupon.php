<?php

namespace App\Models\Admin;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coupon extends Model
{
    protected $guarded = ['id'];


    public function setCodeAttribute(string $code): void {
        $this->attributes['code'] = Str::upper($code);
    }

    public function isValid(): bool
    {
        if ($this->is_active !== '1') return false;
        if ($this->used_count >= (int)$this->max_usage) return false;
        if ($this->expires_at && $this->expires_at < now()) return false;
        
        return true;
    }
}
