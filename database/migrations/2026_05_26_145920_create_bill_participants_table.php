<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Participants in a bill session.
     *
     * These are the people splitting the bill (names only — they
     * don't need to be registered users).
     * amount_paid tracks how much each person has already paid
     * so we can calculate who owes/gets back money.
     */
    public function up(): void
    {
        Schema::create('bill_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bill_session_id')->constrained()->cascadeOnDelete();

            $table->string('name');                                    // participant name
            $table->decimal('amount_paid', 10, 2)->default(0);        // how much they already paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_participants');
    }
};
