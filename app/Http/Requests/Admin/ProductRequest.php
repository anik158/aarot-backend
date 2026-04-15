<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = [
            'name'        => 'required|string|max:255',
            'qty'         => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1',
            'attribute_values'   => 'nullable|array',
            'attribute_values.*' => 'exists:attribute_values,id',
            'category_id' => 'nullable|exists:categories,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['slug'] = 'required|string|max:255|unique:products,slug';

            $rules['first_image']  = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=800,ratio=1/1';
            $rules['second_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=800,ratio=1/1';
            $rules['third_image']  = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=800,ratio=1/1';
        } else {
            $rules['slug'] = 'required|string|max:255|unique:products,slug,' . $this->product->id;

            $rules['first_image']  = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=800,ratio=1/1';
            $rules['second_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=800,ratio=1/1';
            $rules['third_image']  = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=800,ratio=1/1';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'first_image.dimensions'  => 'First image must be square (same width and height) and at least 800×800 pixels.',
            'second_image.dimensions' => 'Second image must be square and at least 800×800 pixels.',
            'third_image.dimensions'  => 'Third image must be square and at least 800×800 pixels.',
        ];
    }
}
