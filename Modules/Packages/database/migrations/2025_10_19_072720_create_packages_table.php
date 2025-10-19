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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status', 20)->default('draft'); // draft|active|paused|retired
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->enum('billing_period', ['one_time','monthly','yearly'])->default('one_time');
            $table->unsignedInteger('access_duration_days')->nullable();
            $table->timestamp('sale_starts_at')->nullable();
            $table->timestamp('sale_ends_at')->nullable();
            $table->string('image_path')->nullable();
            $table->string('promo_video_url')->nullable();
            $table->string('summary', 300)->nullable();
            $table->longText('description')->nullable();
            $table->json('features')->nullable();
            $table->json('prerequisites')->nullable();
            $table->string('target_audience')->nullable();
            $table->string('visibility', 20)->default('public'); // public|unlisted|archived
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedInteger('enrollments_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->boolean('is_discountable')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status','billing_period','visibility']);
            $table->index(['sale_starts_at','sale_ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
