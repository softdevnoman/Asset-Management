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
        if (!Schema::hasColumn('accounts', 'company_name')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->string('company_name')->nullable()->after('id');
                $table->string('company_email')->unique()->nullable()->after('company_name');
                $table->string('subscription_plan')->default('basic')->after('company_email');
                $table->string('status')->default('pending')->after('subscription_plan');
            });
        }

        if (!Schema::hasColumn('users', 'account_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('account_id')->nullable()->after('id')->constrained('accounts')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('users', 'otp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('otp', 10)->nullable()->after('password');
                $table->string('verification_token', 100)->nullable()->after('otp');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
