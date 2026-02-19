<?php

namespace App\Services;

use App\Models\Dataset;
use App\Models\DatasetRecord;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class ExcelDatasetService
{
    /**
     * Parse Excel dan create records ke database
     * 
     * Format Excel yang diharapkan:
     * Row 1: Header (Kecamatan, Kode, Tahun, [kolom data...])
     * Row 2+: Data
     */
    public function parseAndCreateRecords(Dataset $dataset, string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            throw new \Exception('File Excel harus memiliki minimal 2 baris (header + data)');
        }

        // Ambil header (row pertama)
        $headers = array_map('trim', $rows[0]);
        
        // Identifikasi kolom wajib
        $kecamatanIndex = $this->findColumnIndex($headers, ['kecamatan', 'kabupaten_kota', 'wilayah']);
        $kodeIndex = $this->findColumnIndex($headers, ['kode', 'kode_kecamatan', 'kode_kabkota']);
        $tahunIndex = $this->findColumnIndex($headers, ['tahun', 'year']);

        if ($kecamatanIndex === false || $kodeIndex === false || $tahunIndex === false) {
            throw new \Exception('Header Excel harus memiliki kolom: Kecamatan, Kode, dan Tahun');
        }

        // Kolom data dimulai setelah kolom wajib
        $dataStartIndex = max($kecamatanIndex, $kodeIndex, $tahunIndex) + 1;
        $dataColumns = [];
        
        for ($i = $dataStartIndex; $i < count($headers); $i++) {
            if (!empty($headers[$i])) {
                $key = Str::slug($headers[$i], '_');
                $dataColumns[$key] = $headers[$i];
            }
        }

        if (empty($dataColumns)) {
            throw new \Exception('Tidak ada kolom data yang valid di Excel');
        }

        // Insert records
        $recordsCount = 0;
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            $kecamatan = $row[$kecamatanIndex] ?? null;
            $kode = $row[$kodeIndex] ?? null;
            $tahun = $row[$tahunIndex] ?? null;

            if (empty($kecamatan) || empty($tahun)) {
                continue; // Skip baris kosong
            }

            // Extract values
            $values = [];
            $nilaiUtama = 0;
            
            $colIndex = 0;
            foreach ($dataColumns as $key => $label) {
                $valueIndex = $dataStartIndex + $colIndex;
                $value = isset($row[$valueIndex]) ? (float)$row[$valueIndex] : 0;
                $values[$key] = $value;
                
                if ($colIndex === 0) {
                    $nilaiUtama = $value; // Kolom pertama sebagai nilai utama
                }
                $colIndex++;
            }

            DatasetRecord::create([
                'dataset_id' => $dataset->id,
                'kabupaten_kota' => trim($kecamatan),
                'kode_kabkota' => trim($kode),
                'kecamatan' => trim($kecamatan),
                'desa' => '-',
                'tahun' => (int)$tahun,
                'bulan' => '-',
                'satuan' => $dataset->unit,
                'values' => $values,
                'nilai_utama' => $nilaiUtama,
            ]);

            $recordsCount++;
        }

        return [
            'columns' => $dataColumns,
            'records_count' => $recordsCount,
        ];
    }

    private function findColumnIndex(array $headers, array $possibleNames): int|false
    {
        foreach ($headers as $index => $header) {
            $normalized = strtolower(trim($header));
            foreach ($possibleNames as $name) {
                if (str_contains($normalized, strtolower($name))) {
                    return $index;
                }
            }
        }
        return false;
    }
}