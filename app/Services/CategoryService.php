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

        return Category::create($data);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->only(['name', 'slug', 'description', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);
        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }
}
