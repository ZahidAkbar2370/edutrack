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
        Schema::create('monthly_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('payment_date')->nullable();
            $table->string('payment_prove_image')->nullable();

            $table->string('previous_remaining_amount')->nullable();
            $table->string('monthly_fee_amount')->nullable();

            $table->string('any_fine_amount')->default(0);
            $table->string('any_discount_amount')->default(0);

            $table->string('total_amount')->default(0);
            $table->string('paid_amount')->default(0);

            $table->string('remaining_amount')->default(0);
            $table->string('note')->nullable();

            $table->string('system_remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    } 
    public function down(): void
    {
        Schema::dropIfExists('monthly_fees');
    }
};
