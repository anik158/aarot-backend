<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Color;
use App\Models\Admin\Product;
use App\Models\Admin\Size;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    use ResponseStatus;

    public function featured(Request $request)
    {
        try {
            $limit = $request->get('limit', 3);
            $products = Product::with(['attributeValues.attribute'])
                ->where('status', Product::STATUS_ACTIVE)
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();

            return $this->success(ProductResource::collection($products));
        } catch (\Exception $e) {
            Log::error('Error in featured products'.$e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }


    public function index(Request $request)
    {
        try{
            $productQuery = Product::with([ 'reviews', 'attributeValues.attribute']);

            if($request->has('category') && $request->category !='')
            {
                $productQuery->where('category_id', $request->category);
            }

            if ($request->has('options') && is_array($request->options)) {
                $options = $request->options;
                foreach ($options as $optionId) {
                    if ($optionId) {
                        $productQuery->whereHas('attributeValues', function($q) use($optionId) {
                            $q->where('attribute_values.id', $optionId);
                        });
                    }
                }
            }

            if($request->has('search') && trim($request->search) != '')
            {
                $productQuery->where('name', 'like', '%'.$request->search.'%');
            }

            $products = $productQuery->orderBy('id', 'desc')->get();
            $productCollection = ProductResource::collection($products);
            return $this->success($productCollection, 'Products fetched successfully', 200);
        }catch (\Exception $e){
            Log::error('Error in featured products'.$e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }


    public function show($id)
    {
        try{
            $product = Product::with(['attributeValues.attribute', 'reviews.user'])
                ->where('id', $id)
                ->firstOrFail();
            if($product)
            {
                return new ProductResource($product);
            }else{
                return response()->json(["message" => "Product not found"], 404);
            }
        }catch (\Exception $exception){
            return response()->json(["error"=>true, "message"=>$exception->getMessage()], 500);
        }
    }
}
