<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use App\Models\Admin\Color;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ColorController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {

        try
        {
            $query = Color::select(['id', 'name']);

            if ($request->has('category_id') && $request->category_id != '') {
                $query->whereHas('products', function($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            $color = $query->get();

            return $this->success(ColorResource::collection($color), 'Color fetched successfully', 200);
        }catch (\Exception $e){
            Log::error('Error in colors'.$e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }
}
