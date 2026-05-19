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
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->string('serial_number')->nullable();
            $table->string('purchased_date')->nullable();
            $table->decimal('purchased_price')->nullable();
            $table->decimal('current_value')->nullable();
            $table->string('condition')->default('New');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->date('maintenance_date')->nullable();
            $table->string('notes')->nullable();
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
