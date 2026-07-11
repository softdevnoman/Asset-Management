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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchased_date')->nullable();
            $table->decimal('purchased_price')->nullable();
            $table->decimal('current_value')->nullable();
            $table->string('condition')->default('Good');
            $table->foreignId('location_id')->nullable();
            $table->string('assign_to')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->date('maintenance_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
