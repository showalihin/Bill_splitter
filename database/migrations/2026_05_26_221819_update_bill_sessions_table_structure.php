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
        Schema::table('bill_sessions', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->decimal('service_charge_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->dropColumn('status');
        });

        Schema::table('bill_sessions', function (Blueprint $table) {
            $table->string('status')->default('open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('bill_sessions', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->dropColumn('service_charge_amount');
            $table->dropColumn('discount_amount');
            $table->dropColumn('grand_total');
            $table->enum('status', ['draft', 'finalized'])->default('draft');
        });
    }
};
