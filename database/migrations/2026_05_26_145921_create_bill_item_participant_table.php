<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table: assigns items to participants.
     *
     * One item can be shared by multiple participants.
     * One participant can have multiple items.
     * The cost is split equally among all assigned participants for that item.
     */
    public function up(): void
    {
        Schema::create('bill_item_participant', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bill_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bill_participant_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['bill_item_id', 'bill_participant_id'], 'item_participant_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_item_participant');
    }
};
