<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function store(Request $request)
    {
        $data = $request->only(['name', 'slug', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($data);
        $category->attributes()->sync($request->input('attributes', []));
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->only(['name', 'slug', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);
        $category->attributes()->sync($request->input('attributes', []));
        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }
}
