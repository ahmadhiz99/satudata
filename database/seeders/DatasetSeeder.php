<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Dataset;
use App\Models\DatasetRecord;
use Illuminate\Database\Seeder;

class DatasetSeeder extends Seeder
{
    // ─── 9 Kecamatan Barito Utara ──────────────────────────────────
    // Nama disesuaikan dengan output filter_barito_utara.php (dari GADM)
    private array $kecamatans = [
        ['nama' => 'Teweh Tengah',   'kode' => '6205010'],
        ['nama' => 'Montallat',       'kode' => '6205020'],
        ['nama' => 'Gunung Timang',   'kode' => '6205030'],
        ['nama' => 'Gunung Purei',    'kode' => '6205040'],
        ['nama' => 'Teweh Timur',     'kode' => '6205050'],
        ['nama' => 'Teweh Baru',      'kode' => '6205060'],
        ['nama' => 'Teweh Selatan',   'kode' => '6205070'],
        ['nama' => 'Lahei',           'kode' => '6205080'],
        ['nama' => 'Lahei Barat',     'kode' => '6205090'],
        // ⚠️ Setelah run filter_barito_utara.php, update array ini
        // dengan isi dari kecamatan_list_for_seeder.php
    ];

    private array $tahuns = [2020, 2021, 2022, 2023, 2024];

