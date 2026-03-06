<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->default('pcs');
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->string('classification')->default('splv'); // ppe, sphv, splv
            $table->string('category')->nullable(); // e.g. Office Equipment, IT Equipment, Furniture, etc.
            $table->string('estimated_useful_life')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('category');
            $table->index('classification');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
