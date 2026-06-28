<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignUuid('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignUuid('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUuid('teacher_id')->constrained('teachers')->cascadeOnDelete();

            $table->string('daily_test_code')->nullable();

            $table->date('daily_test_date');
            $table->string('daily_test_name');
            $table->string('subject');
            $table->unsignedInteger('daily_test_obtained');
            $table->unsignedInteger('daily_test_total');
            $table->double('daily_test_percentage');
            $table->string('daily_test_note')->nullable();
            $table->enum('whatsapp_status', ['pending', 'processing', 'sent', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_tests');
    }
};
