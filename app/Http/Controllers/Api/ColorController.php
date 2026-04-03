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
            $color = Color::select(['id','name'])
                ->get();

            return $this->success(ColorResource::collection($color), 'Color fetched successfully', 200);
        }catch (\Exception $e){
            Log::error('Error in colors'.$e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }
}
