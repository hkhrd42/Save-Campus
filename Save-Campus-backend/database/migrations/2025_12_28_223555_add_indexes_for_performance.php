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
        Schema::table('meals', function (Blueprint $table) {
            // Index for filtering meals by staff member
            $table->index('user_id');
            
            // Composite index for browsing active meals
            $table->index(['available_portions', 'expires_at']);
            
            // Index for sorting by creation date
            $table->index('created_at');
        });

        Schema::table('claims', function (Blueprint $table) {
            // Index for getting user's claims
            $table->index('user_id');
            
            // Index for getting meal's claims
            $table->index('meal_id');
            
            // Composite index to prevent duplicate claims (unique constraint)
            $table->unique(['user_id', 'meal_id']);
            
            // Index for sorting by creation date
            $table->index('created_at');
        });

        Schema::table('users', function (Blueprint $table) {
            // Index for role-based queries
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['available_portions', 'expires_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['meal_id']);
            $table->dropUnique(['user_id', 'meal_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
