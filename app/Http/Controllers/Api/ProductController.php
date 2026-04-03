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
            $products = Product::with(['colors', 'sizes'])
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
            $productQuery = Product::with([ 'reviews']);

            if($request->has('category') && $request->category !='')
            {
                $productQuery->whereHas('category', function($q) use($request){
                    $q->where('category_id', $request->category);
                });
            }


            if($request->has('color') && $request->color !='')
            {
                $productQuery->whereHas('colors', function($q) use($request){
                    $q->where('colors.id', $request->color);
                });
            }

            if($request->has('size') && $request->size !='')
            {
                $productQuery->whereHas('sizes', function($q) use($request){
                    $q->where('sizes.id', $request->size);
                });
            }

            if($request->has('search') && trim($request->search) != '')
            {
                $productQuery->where(function($q) use($request){
                    $q->where('products.name', 'like', '%'.$request->search.'%');
                } );
            }


            $products = $productQuery->orderBy('id', 'desc')->get();;
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
            $product = Product::with(['colors', 'sizes', 'reviews.users'])
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
