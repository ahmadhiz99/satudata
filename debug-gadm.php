<?php

echo "=== Debug GADM Structure ===\n\n";

$sourceFile = 'gadm41_IDN_2.json';

if (!file_exists($sourceFile)) {
    die("File tidak ditemukan\n");
}

echo "Loading file...\n";
$geojson = json_decode(file_get_contents($sourceFile), true);

echo "Total features: " . count($geojson['features']) . "\n\n";

// Ambil 3 feature pertama untuk melihat struktur
echo "=== Sample Features ===\n\n";

for ($i = 0; $i < min(3, count($geojson['features'])); $i++) {
    $feature = $geojson['features'][$i];
    
    echo "Feature #" . ($i + 1) . ":\n";
    echo "Properties:\n";
    
    foreach ($feature['properties'] as $key => $value) {
        if (is_string($value) || is_numeric($value)) {
            echo "  $key: $value\n";
        }
    }
    
    echo "\n" . str_repeat('-', 50) . "\n\n";
}

// Cari feature yang mengandung "Kalimantan" atau "Tengah"
echo "=== Mencari Features Kalimantan ===\n\n";

$found = 0;
foreach ($geojson['features'] as $index => $feature) {
    $props = $feature['properties'];
    
    // Cek semua properties yang mengandung kata Kalimantan atau Tengah
    foreach ($props as $key => $value) {
        if (is_string($value) && 
            (stripos($value, 'Kalimantan') !== false || stripos($value, 'Tengah') !== false)) {
            
            echo "Found at index $index:\n";
            echo "  Property '$key': $value\n";
            
            // Tampilkan semua properties dari feature ini
            echo "  All properties:\n";
            foreach ($props as $k => $v) {
                if (is_string($v) || is_numeric($v)) {
                    echo "    $k: $v\n";
                }
            }
            
            echo "\n";
            $found++;
            
            if ($found >= 5) break 2; // Stop setelah 5 contoh
        }
    }
}

if ($found === 0) {
    echo "Tidak ditemukan features dengan kata Kalimantan atau Tengah\n";
    echo "\nCoba cari 'Palangka' atau 'Kapuas':\n\n";
    
    foreach ($geojson['features'] as $index => $feature) {
        $props = $feature['properties'];
        
        foreach ($props as $key => $value) {
            if (is_string($value) && 
                (stripos($value, 'Palangka') !== false || stripos($value, 'Kapuas') !== false)) {
                
                echo "Found at index $index:\n";
                foreach ($props as $k => $v) {
                    if (is_string($v) || is_numeric($v)) {
                        echo "  $k: $v\n";
                    }
                }
                echo "\n";
                break 2;
            }
        }
    }
}

echo "\n=== Unique Property Keys ===\n\n";
$allKeys = [];
foreach ($geojson['features'] as $feature) {
    $allKeys = array_merge($allKeys, array_keys($feature['properties']));
}
$uniqueKeys = array_unique($allKeys);
sort($uniqueKeys);

foreach ($uniqueKeys as $key) {
    echo "  - $key\n";
}