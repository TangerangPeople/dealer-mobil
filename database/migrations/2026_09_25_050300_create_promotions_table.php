<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 500);
            $table->longText('description');
            $table->string('image_path')->nullable();
            $table->string('image_alt_text')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->index(['is_active', 'start_date', 'end_date']);
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                'ALTER TABLE promotions ADD CONSTRAINT promotions_valid_date_range CHECK (end_date >= start_date)'
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
