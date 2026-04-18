<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('color_product');
        Schema::dropIfExists('size_product');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('sizes');
    }

    public function down(): void
    {
        // No rollback for purge migration once data is migrated to Attributes
    }
};
