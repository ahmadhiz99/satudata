<?php

echo "=== GADM Barito Utara Filter Script (Level 3 - Kecamatan) ===\n\n";

// ✅ Level 3 = Kecamatan
$sourceFile = 'gadm41_IDN_3.json';

if (!file_exists($sourceFile)) {
    die("❌ Error: File '$sourceFile' tidak ditemukan.\n   Download dari: https://gadm.org/download_country.html\n   Pilih Indonesia → Level 3\n");
}

echo "📂 Loading GeoJSON dari: $sourceFile\n";
echo "⏳ Mohon tunggu, file ini besar (~200MB)...\n\n";

$geojson = json_decode(file_get_contents($sourceFile), true);

if (!$geojson) {
    die("❌ Error: Gagal parse JSON. File mungkin corrupt.\n");
}

echo "✅ File berhasil dimuat\n";
echo "📊 Total features di Indonesia: " . count($geojson['features']) . "\n\n";

// ─── DEBUG: Lihat sample properties untuk tau nama field yang benar ───
echo "🔎 Sample properties feature pertama:\n";
echo json_encode($geojson['features'][0]['properties'], JSON_PRETTY_PRINT) . "\n\n";

echo "🔍 Filtering Kecamatan di Barito Utara...\n";

$kecamatanFeatures = [];

foreach ($geojson['features'] as $feature) {
    $props = $feature['properties'];

    // Di GADM Level 3:
    //   NAME_1 = Provinsi
    //   NAME_2 = Kabupaten/Kota
    //   NAME_3 = Kecamatan

    $provinsi  = $props['NAME_1'] ?? '';
    $kabupaten = $props['NAME_2'] ?? '';
    $kecamatan = $props['NAME_3'] ?? '';
    $kode      = $props['CC_3']   ?? ($props['HASC_3'] ?? '-');

    // ✅ Filter: Kalimantan Tengah → Barito Utara
    // GADM menyimpan tanpa spasi, cek keduanya untuk aman
    $isKalteng     = in_array($provinsi, ['KalimantanTengah', 'Kalimantan Tengah']);
    $isBarutoUtara = in_array($kabupaten, ['BaritoUtara', 'Barito Utara']);

    if ($isKalteng && $isBarutoUtara) {
        $namaFormatted = formatNamaKecamatan($kecamatan);

        $newFeature = [
            'type'       => 'Feature',
            'properties' => [
                'name'          => $namaFormatted,
                'kode'          => $kode,
                'original_name' => $kecamatan,
                'kabupaten'     => 'Barito Utara',
            ],
            'geometry' => $feature['geometry'],
        ];

        $kecamatanFeatures[] = $newFeature;

        echo "  ✓ $namaFormatted (Kode: $kode)\n";
    }
}

echo "\n📍 Total kecamatan ditemukan: " . count($kecamatanFeatures) . "\n\n";

if (count($kecamatanFeatures) === 0) {
    echo "⚠️  Tidak ada kecamatan ditemukan! Kemungkinan nama field berbeda.\n";
    echo "    Coba lihat sample properties di atas dan sesuaikan filter.\n\n";

    // Debug: tampilkan semua NAME_1 unik untuk cek nama provinsi
    echo "🔎 Debug: Sample NAME_1 (10 pertama):\n";
    $name1s = [];
    foreach (array_slice($geojson['features'], 0, 500) as $f) {
        $n = $f['properties']['NAME_1'] ?? 'N/A';
        if (!in_array($n, $name1s)) $name1s[] = $n;
    }
    foreach ($name1s as $n) echo "  - $n\n";

    die("\n❌ Hentikan. Sesuaikan filter berdasarkan debug di atas.\n");
}

// ─── Buat GeoJSON output ───
$outputGeojson = [
    'type'     => 'FeatureCollection',
    'name'     => 'Kecamatan Barito Utara',
    'features' => $kecamatanFeatures,
];

// Buat folder jika belum ada
$outputDir = 'public/geojson';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
    echo "📁 Folder '$outputDir' dibuat.\n";
}

$outputFile = $outputDir . '/barito_utara.json';
file_put_contents($outputFile, json_encode($outputGeojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "💾 File disimpan: $outputFile\n";
echo "📦 Ukuran file: " . round(filesize($outputFile) / 1024, 2) . " KB\n\n";

echo "✅✅✅ SELESAI!\n\n";

echo "Daftar Kecamatan:\n";
echo str_repeat('-', 60) . "\n";
foreach ($kecamatanFeatures as $feature) {
    echo sprintf("%-40s Kode: %s\n",
        $feature['properties']['name'],
        $feature['properties']['kode']
    );
}
echo str_repeat('-', 60) . "\n";

echo "\n🎯 Next Steps:\n";
echo "  1. Pastikan seeder menggunakan nama kecamatan di atas\n";
echo "  2. Blade: fetch('/geojson/barito_utara.json')\n";
echo "  3. Blade: map center [-1.0, 115.0], zoom 9\n";
echo "  4. php artisan db:seed --class=DatasetRecordSeeder\n\n";

// ─── Ekspor juga daftar nama kecamatan untuk seeder ───
$seederList = array_map(fn($f) => $f['properties']['name'], $kecamatanFeatures);
$seederList = array_unique($seederList);
sort($seederList);

$seederExport  = "<?php\n\n";
$seederExport .= "// Salin array ini ke DatasetRecordSeeder.php\n";
$seederExport .= "// Di-generate otomatis dari: $outputFile\n\n";
$seederExport .= "private array \$kecamatans = [\n";

foreach ($kecamatanFeatures as $f) {
    $nama = addslashes($f['properties']['name']);
    $kode = addslashes($f['properties']['kode']);
    $seederExport .= "    ['nama' => '$nama', 'kode' => '$kode'],\n";
}

$seederExport .= "];\n";

file_put_contents('kecamatan_list_for_seeder.php', $seederExport);
echo "📋 Daftar kecamatan untuk seeder disimpan: kecamatan_list_for_seeder.php\n";


// ===== HELPER FUNCTION =====

function formatNamaKecamatan(string $name): string
{
    // GADM Level 3 biasanya sudah dalam format yang cukup baik
    // tapi kadang masih camelCase tanpa spasi

    // Tambah spasi di antara huruf kecil→BESAR (camelCase)
    $withSpaces = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);

    // Title case
    $titled = ucwords(strtolower($withSpaces));

    // Perbaikan khusus kata tertentu
    $replacements = [
        'Utara'  => 'Utara',
        'Selatan'=> 'Selatan',
        'Timur'  => 'Timur',
        'Barat'  => 'Barat',
        'Tengah' => 'Tengah',
    ];

    return $titled;
}