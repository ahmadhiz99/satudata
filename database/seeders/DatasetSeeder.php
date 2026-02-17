<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Dataset;
use App\Models\DatasetRecord;
use Illuminate\Database\Seeder;

class DatasetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Categories
        $pendidikan = Category::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
            'description' => 'Data statistik terkait pendidikan',
            'icon' => 'fa-graduation-cap',
            'color' => '#3B82F6',
        ]);

        $kesehatan = Category::create([
            'name' => 'Kesehatan',
            'slug' => 'kesehatan',
            'description' => 'Data statistik terkait kesehatan',
            'icon' => 'fa-hospital',
            'color' => '#10B981',
        ]);

        $ekonomi = Category::create([
            'name' => 'Ekonomi',
            'slug' => 'ekonomi',
            'description' => 'Data statistik terkait ekonomi dan keuangan',
            'icon' => 'fa-chart-line',
            'color' => '#F59E0B',
        ]);

        // 2. Create Organizations
        $dinasPendidikan = Organization::create([
            'name' => 'Dinas Pendidikan Provinsi Kalimantan Tengah',
            'slug' => 'dinas-pendidikan-kalteng',
            'code' => 'DISDIK-KALTENG',
            'description' => 'Dinas Pendidikan Provinsi Kalimantan Tengah',
            'email' => 'disdik@kalteng.go.id',
        ]);

        $bps = Organization::create([
            'name' => 'Badan Pusat Statistik Kalimantan Tengah',
            'slug' => 'bps-kalteng',
            'code' => 'BPS-KALTENG',
            'description' => 'Badan Pusat Statistik Provinsi Kalimantan Tengah',
            'email' => 'bps@kalteng.go.id',
        ]);

        // 3. Create Dataset: Jumlah Siswa SMK
        $datasetSMK = Dataset::create([
            'title' => 'Jumlah Siswa SMK Per Kabupaten/Kota',
            'slug' => 'jumlah-siswa-smk-per-kabupaten-kota',
            'description' => 'Data jumlah siswa Sekolah Menengah Kejuruan (SMK) Negeri dan Swasta per Kabupaten/Kota di Provinsi Kalimantan Tengah',
            'category_id' => $pendidikan->id,
            'organization_id' => $dinasPendidikan->id,
            'columns' => [
                'siswa_smk_negeri' => 'Siswa SMK Negeri',
                'siswa_smk_swasta' => 'Siswa SMK Swasta',
                'rombongan_belajar' => 'Rombongan Belajar (Ruang)',
                'total' => 'Total Siswa'
            ],
            'unit' => 'Orang',
            'frequency' => 'Tahunan',
            'start_year' => 2023,
            'end_year' => 2024,
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 4. Insert Records
        $dataSMK = [
            ['kabupaten_kota' => 'Kab. Kotawaringin Timur', 'kode' => '6202', 'tahun' => 2024, 'negeri' => 6367, 'swasta' => 1224, 'rombel' => 277],
            ['kabupaten_kota' => 'Kab. Kotawaringin Barat', 'kode' => '6201', 'tahun' => 2024, 'negeri' => 3327, 'swasta' => 1680, 'rombel' => 204],
            ['kabupaten_kota' => 'Kab. Kapuas', 'kode' => '6203', 'tahun' => 2024, 'negeri' => 2630, 'swasta' => 555, 'rombel' => 157],
            ['kabupaten_kota' => 'Kab. Barito Selatan', 'kode' => '6204', 'tahun' => 2024, 'negeri' => 1137, 'swasta' => 246, 'rombel' => 76],
            ['kabupaten_kota' => 'Kab. Barito Utara', 'kode' => '6205', 'tahun' => 2024, 'negeri' => 1711, 'swasta' => 0, 'rombel' => 81],
            ['kabupaten_kota' => 'Kab. Sukamara', 'kode' => '6207', 'tahun' => 2024, 'negeri' => 622, 'swasta' => 0, 'rombel' => 27],
            ['kabupaten_kota' => 'Kab. Lamandau', 'kode' => '6206', 'tahun' => 2024, 'negeri' => 1379, 'swasta' => 0, 'rombel' => 76],
            ['kabupaten_kota' => 'Kab. Seruyan', 'kode' => '6208', 'tahun' => 2024, 'negeri' => 1071, 'swasta' => 603, 'rombel' => 69],
            ['kabupaten_kota' => 'Kab. Katingan', 'kode' => '6209', 'tahun' => 2024, 'negeri' => 1438, 'swasta' => 399, 'rombel' => 84],
            ['kabupaten_kota' => 'Kab. Pulang Pisau', 'kode' => '6210', 'tahun' => 2024, 'negeri' => 496, 'swasta' => 212, 'rombel' => 50],
            ['kabupaten_kota' => 'Kab. Gunung Mas', 'kode' => '6211', 'tahun' => 2024, 'negeri' => 684, 'swasta' => 16, 'rombel' => 34],
            ['kabupaten_kota' => 'Kab. Barito Timur', 'kode' => '6212', 'tahun' => 2024, 'negeri' => 1304, 'swasta' => 45, 'rombel' => 85],
            ['kabupaten_kota' => 'Kab. Murung Raya', 'kode' => '6213', 'tahun' => 2024, 'negeri' => 408, 'swasta' => 0, 'rombel' => 14],
            ['kabupaten_kota' => 'Kota Palangkaraya', 'kode' => '6271', 'tahun' => 2024, 'negeri' => 4244, 'swasta' => 1030, 'rombel' => 209],
        ];

        foreach ($dataSMK as $data) {
            $total = $data['negeri'] + $data['swasta'];
            
            DatasetRecord::create([
                'dataset_id' => $datasetSMK->id,
                'kabupaten_kota' => $data['kabupaten_kota'],
                'kode_kabkota' => $data['kode'],
                'kecamatan' => '-',
                'desa' => '-',
                'tahun' => $data['tahun'],
                'bulan' => '-',
                'satuan' => 'Orang',
                'values' => [
                    'siswa_smk_negeri' => $data['negeri'],
                    'siswa_smk_swasta' => $data['swasta'],
                    'rombongan_belajar' => $data['rombel'],
                    'total' => $total,
                ],
                'nilai_utama' => $total,
            ]);
        }
    }
}