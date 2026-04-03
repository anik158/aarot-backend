<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {

        try
        {
            $categories = Category::select(['id','name','slug'])
                ->where('status', Category::STATUS_ACTIVE)
                ->get();

            return $this->success(CategoryResource::collection($categories), 'Categories fetched successfully', 200);
        }catch (\Exception $e){
            Log::error('Error in categories'.$e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }
}
