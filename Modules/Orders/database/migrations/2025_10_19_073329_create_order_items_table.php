<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            
            // Foreign keys automatically create indexes
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            
            $t->unsignedInteger('qty')->default(1);
            $t->decimal('unit_price', 12, 2);
            $t->decimal('line_total', 12, 2);
            $t->timestamp('access_starts_at')->nullable();
            $t->timestamp('access_expires_at')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
            
            // ❌ REMOVED: Index is redundant because foreignId('package_id') creates it.
            // $t->index(['package_id']); 
            
            // This custom index is fine and useful for queries.
            $t->index(['access_expires_at']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};