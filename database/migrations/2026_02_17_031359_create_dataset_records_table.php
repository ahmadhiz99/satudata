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
            
            // Lokasi (bisa null untuk data provinsi)
            $table->string('kabupaten_kota')->nullable();
            $table->string('kode_kabkota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            
            // Waktu
            $table->year('tahun');
            $table->string('bulan')->nullable(); // Januari, Februari, dll atau "-"
            $table->string('satuan')->nullable(); // Orang, Persen, dll
            
            // Data values (flexible JSON untuk berbagai jenis data)
            $table->json('values'); // {"siswa_smk_negeri": 408, "siswa_smk_swasta": 0, ...}
            
            // Nilai agregat untuk sorting/filtering
            $table->decimal('nilai_utama', 15, 2)->nullable(); // Nilai utama untuk chart/sorting
            
            $table->timestamps();
            
            // Indexes
            $table->index(['dataset_id', 'tahun']);
            $table->index(['dataset_id', 'kabupaten_kota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_records');
    }
};