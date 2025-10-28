<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('subscriber_id')->unique();

            // Basic profile
            $t->string('username')->unique();
            $t->string('full_name');
            $t->string('name_bn')->nullable();
            $t->string('email')->nullable()->unique();
            $t->string('phone', 20)->nullable()->unique();
            $t->string('password')->nullable();
            $t->rememberToken();

            // Profile picture
            $t->string('profile_pic')->nullable();

            // Personal details
            $t->date('dob')->nullable();
            $t->string('gender', 10)->nullable();
            $t->string('blood_group', 5)->nullable();     // NEW
            $t->string('id_number', 64)->nullable();      // NEW
            $t->string('education', 120)->nullable();     // NEW
            $t->string('profession')->nullable();
            $t->text('other_expertise')->nullable();

            // Optional full form snapshot (raw request)
            $t->json('registration_snapshot')->nullable(); // NEW

            // Address
            $t->string('country')->default('Bangladesh');
            $t->string('division')->nullable();
            $t->string('district')->nullable();
            $t->text('address')->nullable();

            // Package / Subscription
            $t->string('package_title')->nullable();       // NEW (e.g. Piano)
            $t->string('package_slug')->nullable();        // NEW (e.g. piano)
            $t->enum('fee_type', ['one_time', 'monthly', 'yearly'])->nullable(); // NEW
            $t->decimal('fee_amount', 12, 2)->nullable();  // NEW

            // Legacy sub info (keep for memberships)
            $t->enum('plan', ['monthly', 'yearly'])->nullable();
            $t->enum('status', ['pending', 'active', 'expired', 'inactive'])->default('pending');
            $t->date('started_at')->nullable();
            $t->date('expires_at')->nullable();

            // Payment summary (last txn)
            $t->decimal('last_payment_amount', 12, 2)->nullable();
            $t->string('last_payment_tran_id')->nullable();
            $t->timestamp('last_payment_at')->nullable();
            $t->string('last_payment_gateway')->nullable();
            $t->decimal('balance', 12, 2)->default(0);

            // Meta
            $t->softDeletes();
            $t->timestamps();

            $t->index(['status', 'plan', 'expires_at']);
            $t->index(['package_slug', 'fee_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
