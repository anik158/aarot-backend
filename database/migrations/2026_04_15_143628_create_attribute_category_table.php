<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_category', function (Blueprint $row) {
            $row->id();
            $row->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $row->foreignId('category_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_category');
    }
};
