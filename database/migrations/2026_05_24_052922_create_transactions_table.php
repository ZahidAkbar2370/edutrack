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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('schools')->cascadeOnDelete();
            $table->foreignUuid('school_id')->constrained('schools')->cascadeOnDelete();

            $table->enum('transaction_purpose', ['membership_renew', 'membership_upgrade', 'membership_downgrade', 'domain_hosting', 'other'])->default('other');
            $table->string('transaction_prove_image')->nullable();

            $table->foreignUuid('membership_id')->nullable()->constrained('memberships')->nullOnDelete();
            $table->string('membership_expire_date')->nullable();

            $table->string('transaction_amount')->default(0);

            $table->string('transaction_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
