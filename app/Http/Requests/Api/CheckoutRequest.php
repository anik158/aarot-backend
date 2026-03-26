<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        \Log::info($this->all());
        return [
            'name'                  => 'required|string|min:3|max:255',
            'phone'                 => 'required|string|max:20',
            'address'               => 'required|string|min:10',
            'city'                  => 'required|string|max:100',

            'cart_items'            => 'required|array|min:1',
            'cart_items.*.productId'=> 'required|integer|exists:products,id',
            'cart_items.*.colorId'  => 'nullable|integer|exists:colors,id',
            'cart_items.*.sizeId'   => 'nullable|integer|exists:sizes,id',
            'cart_items.*.qty'      => 'required|integer|min:1',
            'cart_items.*.price'    => 'required|numeric|min:0',
        ];
    }


    public function messages(): array
    {
        return [
            'cart_items.required' => 'Your cart cannot be empty.',
            'cart_items.min'      => 'Your cart must have at least one item.',
        ];
    }
}
