<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A bill splitting session.
     *
     * Created by a logged-in user, optionally linked to a restaurant.
     * Contains participants, items, and tax/service charge settings.
     */
    public function up(): void
    {
        Schema::create('bill_sessions', function (Blueprint $table) {
            $table->id();

            // Who created this session
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Optional link to a restaurant (for pulling menu items)
            $table->foreignId('restaurant_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');                                      // e.g. "Dinner at Kacchi Bhai"
            $table->decimal('vat_percentage', 5, 2)->default(0);          // e.g. 7.50
            $table->decimal('service_charge_percentage', 5, 2)->default(0); // e.g. 10.00
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_sessions');
    }
};
