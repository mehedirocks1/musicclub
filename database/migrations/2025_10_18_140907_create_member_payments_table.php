<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_payments', function (Blueprint $table) {
            $table->id();

            // Member relation (nullable to preserve payment history)
            $table->foreignId('member_id')
                ->nullable()
                ->constrained('members')
                ->nullOnDelete();

            // Optional package info
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('package_name')->nullable();
            $table->index('package_id');

            // Transaction info
            $table->string('tran_id')->unique();
            $table->enum('plan', ['monthly', 'yearly'])->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('BDT');
            $table->enum('status', ['pending','paid','failed','cancelled','validation_failed'])->default('pending');

            // Gateway response
            $table->string('bank_tran_id')->nullable();
            $table->string('val_id')->nullable();
            $table->string('card_type')->nullable();

            // Member snapshot (nullable to support both members & subscribers)
            $table->string('full_name')->nullable();
            $table->string('name_bn')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 15)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('id_number')->nullable();
            $table->string('education_qualification')->nullable();
            $table->string('profession')->nullable();
            $table->text('other_expertise')->nullable();
            $table->string('country')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
            $table->string('membership_type')->nullable();
            $table->string('profile_pic')->nullable();

            // Persisted password hash (nullable for subscribers)
            $table->string('password_hash')->nullable();

            // Raw gateway response
            $table->json('gateway_payload')->nullable();

            // Meta
            $table->softDeletes();
            $table->timestamps();

            // Helpful indexes
            $table->index(['member_id']);
            $table->index(['status', 'plan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_payments');
    }
};
