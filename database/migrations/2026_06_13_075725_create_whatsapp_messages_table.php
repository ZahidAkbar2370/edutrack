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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignUuid('student_id')->nullable()->constrained('students')->cascadeOnDelete();
            $table->foreignUuid('teacher_id')->nullable()->constrained('teachers')->cascadeOnDelete();
            $table->foreignUuid('parent_id')->nullable()->constrained('parents')->cascadeOnDelete();
            $table->enum('message_type', ['attendance', 'daily_test', 'fee', 'other'])->default('other');
            $table->string('from_number')->nullable();
            $table->string('to_number')->nullable();
            $table->string('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
