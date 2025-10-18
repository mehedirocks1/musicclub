<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            // 🖼️ Profile
            $table->string('profile_pic')->nullable(); // storage path

            // 🧍 Basic Info
            $table->string('member_id')->unique();
            $table->string('username')->unique();
            $table->string('name_bn')->nullable();
            $table->string('full_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 20)->nullable()->unique();

            // 🔐 Authentication
            $table->string('password')->nullable();
            $table->rememberToken(); // 🔐 optional but useful for auth guards

            // 👨‍👩 Family Info
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();

            // 🎂 Personal Info
            $table->date('dob')->nullable();
            $table->string('id_number')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('blood_group', 3)->nullable();

            // 🎓 Professional Info
            $table->string('education_qualification')->nullable();
            $table->string('profession')->nullable();
            $table->text('other_expertise')->nullable();

            // 🌍 Address
            $table->string('country')->default('Bangladesh');
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();

            // 🧾 Membership (existing)
            $table->string('membership_type')->default('Student');
            $table->date('registration_date')->nullable();

            // 🧾 ➕ Membership add-ons (for Monthly/Yearly + lifecycle)
            $table->enum('membership_plan', ['monthly', 'yearly'])->default('monthly'); // ✅ new
            $table->enum('membership_status', ['pending', 'active', 'expired', 'inactive'])->default('pending'); // ✅ new
            $table->date('membership_started_at')->nullable();  // ✅ new
            $table->date('membership_expires_at')->nullable();  // ✅ new

            // 💰 Account
            $table->decimal('balance', 12, 2)->default(0);

            // 💳 Last payment snapshot (handy for dashboard/quick look)
            $table->decimal('last_payment_amount', 12, 2)->nullable(); // ✅ new
            $table->string('last_payment_tran_id')->nullable();        // ✅ new
            $table->timestamp('last_payment_at')->nullable();          // ✅ new
            $table->string('last_payment_gateway')->nullable();        // e.g., 'sslcommerz' // ✅ new

            // 🧠 Soft Deletes + timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('membership_type');
            $table->index('registration_date');
            $table->index('membership_plan');     // ✅ new
            $table->index('membership_status');   // ✅ new
            $table->index('membership_expires_at'); // ✅ new
        });

        // ✅ Add foreign key users.member_id → members.id
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'member_id')) {
                $table->foreign('member_id')
                    ->references('id')->on('members')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'member_id')) {
            try {
                DB::statement('ALTER TABLE `users` DROP FOREIGN KEY `users_member_id_foreign`');
            } catch (\Throwable $e) {
                // ignore if not exists
            }
        }

        Schema::dropIfExists('members');
    }
};
