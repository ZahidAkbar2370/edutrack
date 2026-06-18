<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Membership Information
            $table->foreignUuid('membership_id')->constrained('memberships')->cascadeOnDelete();

            // School Information
            $table->string('school_name');
            $table->string('school_email')->nullable();
            $table->string('school_phone_no');
            $table->string('city');
            $table->string('address');
            $table->string('school_logo')->default('Admin/images/school/logo/default.png');

            // Principal Information
            $table->string('priciple_name');
            $table->string('priciple_phone_no')->nullable();
            $table->string('priciple_email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
