<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attribute;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use ResponseStatus;

    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');

        $query = Attribute::with('values');

        if ($categoryId) {
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        } else {
            // If no category, show attributes that have active products (Amazon style)
            $query->whereHas('values.products', function($p) {
                $p->where('status', 1);
            });
        }

        $attributes = $query->get()->map(function($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'slug' => $attr->slug,
                'values' => $attr->values->map(function($val) {
                    return [
                        'id' => $val->id,
                        'name' => $val->value // CustomSelect expects 'name'
                    ];
                })->values()
            ];
        });

        return $this->success($attributes);
    }
}
