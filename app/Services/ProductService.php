<?php

namespace App\Services;

use App\Models\Admin\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function store($request)
    {
        $data = $request->except(['attribute_values', 'first_image', 'second_image', 'third_image']);

        if ($request->hasFile('first_image')) {
            $data['first_image'] = $this->uploadImage($request->file('first_image'));
        }
        if ($request->hasFile('second_image')) {
            $data['second_image'] = $this->uploadImage($request->file('second_image'));
        }
        if ($request->hasFile('third_image')) {
            $data['third_image'] = $this->uploadImage($request->file('third_image'));
        }

        $product = Product::create($data);

        if ($request->has('attribute_values')) {
            $product->attributeValues()->sync($request->attribute_values);
        }

        return $product;
    }

    public function update($request, $product)
    {
        $data = $request->except(['attribute_values', 'first_image', 'second_image', 'third_image']);

        if ($request->hasFile('first_image')) {
            $this->deleteImage($product->first_image);
            $data['first_image'] = $this->uploadImage($request->file('first_image'));
        }
        if ($request->hasFile('second_image')) {
            $this->deleteImage($product->second_image);
            $data['second_image'] = $this->uploadImage($request->file('second_image'));
        }
        if ($request->hasFile('third_image')) {
            $this->deleteImage($product->third_image);
            $data['third_image'] = $this->uploadImage($request->file('third_image'));
        }

        $product->update($data);

        $product->attributeValues()->sync($request->attribute_values ?? []);

        return $product;
    }

    public function destroy($product)
    {
        $this->deleteImage($product->first_image);
        $this->deleteImage($product->second_image);
        $this->deleteImage($product->third_image);

        $product->attributeValues()->detach();

        $product->delete();
    }

    private function uploadImage($file)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/products'), $filename);
        return 'uploads/products/' . $filename;
    }

    private function deleteImage($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
