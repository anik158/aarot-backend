<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;
use App\Models\Admin\Size;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SizeController extends Controller
{
    use ResponseStatus;
    public function index(Request $request)
    {

        try
        {
            $sizes = Size::select(['id','name'])
                ->get();

            return $this->success(SizeResource::collection($sizes), 'Size fetched successfully', 200);
        }catch (\Exception $e){
            Log::error('Error in colors'.$e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }
}
