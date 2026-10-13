<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('unit', 30)->default('pcs');
            $table->decimal('purchase_price', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('min_stock')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['stock', 'min_stock']);
            $table->index('name');
        });

        Schema::table('service_bookings', function (Blueprint $table): void {
            $table->foreignId('mechanic_id')
                ->nullable()
                ->after('service_package_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        Schema::create('service_booking_spare_parts', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('service_booking_id')
                ->constrained('service_bookings')
                ->cascadeOnDelete();

            $table->foreignId('spare_part_id')
                ->constrained('spare_parts')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 14, 2);
            $table->text('notes')->nullable();

            $table->foreignId('used_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['service_booking_id', 'spare_part_id'],
                'booking_spare_part_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_booking_spare_parts');

        Schema::table('service_bookings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('mechanic_id');
        });

        Schema::dropIfExists('spare_parts');
    }
};
