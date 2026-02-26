<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Dataset;
use App\Models\User;
use App\Models\DatasetRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatasetSeeder extends Seeder
{
    private array $kecamatans = [
        ['nama' => 'Teweh Tengah',  'kode' => '6205010'],
        ['nama' => 'Montallat',     'kode' => '6205020'],
        ['nama' => 'Gunung Timang', 'kode' => '6205030'],
        ['nama' => 'Gunung Purei',  'kode' => '6205040'],
        ['nama' => 'Teweh Timur',   'kode' => '6205050'],
        ['nama' => 'Teweh Baru',    'kode' => '6205060'],
        ['nama' => 'Teweh Selatan', 'kode' => '6205070'],
        ['nama' => 'Lahei',         'kode' => '6205080'],
        ['nama' => 'Lahei Barat',   'kode' => '6205090'],
    ];

    private array $tahuns = [2020, 2021, 2022, 2023, 2024];

    public function run(): void
    {
        DatasetRecord::query()->delete();
        Dataset::query()->delete();
        Organization::query()->delete();
        Category::query()->delete();
        User::query()->delete();

        // ─────────────────────────────────────────────────────────
        // 1. USERS
        // ─────────────────────────────────────────────────────────
        User::create(['name' => 'Admin',    'email' => 'admin@baritoutarakab.go.id',    'password' => bcrypt('12345678')]);
        User::create(['name' => 'Operator', 'email' => 'operator@baritoutarakab.go.id', 'password' => bcrypt('12345678')]);
        User::create(['name' => 'Ginko',    'email' => 'ginko@gmail.com',               'password' => bcrypt('12345678')]);

        // ─────────────────────────────────────────────────────────
        // 2. CATEGORIES
        // ─────────────────────────────────────────────────────────
        $categoryData = [
            'Ekonomi' => [
                'icon'  => 'fa-chart-line',
                'color' => '#F59E0B',
            ],
            'Informasi' => [
                'icon'  => 'fa-circle-info',
                'color' => '#6366F1',
            ],
            'Infrastruktur' => [
                'icon'  => 'fa-road',
                'color' => '#F97316',
            ],
            'Pangan' => [
                'icon'  => 'fa-seedling',
                'color' => '#10B981',
            ],
            'Pemerintahan' => [
                'icon'  => 'fa-building-columns',
                'color' => '#3B82F6',
            ],
            'Wilayah' => [
                'icon'  => 'fa-map',
                'color' => '#8B5CF6',
            ],
        ];

        $categories = [];
        foreach ($categoryData as $nama => $data) {
            $categories[$nama] = Category::create([
                'name'        => $nama,
                'slug'        => Str::slug($nama),
                'description' => 'Data statistik sektoral bidang ' . $nama,
                'icon'        => $data['icon'],
                'color'       => $data['color'],
                'is_active'   => true,
            ]);
        }

        // ─────────────────────────────────────────────────────────
        // 3. ORGANIZATIONS
        // ─────────────────────────────────────────────────────────
        $opdList = [
            'Sekretariat Daerah',
            'Sekretariat Dewan Perwakilan Rakyat Daerah',
            'Dinas Perhubungan',
            'Dinas Pendidikan',
            'Dinas Kesehatan',
            'Dinas Sosial, Pemberdayaan Masyarakat dan Desa',
            'Dinas Ketahanan Pangan dan Perikanan',
            'Dinas Kependudukan dan Catatan Sipil',
            'Dinas Kebudayaan, Pariwisata, Kepemudaan dan Olahraga',
            'Dinas Pertanian',
            'Dinas Perdagangan dan Perindustrian',
            'Badan Pengelolaan Keuangan dan Aset',
            'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
            'Badan Kesatuan Bangsa dan Politik',
            'Dinas Tenaga Kerja, Transmigrasi, Koperasi, Usaha Kecil, dan Menengah',
            'Badan Pengelola Pendapatan Daerah',
            'Dinas Pekerjaan Umum dan Penataan Ruang',
            'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
            'Inspektorat Kabupaten',
            'Dinas Perumahan Rakyat, Kawasan Permukiman dan Pertanahan',
            'Badan Penanggulangan Bencana Daerah',
            'Dinas Lingkungan Hidup',
            'Satuan Polisi Pamong Praja',
            'Dinas Pemadam Kebakaran dan Pengamanan',
            'Dinas Komunikasi, Informatika dan Persandian',
            'Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah',
            'Dinas Kearsipan dan Perpustakaan',
            'Dinas Pengendalian Penduduk, Keluarga Berencana dan Pemberdayaan Perempuan dan Perlindungan Anak',
            'Kecamatan Lahei',
            'Kecamatan Lahei Barat',
            'Kecamatan Montallat',
            'Kecamatan Teweh Baru',
            'Kecamatan Teweh Tengah',
            'Kecamatan Teweh Timur',
            'Kecamatan Teweh Selatan',
            'Kecamatan Gunung Timang',
            'Kecamatan Gunung Purei',
        ];

        $organizations = [];
        foreach ($opdList as $nama) {
            $organizations[$nama] = Organization::create([
                'name'        => $nama,
                'slug'        => Str::slug($nama),
                'code'        => strtoupper(substr(Str::slug($nama), 0, 20)),
                'description' => $nama . ' Kabupaten Barito Utara',
                'email'       => Str::slug($nama) . '@baritoutara.go.id',
                'is_active'   => true,
            ]);
        }

        $dinasPendidikan = $organizations['Dinas Pendidikan'];
        $dinasKesehatan  = $organizations['Dinas Kesehatan'];
        $bps             = $organizations['Dinas Pengendalian Penduduk, Keluarga Berencana dan Pemberdayaan Perempuan dan Perlindungan Anak'];

        // ─────────────────────────────────────────────────────────
        // 4. DATASET A: Jumlah Siswa SD Per Kecamatan
        // ─────────────────────────────────────────────────────────
        // $datasetSD = Dataset::create([
        //     'title'           => 'Jumlah Siswa SD Per Kecamatan',
        //     'slug'            => 'jumlah-siswa-sd-per-kecamatan',
        //     'description'     => 'Data jumlah siswa Sekolah Dasar (SD) Negeri dan Swasta per Kecamatan di Kabupaten Barito Utara',
        //     'category_id'     => $categories['Informasi']->id,
        //     'organization_id' => $dinasPendidikan->id,
        //     'columns'         => [
        //         'kecamatan'      => 'Kecamatan',
        //         'siswa_negeri'   => 'Siswa SD Negeri',
        //         'siswa_swasta'   => 'Siswa SD Swasta',
        //         'jumlah_sekolah' => 'Jumlah Sekolah',
        //         'total'          => 'Total Siswa',
        //     ],
        //     'unit'         => 'Orang',
        //     'frequency'    => 'Tahunan',
        //     'start_year'   => 2020,
        //     'end_year'     => 2024,
        //     'status'       => 'published',
        //     'published_at' => now(),
        // ]);

        // $this->seedRecords($datasetSD, function ($kec, $tahun) {
        //     $negeri  = rand(200, 1500);
        //     $swasta  = rand(0, 300);
        //     $sekolah = rand(3, 12);
        //     $total   = $negeri + $swasta;
        //     return [
        //         'values'      => [
        //             'kecamatan'      => $kec['nama'],
        //             'siswa_negeri'   => $negeri,
        //             'siswa_swasta'   => $swasta,
        //             'jumlah_sekolah' => $sekolah,
        //             'total'          => $total,
        //         ],
        //         'nilai_utama' => $total,
        //     ];
        // });
    }


    // ─────────────────────────────────────────────────────────────
    // Helper: seed records tanpa kolom lokasi — semua masuk values
    // ─────────────────────────────────────────────────────────────
    private function seedRecords(Dataset $dataset, callable $generator): void
    {
        $records = [];

        foreach ($this->kecamatans as $kec) {
            foreach ($this->tahuns as $tahun) {
                $data = $generator($kec, $tahun);

                $records[] = [
                    'dataset_id'  => $dataset->id,
                    'tahun'       => $tahun,
                    'bulan'       => null,
                    'satuan'      => $dataset->unit,
                    'values'      => json_encode($data['values']),
                    'nilai_utama' => $data['nilai_utama'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        // Batch insert agar lebih cepat
        DatasetRecord::insert($records);

        $total = count($this->kecamatans) * count($this->tahuns);
        $this->command->info("  ✓ [{$dataset->title}] → {$total} records");
    }
}