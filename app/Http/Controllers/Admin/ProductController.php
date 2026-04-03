<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Color;
use App\Models\Admin\Product;
use App\Models\Admin\Size;
use App\Models\Category;
use App\Services\ProductService;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use ResponseStatus;
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $products = Product::select(['id', 'name', 'slug', 'qty', 'price', 'status'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        $products->appends(['search' => $search]);

        return view('admin.product.index', compact('products', 'search'));
    }

    public function create()
    {
        $colors = Color::all();
        $sizes = Size::all();
        $categories = Category::where('status', Category::STATUS_ACTIVE )->get();


        return view('admin.product.add-edit', [
            'product' => null,
            'colors' => $colors,
            'sizes' => $sizes,
            'categories' => $categories
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $this->productService->store($request);
            return $this->success($data, 'Product created successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Failed to create new product: ' . $e->getMessage(), 500);
        }
    }

    public function show(Product $product)
    {
        $product->load(['colors', 'sizes']);

        $categories = Category::where('status', Category::STATUS_ACTIVE)->get();
        return view('admin.product.show', compact('product','categories'));
    }

    public function edit(Product $product)
    {
        $colors = Color::all();
        $sizes = Size::all();
        $product->load(['colors', 'sizes']);
        $categories = Category::where('status', Category::STATUS_ACTIVE )->get();

        return view('admin.product.add-edit', compact('product', 'colors', 'sizes','categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $this->productService->update($request, $product);
            return $this->success($data, 'Product updated successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error('Failed to update product: ' . $exception->getMessage(), 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->destroy($product);
            return $this->success(null, 'Product deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error('Failed to delete product', 500);
        }
    }
}
