<?php

namespace Database\Seeders;

use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Create generic attributes
        $colorAttr = Attribute::firstOrCreate(['slug' => 'color'], ['name' => 'Color']);
        $sizeAttr = Attribute::firstOrCreate(['slug' => 'size'], ['name' => 'Size']);
        $storageAttr = Attribute::firstOrCreate(['slug' => 'storage'], ['name' => 'Storage']);

        // Migrate Old Colors
        $oldColors = DB::table('colors')->get();
        foreach ($oldColors as $oldColor) {
            $val = AttributeValue::firstOrCreate([
                'attribute_id' => $colorAttr->id,
                'value' => $oldColor->name
            ]);
            
            $productIds = DB::table('color_product')->where('color_id', $oldColor->id)->pluck('product_id');
            foreach ($productIds as $pid) {
                DB::table('attribute_value_product')->insertOrIgnore([
                    'product_id' => $pid,
                    'attribute_value_id' => $val->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Migrate Old Sizes
        $oldSizes = DB::table('sizes')->get();
        foreach ($oldSizes as $oldSize) {
            $val = AttributeValue::firstOrCreate([
                'attribute_id' => $sizeAttr->id,
                'value' => $oldSize->name
            ]);

            $productIds = DB::table('size_product')->where('size_id', $oldSize->id)->pluck('product_id');
            foreach ($productIds as $pid) {
                DB::table('attribute_value_product')->insertOrIgnore([
                    'product_id' => $pid,
                    'attribute_value_id' => $val->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Demo data for Electronics
        AttributeValue::firstOrCreate(['attribute_id' => $storageAttr->id, 'value' => '128GB']);
        AttributeValue::firstOrCreate(['attribute_id' => $storageAttr->id, 'value' => '256GB']);
        AttributeValue::firstOrCreate(['attribute_id' => $storageAttr->id, 'value' => '512GB']);
        AttributeValue::firstOrCreate(['attribute_id' => $storageAttr->id, 'value' => '1TB']);
    }
}
