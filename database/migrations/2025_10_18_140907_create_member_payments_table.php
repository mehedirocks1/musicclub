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
        Schema::create('member_payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('member_id')
    ->nullable()
    ->constrained('members')
    ->cascadeOnDelete();


            // ট্রানজ্যাকশন তথ্য
            $t->string('tran_id')->unique();
            $t->enum('plan', ['monthly', 'yearly']);
            $t->decimal('amount', 12, 2);
            $t->string('currency', 8)->default('BDT');
            $t->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'validation_failed'])->default('pending');

            // গেটওয়ে রেসপন্স ফিল্ড
            $t->string('bank_tran_id')->nullable();
            $t->string('val_id')->nullable();
            $t->string('card_type')->nullable();

            // --- মেম্বার স্ন্যাপশট ---
            $t->string('full_name')->nullable();
            $t->string('name_bn')->nullable();
            $t->string('username')->nullable();
            $t->string('email')->nullable();
            $t->string('phone', 30)->nullable();
            $t->date('dob')->nullable();
            $t->string('gender', 15)->nullable();
            $t->string('blood_group', 5)->nullable();
            $t->string('id_number')->nullable();
            $t->string('education_qualification')->nullable();
            $t->string('profession')->nullable();
            $t->text('other_expertise')->nullable();
            $t->string('country')->nullable();
            $t->string('division')->nullable();
            $t->string('district')->nullable();
            $t->text('address')->nullable();
            $t->string('membership_type')->nullable();

            // প্রোফাইল পিকচার (যদি থাকে)
            $t->string('profile_pic')->nullable();

            // গেটওয়ে পেলোড ও মেটাডাটা
            $t->json('gateway_payload')->nullable();

            // ✅ SoftDeletes + timestamps
            $t->softDeletes();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_payments');
    }
};
