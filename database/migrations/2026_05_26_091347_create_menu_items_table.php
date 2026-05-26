<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menu items belong to a restaurant.
     * They are soft-deletable so bill history keeps its item names intact.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('category')->nullable();     // e.g. "Appetizer", "Main Course"
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);            // BDT price
            $table->string('unit')->nullable();         // e.g. "plate", "glass", "piece"
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();                      // preserve history in bills
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
