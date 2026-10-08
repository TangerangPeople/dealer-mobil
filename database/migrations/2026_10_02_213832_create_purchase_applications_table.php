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
        Schema::create('purchase_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number', 32)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vehicle_brand', 100);
            $table->string('vehicle_name');
            $table->string('vehicle_type', 100);
            $table->unsignedSmallInteger('vehicle_year');
            $table->decimal('vehicle_price', 15, 0);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            $table->string('customer_whatsapp', 20);
            $table->text('customer_address');
            $table->string('purchase_method', 30);
            $table->text('financing_preference')->nullable();
            $table->text('customer_note')->nullable();
            $table->string('status', 20)->default('pending');
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_applications');
    }
};
