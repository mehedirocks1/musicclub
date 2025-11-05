<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // 🔗 Relations
            $table->unsignedBigInteger('member_id')->nullable()->index();
            $table->string('subscriber_id')->nullable()->index(); // string-based subscriber key
            $table->unsignedBigInteger('package_id')->nullable()->index();

            // 💰 Payment info
            $table->string('tran_id')->nullable()->unique(); // gateway transaction reference
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('gateway')->nullable(); // sslcommerz, stripe, etc.
            $table->string('method')->nullable(); // bkash, nagad, cash, card
            $table->string('transaction_id')->nullable()->index(); // bank_tran_id / val_id
            $table->json('gateway_payload')->nullable(); // store raw response safely
            $table->text('note')->nullable();

            $table->timestamps();

            // 🔐 Foreign keys (optional, safe with nullable)
            $table->foreign('member_id')
                ->references('id')->on('members')
                ->onDelete('cascade');

            // If you want package link later:
            // $table->foreign('package_id')
            //     ->references('id')->on('packages')
            //     ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