    public function run(): void
    {
        // ── Truncate semua tabel terkait ──────────────────────────
        DatasetRecord::query()->delete();
        Dataset::query()->delete();
        Organization::query()->delete();
        Category::query()->delete();

        // ─────────────────────────────────────────────────────────
        // 1. CATEGORIES
        // ─────────────────────────────────────────────────────────
        $pendidikan = Category::create([
            'name'        => 'Pendidikan',
            'slug'        => 'pendidikan',
            'description' => 'Data statistik terkait pendidikan',
            'icon'        => 'fa-graduation-cap',
            'color'       => '#3B82F6',
        ]);

        $kesehatan = Category::create([
            'name'        => 'Kesehatan',
            'slug'        => 'kesehatan',
            'description' => 'Data statistik terkait kesehatan',
            'icon'        => 'fa-hospital',
            'color'       => '#10B981',
        ]);

        $ekonomi = Category::create([
            'name'        => 'Ekonomi',
            'slug'        => 'ekonomi',
            'description' => 'Data statistik terkait ekonomi dan keuangan',
            'icon'        => 'fa-chart-line',
            'color'       => '#F59E0B',
        ]);

        // ─────────────────────────────────────────────────────────
        // 2. ORGANIZATIONS
        // ─────────────────────────────────────────────────────────
        $dinasPendidikan = Organization::create([
            'name'        => 'Dinas Pendidikan Kabupaten Barito Utara',
            'slug'        => 'dinas-pendidikan-barito-utara',
            'code'        => 'DISDIK-BARTIM',
            'description' => 'Dinas Pendidikan Kabupaten Barito Utara',
            'email'       => 'disdik@baritoutara.go.id',
        ]);

        $dinasKesehatan = Organization::create([
            'name'        => 'Dinas Kesehatan Kabupaten Barito Utara',
            'slug'        => 'dinas-kesehatan-barito-utara',
            'code'        => 'DINKES-BARTIM',
            'description' => 'Dinas Kesehatan Kabupaten Barito Utara',
            'email'       => 'dinkes@baritoutara.go.id',
        ]);

        $bps = Organization::create([
            'name'        => 'Badan Pusat Statistik Kabupaten Barito Utara',
            'slug'        => 'bps-barito-utara',
            'code'        => 'BPS-BARTIM',
            'description' => 'Badan Pusat Statistik Kabupaten Barito Utara',
            'email'       => 'bps@baritoutara.go.id',
        ]);

        // ─────────────────────────────────────────────────────────
        // 3. DATASET A: Jumlah Siswa SD Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetSD = Dataset::create([
            'title'           => 'Jumlah Siswa SD Per Kecamatan',
            'slug'            => 'jumlah-siswa-sd-per-kecamatan',
            'description'     => 'Data jumlah siswa Sekolah Dasar (SD) Negeri dan Swasta per Kecamatan di Kabupaten Barito Utara',
            'category_id'     => $pendidikan->id,
            'organization_id' => $dinasPendidikan->id,
            'columns'         => [
                'siswa_negeri'      => 'Siswa SD Negeri',
                'siswa_swasta'      => 'Siswa SD Swasta',
                'jumlah_sekolah'    => 'Jumlah Sekolah',
                'total'             => 'Total Siswa',
            ],
            'unit'         => 'Orang',
            'frequency'    => 'Tahunan',
            'start_year'   => 2020,
            'end_year'     => 2024,
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->seedRecords($datasetSD, function ($kec, $tahun) {
            $negeri  = rand(200, 1500);
            $swasta  = rand(0, 300);
            $sekolah = rand(3, 12);
            $total   = $negeri + $swasta;
            return [
                'values'      => [
                    'siswa_negeri'   => $negeri,
                    'siswa_swasta'   => $swasta,
                    'jumlah_sekolah' => $sekolah,
                    'total'          => $total,
                ],
                'nilai_utama' => $total,
            ];
        });

        // ─────────────────────────────────────────────────────────
        // 4. DATASET B: Fasilitas Kesehatan Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetKesehatan = Dataset::create([
            'title'           => 'Fasilitas Kesehatan Per Kecamatan',
            'slug'            => 'fasilitas-kesehatan-per-kecamatan',
            'description'     => 'Data jumlah fasilitas kesehatan (Puskesmas, Pustu, Posyandu) per Kecamatan di Kabupaten Barito Utara',
            'category_id'     => $kesehatan->id,
            'organization_id' => $dinasKesehatan->id,
            'columns'         => [
                'puskesmas' => 'Puskesmas',
                'pustu'     => 'Puskesmas Pembantu',
                'posyandu'  => 'Posyandu',
                'total'     => 'Total Fasilitas',
            ],
            'unit'         => 'Unit',
            'frequency'    => 'Tahunan',
            'start_year'   => 2020,
            'end_year'     => 2024,
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->seedRecords($datasetKesehatan, function ($kec, $tahun) {
            $puskesmas = rand(1, 3);
            $pustu     = rand(1, 6);
            $posyandu  = rand(5, 25);
            $total     = $puskesmas + $pustu + $posyandu;
            return [
                'values'      => [
                    'puskesmas' => $puskesmas,
                    'pustu'     => $pustu,
                    'posyandu'  => $posyandu,
                    'total'     => $total,
                ],
                'nilai_utama' => $total,
            ];
        });

        // ─────────────────────────────────────────────────────────
        // 5. DATASET C: Jumlah Penduduk Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetPenduduk = Dataset::create([
            'title'           => 'Jumlah Penduduk Per Kecamatan',
            'slug'            => 'jumlah-penduduk-per-kecamatan',
            'description'     => 'Data jumlah penduduk per Kecamatan di Kabupaten Barito Utara berdasarkan jenis kelamin',
            'category_id'     => $ekonomi->id,
            'organization_id' => $bps->id,
            'columns'         => [
                'laki_laki'   => 'Penduduk Laki-laki',
                'perempuan'   => 'Penduduk Perempuan',
                'kepala_kk'   => 'Jumlah KK',
                'total'       => 'Total Penduduk',
            ],
            'unit'         => 'Jiwa',
            'frequency'    => 'Tahunan',
            'start_year'   => 2020,
            'end_year'     => 2024,
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->seedRecords($datasetPenduduk, function ($kec, $tahun) {
            $laki     = rand(1500, 15000);
            $perempuan= rand(1400, 14000);
            $kk       = (int)(($laki + $perempuan) / rand(3, 5));
            $total    = $laki + $perempuan;
            return [
                'values'      => [
                    'laki_laki'   => $laki,
                    'perempuan'   => $perempuan,
                    'kepala_kk'   => $kk,
                    'total'       => $total,
                ],
                'nilai_utama' => $total,
            ];
        });

        $this->command->info("\n✅ Seeding selesai! Scope: Barito Utara per Kecamatan");
        $this->command->info("   Dataset: 3 | Kecamatan: " . count($this->kecamatans) . " | Tahun: " . count($this->tahuns));
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER: Loop kecamatan × tahun dan insert records
    // ─────────────────────────────────────────────────────────────
    private function seedRecords(Dataset $dataset, callable $generator): void
    {
        foreach ($this->kecamatans as $kec) {
            foreach ($this->tahuns as $tahun) {
                $data = $generator($kec, $tahun);

                DatasetRecord::create([
                    'dataset_id'    => $dataset->id,
                    'kabupaten_kota'=> $kec['nama'],   // ← nama kecamatan
                    'kode_kabkota'  => $kec['kode'],   // ← kode kecamatan
                    'kecamatan'     => $kec['nama'],
                    'desa'          => '-',
                    'tahun'         => $tahun,
                    'bulan'         => '-',
                    'satuan'        => $dataset->unit,
                    'values'        => $data['values'],
                    'nilai_utama'   => $data['nilai_utama'],
                ]);
            }
        }

        $total = count($this->kecamatans) * count($this->tahuns);
        $this->command->info("  ✓ [{$dataset->title}] → {$total} records");
    }
}