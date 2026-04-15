<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
use App\Models\Admin\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->orderBy('id', 'desc')->paginate(10);
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.add-edit');
    }

    public function store(AttributeRequest $request)
    {
        $attribute = Attribute::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->has('values') && is_array($request->values)) {
            foreach ($request->values as $value) {
                if (trim($value) !== '') {
                    $attribute->values()->create(['value' => $value]);
                }
            }
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute and its values created successfully.');
    }

    public function edit(Attribute $attribute)
    {
        $attribute->load('values');
        return view('admin.attributes.add-edit', compact('attribute'));
    }

    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        if ($request->has('values') && is_array($request->values)) {
            // Get existing IDs if passed implicitly, or just recreate them.
            // A common pattern is to just delete and recreate, but we don't want to break foreign keys in pivot table.
            
            // Re-sync values by keeping existing or adding new ones. 
            // In the form, values will be an array of strings. We should check which ones exist.
            $existingValues = $attribute->values()->pluck('value', 'id')->toArray();
            
            $newValues = [];
            foreach ($request->values as $value) {
                if (trim($value) !== '') {
                    $newValues[] = $value;
                    $existingKey = array_search($value, $existingValues);
                    if ($existingKey === false) {
                        $attribute->values()->create(['value' => $value]);
                    } else {
                        // Value already exists, remove it from existingValues array so we know what to delete
                        unset($existingValues[$existingKey]);
                    }
                }
            }

            // Any values left in $existingValues were removed from the input
            foreach ($existingValues as $id => $val) {
                $attribute->values()->where('id', $id)->delete();
            }
        } else {
            // Delete all values
            $attribute->values()->delete();
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
    }
}
