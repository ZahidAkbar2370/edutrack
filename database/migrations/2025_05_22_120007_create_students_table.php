<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignUuid('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignUuid('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('student_name');
            $table->string('student_email')->nullable();
            $table->string('student_phone_no')->nullable();
            $table->string('student_photo')->nullable();
            $table->string('student_roll_number')->nullable();
            $table->date('student_admission_date')->nullable();
            $table->string('student_per_month_fee')->default('0');
            $table->enum('status', ['active', 'completed', 'banned', 'inactive'])
                ->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
