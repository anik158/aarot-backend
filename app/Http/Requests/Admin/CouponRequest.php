<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:2|max:255|unique:coupons,code' . ($this->coupon ? ',' . $this->coupon->id : ''),
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0.01',
            'max_usage' => 'required|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'required|in:1,0',
        ];
    }
}
