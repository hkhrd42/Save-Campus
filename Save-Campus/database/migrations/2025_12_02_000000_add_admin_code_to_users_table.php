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
        // Create admin_codes table to store valid staff registration codes
        Schema::create('admin_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('is_used')->default(false);
            $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Insert default admin codes
        DB::table('admin_codes')->insert([
            ['code' => 'STAFF2025', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ADMIN2025', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CAMPUS2025', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_codes');
    }
};
