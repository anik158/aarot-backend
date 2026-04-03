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
        return view('admin.categories.add-edit');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->store($request);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create category: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.add-edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->update($request, $category);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update category: ' . $e->getMessage())
                ->withInput();
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
}
