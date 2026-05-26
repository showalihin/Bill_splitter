<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Items in a bill session.
     *
     * Each item has a name, unit price, and quantity.
     * Items are assigned to participants via the pivot table.
     */
    public function up(): void
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bill_session_id')->constrained()->cascadeOnDelete();

            $table->string('name');                                   // item name
            $table->decimal('price', 10, 2);                          // unit price in BDT
            $table->integer('quantity')->default(1);                   // how many ordered
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
