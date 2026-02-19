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
                'subs'  => ['Koperasi', 'Pajak Bumi Dan Bangunan', 'Pariwisata', 'Perikanan', 'Peternakan', 'Sarana Perekonomian', 'UMKM', 'Unit Usaha'],
            ],
            'Informasi' => [
                'icon'  => 'fa-circle-info',
                'color' => '#6366F1',
                'subs'  => ['Damkar', 'Keagamaan', 'Keamanan Dan Ketertiban', 'Kesehatan', 'Pendidikan', 'Penduduk', 'Polisi Pamong Praja', 'Tenaga Kerja'],
            ],
            'Infrastruktur' => [
                'icon'  => 'fa-road',
                'color' => '#F97316',
                'subs'  => ['Jalan Dan Jembatan', 'Komunikasi Dan Informatika', 'Olahraga', 'Tenaga Listrik', 'Transportasi'],
            ],
            'Pangan' => [
                'icon'  => 'fa-seedling',
                'color' => '#10B981',
                'subs'  => ['Kehutanan', 'Pertanian Dan Perkebunan'],
            ],
            'Pemerintahan' => [
                'icon'  => 'fa-building-columns',
                'color' => '#3B82F6',
                'subs'  => ['Anggaran Belanja TIK', 'Data Pemilu', 'DPRD', 'Keuangan Daerah', 'Organisasi', 'Pembangunan', 'Pemerintahan', 'Perizinan', 'Perpustakaan', 'Pertanahan', 'Produk Hukum', 'Sosial'],
            ],
            'Wilayah' => [
                'icon'  => 'fa-map',
                'color' => '#8B5CF6',
                'subs'  => ['Geografis', 'Lingkungan', 'Luas Wilayah', 'Penanggulangan Bencana Alam', 'Perumahan, Pemukiman Dan Pertamanan', 'Sumber Daya Alam', 'Topologi'],
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
            // BADAN
            'Badan Kepegawaian Dan Pengembangan Sumber Daya Manusia',
            'Badan Kesatuan Bangsa Dan Politik',
            'Badan Penanggulangan Bencana Daerah',
            'Badan Pendapatan Daerah',
            'Badan Pengelolaan Keuangan Aset Daerah',
            'Badan Perencanaan Pembangunan, Riset Dan Inovasi Daerah',
            // SEKRETARIAT DAERAH
            'Bagian Administrasi Pembangunan Sekretariat Daerah',
            'Bagian Hukum Sekretariat Daerah',
            'Bagian Kesejahteraan Rakyat Sekretariat Daerah',
            'Bagian Organisasi Sekretariat Daerah',
            'Bagian Pemerintahan Sekretariat Daerah',
            'Bagian Pengadaan Barang Dan Jasa Sekretariat Daerah',
            'Bagian Perekonomian Dan Sumber Daya Alam Sekretariat Daerah',
            'Bagian Protokol Dan Komunikasi Pimpinan Sekretariat Daerah',
            'Bagian Umum Sekretariat Daerah',
            'Sekretariat Daerah',
            'Sekretariat DPRD',
            // DINAS
            'Dinas Kebudayaan, Kepemudaan Dan Olahraga Serta Pariwisata',
            'Dinas Kependudukan Dan Pencatatan Sipil',
            'Dinas Kesehatan',
            'Dinas Ketahanan Pangan Dan Pertanian',
            'Dinas Komunikasi Dan Informatika',
            'Dinas Koperasi, Usaha Kecil Dan Menengah, Perindustrian Dan Perdagangan',
            'Dinas Lingkungan Hidup',
            'Dinas Pekerjaan Umum Dan Penataan Ruang',
            'Dinas Pemadam Kebakaran Dan Penyelamatan',
            'Dinas Pemberdayaan Masyarakat Dan Desa',
            'Dinas Pemberdayaan Perempuan Dan Perlindungan Anak Serta Pengendalian Penduduk Dan Keluarga Berencana',
            'Dinas Penanaman Modal Dan Pelayanan Terpadu Satu Pintu',
            'Dinas Pendidikan',
            'Dinas Perhubungan',
            'Dinas Perikanan',
            'Dinas Perpustakaan Dan Kearsipan',
            'Dinas Perumahan Rakyat Dan Kawasan Permukiman Serta Pertanahan',
            'Dinas Sosial',
            'Dinas Transmigrasi Dan Tenaga Kerja',
            'Inspektorat Daerah',
            'Satuan Polisi Pamong Praja',
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

        // Shortcut
        $dinasPendidikan = $organizations['Dinas Pendidikan'];
        $dinasKesehatan  = $organizations['Dinas Kesehatan'];
        $bappeda         = $organizations['Badan Perencanaan Pembangunan, Riset Dan Inovasi Daerah'];
        $bps             = $organizations['Dinas Kependudukan Dan Pencatatan Sipil'];

        // ─────────────────────────────────────────────────────────
        // 4. DATASET A: Jumlah Siswa SD Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetSD = Dataset::create([
            'title'           => 'Jumlah Siswa SD Per Kecamatan',
            'slug'            => 'jumlah-siswa-sd-per-kecamatan',
            'description'     => 'Data jumlah siswa Sekolah Dasar (SD) Negeri dan Swasta per Kecamatan di Kabupaten Barito Utara',
            'category_id'     => $categories['Informasi']->id,
            'organization_id' => $dinasPendidikan->id,
            'columns'         => [
                'siswa_negeri'   => 'Siswa SD Negeri',
                'siswa_swasta'   => 'Siswa SD Swasta',
                'jumlah_sekolah' => 'Jumlah Sekolah',
                'total'          => 'Total Siswa',
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
                'values'      => ['siswa_negeri' => $negeri, 'siswa_swasta' => $swasta, 'jumlah_sekolah' => $sekolah, 'total' => $total],
                'nilai_utama' => $total,
            ];
        });

        // ─────────────────────────────────────────────────────────
        // 5. DATASET B: Fasilitas Kesehatan Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetKesehatan = Dataset::create([
            'title'           => 'Fasilitas Kesehatan Per Kecamatan',
            'slug'            => 'fasilitas-kesehatan-per-kecamatan',
            'description'     => 'Data jumlah fasilitas kesehatan per Kecamatan di Kabupaten Barito Utara',
            'category_id'     => $categories['Informasi']->id,
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
                'values'      => ['puskesmas' => $puskesmas, 'pustu' => $pustu, 'posyandu' => $posyandu, 'total' => $total],
                'nilai_utama' => $total,
            ];
        });

        // ─────────────────────────────────────────────────────────
        // 6. DATASET C: Jumlah Penduduk Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetPenduduk = Dataset::create([
            'title'           => 'Jumlah Penduduk Per Kecamatan',
            'slug'            => 'jumlah-penduduk-per-kecamatan',
            'description'     => 'Data jumlah penduduk per Kecamatan di Kabupaten Barito Utara berdasarkan jenis kelamin',
            'category_id'     => $categories['Wilayah']->id,
            'organization_id' => $bps->id,
            'columns'         => [
                'laki_laki' => 'Penduduk Laki-laki',
                'perempuan' => 'Penduduk Perempuan',
                'kepala_kk' => 'Jumlah KK',
                'total'     => 'Total Penduduk',
            ],
            'unit'         => 'Jiwa',
            'frequency'    => 'Tahunan',
            'start_year'   => 2020,
            'end_year'     => 2024,
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->seedRecords($datasetPenduduk, function ($kec, $tahun) {
            $laki      = rand(1500, 15000);
            $perempuan = rand(1400, 14000);
            $kk        = (int)(($laki + $perempuan) / rand(3, 5));
            $total     = $laki + $perempuan;
            return [
                'values'      => ['laki_laki' => $laki, 'perempuan' => $perempuan, 'kepala_kk' => $kk, 'total' => $total],
                'nilai_utama' => $total,
            ];
        });

        // ─────────────────────────────────────────────────────────
        // 7. DATASET D: Produksi Pertanian Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetPertanian = Dataset::create([
            'title'           => 'Produksi Pertanian Per Kecamatan',
            'slug'            => 'produksi-pertanian-per-kecamatan',
            'description'     => 'Data produksi tanaman pangan per Kecamatan di Kabupaten Barito Utara',
            'category_id'     => $categories['Pangan']->id,
            'organization_id' => $organizations['Dinas Ketahanan Pangan Dan Pertanian']->id,
            'columns'         => [
                'padi'     => 'Padi (Ton)',
                'jagung'   => 'Jagung (Ton)',
                'singkong' => 'Singkong (Ton)',
                'total'    => 'Total Produksi',
            ],
            'unit'         => 'Ton',
            'frequency'    => 'Tahunan',
            'start_year'   => 2020,
            'end_year'     => 2024,
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->seedRecords($datasetPertanian, function ($kec, $tahun) {
            $padi     = rand(100, 3000);
            $jagung   = rand(50, 800);
            $singkong = rand(20, 500);
            $total    = $padi + $jagung + $singkong;
            return [
                'values'      => ['padi' => $padi, 'jagung' => $jagung, 'singkong' => $singkong, 'total' => $total],
                'nilai_utama' => $total,
            ];
        });

        // ─────────────────────────────────────────────────────────
        // 8. DATASET E: Panjang Jalan Per Kecamatan
        // ─────────────────────────────────────────────────────────
        $datasetJalan = Dataset::create([
            'title'           => 'Panjang Jalan Per Kecamatan',
            'slug'            => 'panjang-jalan-per-kecamatan',
            'description'     => 'Data panjang jalan berdasarkan kondisi per Kecamatan di Kabupaten Barito Utara',
            'category_id'     => $categories['Infrastruktur']->id,
            'organization_id' => $organizations['Dinas Pekerjaan Umum Dan Penataan Ruang']->id,
            'columns'         => [
                'baik'     => 'Kondisi Baik (KM)',
                'sedang'   => 'Kondisi Sedang (KM)',
                'rusak'    => 'Kondisi Rusak (KM)',
                'total'    => 'Total Panjang',
            ],
            'unit'         => 'KM',
            'frequency'    => 'Tahunan',
            'start_year'   => 2020,
            'end_year'     => 2024,
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->seedRecords($datasetJalan, function ($kec, $tahun) {
            $baik   = rand(10, 80);
            $sedang = rand(5, 40);
            $rusak  = rand(2, 30);
            $total  = $baik + $sedang + $rusak;
            return [
                'values'      => ['baik' => $baik, 'sedang' => $sedang, 'rusak' => $rusak, 'total' => $total],
                'nilai_utama' => $total,
            ];
        });

        $this->command->info("\n✅ Seeding selesai! Scope: Barito Utara per Kecamatan");
        $this->command->info("   Kategori : " . count($categoryData));
        $this->command->info("   OPD      : " . count($opdList));
        $this->command->info("   Dataset  : 5 | Kecamatan: " . count($this->kecamatans) . " | Tahun: " . count($this->tahuns));
    }

    private function seedRecords(Dataset $dataset, callable $generator): void
    {
        foreach ($this->kecamatans as $kec) {
            foreach ($this->tahuns as $tahun) {
                $data = $generator($kec, $tahun);
                DatasetRecord::create([
                    'dataset_id'     => $dataset->id,
                    'kabupaten_kota' => $kec['nama'],
                    'kode_kabkota'   => $kec['kode'],
                    'kecamatan'      => $kec['nama'],
                    'desa'           => '-',
                    'tahun'          => $tahun,
                    'bulan'          => '-',
                    'satuan'         => $dataset->unit,
                    'values'         => $data['values'],
                    'nilai_utama'    => $data['nilai_utama'],
                ]);
            }
        }

        $total = count($this->kecamatans) * count($this->tahuns);
        $this->command->info("  ✓ [{$dataset->title}] → {$total} records");
    }
}