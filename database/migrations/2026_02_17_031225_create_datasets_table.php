<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');

            // Relasi ke file upload sumber
            $table->foreignId('dataset_upload_id')
                  ->nullable()
                  ->constrained('dataset_uploads')
                  ->nullOnDelete();

            // Metadata
            $table->json('columns')->nullable();         // {"slug": "Label Asli", ...}
            $table->string('excel_path')->nullable();    // path file Excel (dari add_excel_path)
            $table->boolean('can_visualize')->default(true); // dari add_excel_path
            $table->string('visualize_types')->nullable(); // dari add_excel_path
            $table->string('unit')->nullable();
            $table->string('frequency')->nullable();
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();

            // Status & Visibility
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->boolean('is_public')->default(true);

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};