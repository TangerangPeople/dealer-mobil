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
        Schema::create('purchase_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_application_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 60);
            $table->string('document_label', 120);
            $table->string('file_path')->unique();
            $table->string('original_name');
            $table->string('mime_type', 127);
            $table->unsignedBigInteger('file_size');
            $table->timestamp('uploaded_at');
            $table->index(
                ['purchase_application_id', 'document_type'],
                'pad_app_doc_type_idx'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_application_documents');
    }
};
