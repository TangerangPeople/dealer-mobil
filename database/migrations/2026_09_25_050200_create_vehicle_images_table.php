<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $primaryVehicleExpression = DB::getDriverName() === 'sqlite'
            ? 'CASE WHEN is_primary = 1 THEN vehicle_id ELSE NULL END'
            : 'IF(is_primary = 1, vehicle_id, NULL)';

        Schema::create('vehicle_images', function (Blueprint $table) use ($primaryVehicleExpression) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('alt_text');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->unsignedBigInteger('primary_vehicle_id')->storedAs($primaryVehicleExpression)->unique();
            $table->unique(['vehicle_id', 'sort_order']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_images');
    }
};
