<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'      => 'required|in:0,1',
        ];

        if ($this->isMethod('POST')) {
            $rules['slug'] = 'required|string|max:255|unique:categories,slug';
        } else {
            $rules['slug'] = 'required|string|max:255|unique:categories,slug,' . $this->category->id;
        }

        return $rules;
    }
}
