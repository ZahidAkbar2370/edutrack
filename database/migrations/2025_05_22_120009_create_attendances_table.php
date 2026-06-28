<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignUuid('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignUuid('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('attendance_code')->nullable();
            $table->date('attendance_date');
            $table->enum('attendance_status', ['present', 'leave', 'late', 'absent'])->default('present');
            $table->string('attendance_note')->nullable();
            $table->enum('whatsapp_status', ['pending', 'processing', 'sent', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
