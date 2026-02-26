<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->onDelete('cascade');

            // Waktu
            $table->year('tahun')->nullable();
            $table->string('bulan')->nullable();
            $table->string('satuan')->nullable();

            // Data fleksibel dalam JSON
            $table->json('values'); // {"nama_kolom": nilai, ...}

            // Nilai agregat untuk sorting/chart
            $table->decimal('nilai_utama', 15, 2)->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['dataset_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_records');
    }
};