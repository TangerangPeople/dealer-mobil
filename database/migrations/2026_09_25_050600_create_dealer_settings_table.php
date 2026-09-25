<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dealer_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('dealer_name');
            $table->string('logo_path')->nullable();
            $table->string('tagline')->nullable();
            $table->text('home_intro')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->longText('history')->nullable();
            $table->longText('about_content')->nullable();
            $table->text('address');
            $table->string('phone', 30);
            $table->string('email');
            $table->string('whatsapp', 20);
            $table->json('opening_hours')->nullable();
            $table->json('services')->nullable();
            $table->string('map_url', 2048)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE dealer_settings ADD CONSTRAINT dealer_settings_singleton CHECK (id = 1)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dealer_settings');
    }
};
