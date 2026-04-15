<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('attribute') ? $this->route('attribute')->id : null;

        return [
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'values' => 'nullable|array',
            'values.*' => 'required|string|max:255',
        ];
    }
}
