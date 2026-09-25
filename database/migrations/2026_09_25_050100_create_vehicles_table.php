<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('brand', 100);
            $table->string('name');
            $table->string('type', 100);
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year');
            $table->decimal('price', 15, 0);
            $table->string('color', 50)->nullable();
            $table->string('transmission', 30)->nullable();
            $table->unsignedSmallInteger('engine_capacity')->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->enum('status', ['available', 'sold', 'reserved', 'inactive'])->default('available');
            $table->longText('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->index(['category_id', 'status']);
            $table->index(['status', 'is_featured']);
            $table->index('brand');
            $table->index('type');
            $table->index('transmission');
            $table->index('year');
            $table->index('price');
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::getDriverName() === 'mysql') {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->fullText(['name', 'brand', 'type', 'description'], 'vehicles_search_fulltext');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
