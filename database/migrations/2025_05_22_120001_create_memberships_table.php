<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('membership_name');
            $table->string('membership_price')->default('0');
            $table->string('students_limit')->nullable(); // null = unlimited
            $table->string('teachers_limit')->nullable(); // null = unlimited
            $table->boolean('allowed_attendance')->default(false);
            $table->boolean('allowed_daily_test')->default(false);
            $table->boolean('allowed_student_card')->default(false);
            $table->boolean('allowed_fee_management')->default(false);
            $table->boolean('allowed_whatsapp_message')->default(false);
            $table->boolean('allowed_whatsapp_announcement')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
