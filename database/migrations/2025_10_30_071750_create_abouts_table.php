<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('POJ Music Club');
            $table->year('founded_year')->nullable();
            $table->unsignedInteger('members_count')->default(0);
            $table->unsignedInteger('events_per_year')->default(0);
            $table->text('short_description')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->json('activities')->nullable(); // list of activities e.g. ["Live shows", "Workshops"]
            $table->string('hero_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};