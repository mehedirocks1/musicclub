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
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            // This method automatically creates the 'orders_buyer_type_buyer_id_index'
            $t->nullableMorphs('buyer'); // buyer_type (Member/Subscriber), buyer_id 
            
            $t->string('order_code')->unique();
            $t->string('status', 20)->default('pending'); // pending|paid|failed|refunded|cancelled
            $t->string('gateway', 30)->nullable(); // sslcommerz|bkash|nagad
            $t->string('tran_id')->nullable();
            $t->decimal('subtotal', 12, 2)->default(0);
            $t->decimal('tax', 12, 2)->default(0);
            $t->decimal('discount', 12, 2)->default(0);
            $t->decimal('total', 12, 2)->default(0);
            $t->string('currency', 10)->default('BDT');
            $t->timestamp('paid_at')->nullable();
            $t->json('meta')->nullable(); // gateway raw snapshot
            $t->timestamps();
            
            // ❌ REMOVED: This line is redundant because nullableMorphs('buyer') already creates this index.
            // $t->index(['buyer_type','buyer_id']); 
            
            // This custom index is fine and should be kept.
            $t->index(['status','gateway','paid_at']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};