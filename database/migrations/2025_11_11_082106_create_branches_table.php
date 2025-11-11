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
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Branch name
            $table->string('address'); // Branch address
            $table->string('phone')->nullable(); // Phone number (optional)
            $table->string('map_url')->nullable(); // Optional Google Maps iframe URL
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
