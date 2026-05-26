<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Restaurants can be:
     *   - 'global'  : visible to all users, created/approved by admin
     *   - 'private' : visible only to the owning user
     *
     * status field tracks the "make global" request workflow:
     *   - 'private'  : default, only owner sees it
     *   - 'pending'  : user requested to make it global, awaiting admin review
     *   - 'approved' : admin approved → scope becomes 'global'
     *   - 'rejected' : admin rejected the request
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();

            // Owner (null means it was created directly by admin as global)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('cuisine')->nullable();          // e.g. "Bengali", "Italian"
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();            // path to uploaded logo/photo
            $table->text('description')->nullable();

            // 'global' = visible to all | 'private' = owner only
            $table->enum('scope', ['global', 'private'])->default('private');

            // Request-to-publish workflow
            $table->enum('status', ['private', 'pending', 'approved', 'rejected'])->default('private');
            $table->text('rejection_reason')->nullable();   // admin note when rejecting

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
