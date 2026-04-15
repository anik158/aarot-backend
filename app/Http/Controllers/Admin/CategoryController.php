<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $categories = Category::select(['id', 'name', 'slug','description', 'image', 'status'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        $categories->appends(['search' => $search]);
        return view('admin.categories.index', compact('categories','search'));
    }

    public function create()
    {
        $attributes = \App\Models\Admin\Attribute::where('status', 1)->get();
        return view('admin.categories.add-edit', compact('attributes'));
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->store($request);
            // Handle AJAX or Redirect based on request if needed, 
            // but the current add-edit.blade uses AJAX for categoryForm
            return response()->json(['success' => true, 'message' => 'Category created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit(Category $category)
    {
        $category->load('attributes');
        $attributes = \App\Models\Admin\Attribute::where('status', 1)->get();
        return view('admin.categories.add-edit', compact('category', 'attributes'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->update($request, $category);
            return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function destroy(Category $category)
    {
        try {
            $this->categoryService->destroy($category);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete category.');
        }
    }

    public function getAttributes(Category $category)
    {
        return response()->json($category->attributes()->with('values')->get());
    }
}
