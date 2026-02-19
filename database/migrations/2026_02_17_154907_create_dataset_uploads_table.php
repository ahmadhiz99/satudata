<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable(); // dalam bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_uploads');
    }
};