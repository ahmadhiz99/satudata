<?php

echo "=== GADM Kalteng Filter Script (Fixed) ===\n\n";

$sourceFile = 'gadm41_IDN_2.json';

if (!file_exists($sourceFile)) {
    die("❌ Error: File '$sourceFile' tidak ditemukan.\n");
}

echo "📂 Loading GeoJSON dari: $sourceFile\n";
echo "⏳ Mohon tunggu...\n\n";

$geojson = json_decode(file_get_contents($sourceFile), true);

if (!$geojson) {
    die("❌ Error: Gagal parse JSON.\n");
}

echo "✅ File berhasil dimuat\n";
echo "📊 Total features di Indonesia: " . count($geojson['features']) . "\n\n";

echo "🔍 Filtering Kalimantan Tengah...\n";

$kaltengFeatures = [];

foreach ($geojson['features'] as $feature) {
    $provinsi = $feature['properties']['NAME_1'] ?? '';
    
    // ✅ FILTER: Cari "KalimantanTengah" (tanpa spasi!)
    if ($provinsi === 'KalimantanTengah') {
        $kabupaten = $feature['properties']['NAME_2'] ?? 'Unknown';
        $kode = $feature['properties']['CC_2'] ?? '-';
        
        // Format nama dengan spasi dan prefix Kab./Kota
        $namaFormatted = formatNamaKabupaten($kabupaten);
        
        $newFeature = [
            'type' => 'Feature',
            'properties' => [
                'name' => $namaFormatted,
                'kode' => $kode,
                'original_name' => $kabupaten,
            ],
            'geometry' => $feature['geometry']
        ];
        
        $kaltengFeatures[] = $newFeature;
        
        echo "  ✓ $namaFormatted (Kode: $kode)\n";
    }
}

echo "\n📍 Total kabupaten/kota ditemukan: " . count($kaltengFeatures) . "\n\n";

if (count($kaltengFeatures) === 0) {
    die("❌ Error: Tidak ada kabupaten ditemukan!\n");
}

// Buat GeoJSON baru
$kaltengGeojson = [
    'type' => 'FeatureCollection',
    'features' => $kaltengFeatures
];

// Buat folder jika belum ada
$outputDir = 'public/geojson';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Simpan hasil
$outputFile = $outputDir . '/kalteng.json';
file_put_contents($outputFile, json_encode($kaltengGeojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "💾 File disimpan: $outputFile\n";
echo "📦 Ukuran file: " . round(filesize($outputFile) / 1024, 2) . " KB\n\n";

echo "✅✅✅ SELESAI!\n\n";

echo "Daftar Kabupaten/Kota:\n";
echo str_repeat('-', 60) . "\n";
foreach ($kaltengFeatures as $feature) {
    echo sprintf("%-40s Kode: %s\n", 
        $feature['properties']['name'], 
        $feature['properties']['kode']
    );
}
echo str_repeat('-', 60) . "\n";

echo "\n🎯 Next: Refresh browser dan test peta!\n";

// ===== HELPER FUNCTION =====

function formatNamaKabupaten($name) {
    // Mapping nama dari GADM (tanpa spasi) ke format yang benar
    $mapping = [
        'KotawaringinTimur' => 'Kab. Kotawaringin Timur',
        'Kapuas' => 'Kab. Kapuas',
        'BaritoSelatan' => 'Kab. Barito Selatan',
        'BaritoUtara' => 'Kab. Barito Utara',
        'KotawaringinBarat' => 'Kab. Kotawaringin Barat',
        'Seruyan' => 'Kab. Seruyan',
        'Sukamara' => 'Kab. Sukamara',
        'Lamandau' => 'Kab. Lamandau',
        'Katingan' => 'Kab. Katingan',
        'PulangPisau' => 'Kab. Pulang Pisau',
        'MurungRaya' => 'Kab. Murung Raya',
        'BaritoTimur' => 'Kab. Barito Timur',
        'GunungMas' => 'Kab. Gunung Mas',
        'PalangkaRaya' => 'Kota Palangkaraya',
        'Palangkaraya' => 'Kota Palangkaraya',
    ];
    
    // Cek mapping exact
    if (isset($mapping[$name])) {
        return $mapping[$name];
    }
    
    // Fallback: tambah spasi di capital letters dan prefix Kab.
    $nameWithSpaces = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
    
    if (stripos($nameWithSpaces, 'Palangka') !== false) {
        return 'Kota ' . $nameWithSpaces;
    }
    
    return 'Kab. ' . $nameWithSpaces;
}