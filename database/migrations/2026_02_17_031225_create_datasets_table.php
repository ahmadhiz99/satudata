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
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Jumlah Siswa SMK Per Kabupaten/Kota
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            
            // Metadata
            $table->json('columns')->nullable(); // Kolom-kolom yang ada di dataset
            $table->string('unit')->nullable(); // Orang, Persen, Rupiah, dll
            $table->string('frequency')->nullable(); // Tahunan, Bulanan, dll
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            
            // Status & Visibility
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->boolean('is_public')->default(true);
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset');
    }
};
