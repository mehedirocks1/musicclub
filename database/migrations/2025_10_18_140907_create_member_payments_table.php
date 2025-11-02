<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_payments', function (Blueprint $t) {
            $t->id();

            // members relation
            // USE nullOnDelete() TO PRESERVE PAYMENT HISTORY
            $t->foreignId('member_id')
                ->nullable()
                ->constrained('members')
                ->nullOnDelete(); // <-- This is the only critical change needed

            // Optional package relation (no constraint)
            $t->unsignedBigInteger('package_id')->nullable();
            $t->index('package_id');

            // Optional package snapshot
            $t->string('package_name')->nullable();

            // Transaction info
            $t->string('tran_id')->unique();
            $t->enum('plan', ['monthly', 'yearly'])->nullable();
            $t->decimal('amount', 12, 2);
            $t->string('currency', 8)->default('BDT');
            $t->enum('status', [
                'pending',
                'paid',
                'failed',
                'cancelled',
                'validation_failed'
            ])->default('pending');

            // Gateway response
            $t->string('bank_tran_id')->nullable();
            $t->string('val_id')->nullable();
            $t->string('card_type')->nullable();

            // Member snapshot (all nullable to support both flows)
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
            $t->string('profile_pic')->nullable();

            // Persisted hashed password (nullable for Subscribers)
            $t->string('password_hash')->nullable();

            // Gateway payload (raw response data)
            $t->json('gateway_payload')->nullable();

            // Meta info
            $t->softDeletes();
            $t->timestamps();

            // Helpful indexes
            $t->index(['member_id']);
            $t->index(['status', 'plan']);
            // $t->index(['tran_id']); // ->unique() already adds an index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_payments');
    }
};