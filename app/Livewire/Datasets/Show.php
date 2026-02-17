<?php

namespace App\Livewire\Datasets;

use App\Models\Dataset;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Show extends Component
{
    use WithPagination;

    public Dataset $dataset;
    public $activeTab    = 'tabel';
    public $filterKecamatan = '';   // ← ganti dari filterKabupaten
    public $filterTahun  = '';

    public function mount(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab === 'grafik') $this->dispatchChartEvent();
        if ($tab === 'peta')   $this->dispatchMapEvent();
    }

    public function updatedFilterKecamatan()
    {
        $this->resetPage();
        if ($this->activeTab === 'grafik') $this->dispatchChartEvent();
        if ($this->activeTab === 'peta')   $this->dispatchMapEvent();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
        if ($this->activeTab === 'grafik') $this->dispatchChartEvent();
        if ($this->activeTab === 'peta')   $this->dispatchMapEvent();
    }

    // ─────────────────────────────────────────────────────────────
    // Download Excel (ikut filter aktif)
    // ─────────────────────────────────────────────────────────────
    public function downloadExcel(): StreamedResponse
    {
        $data = $this->dataset->records()
            ->when($this->filterKecamatan, fn($q) => $q->where('kabupaten_kota', $this->filterKecamatan))
            ->when($this->filterTahun,     fn($q) => $q->where('tahun', $this->filterTahun))
            ->orderBy('kabupaten_kota')
            ->orderBy('tahun')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        $totalCols = count($this->dataset->columns) + 3; // kecamatan + kode + tahun + kolom data
        $lastCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);

        // ── Baris 1: Judul dataset ──
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $this->dataset->title . ' — Kabupaten Barito Utara');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'B91C1C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Baris 2: Info filter ──
        $filterParts = [];
        if ($this->filterKecamatan) $filterParts[] = 'Kecamatan: ' . $this->filterKecamatan;
        if ($this->filterTahun)     $filterParts[] = 'Tahun: ' . $this->filterTahun;
        $filterText = $filterParts ? implode(' | ', $filterParts) : 'Semua Kecamatan — Semua Tahun';

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $filterText);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Baris 3: Header kolom ──
        $headers = ['Kecamatan', 'Kode', 'Tahun'];
        foreach ($this->dataset->columns as $label) {
            $headers[] = $label . ' (' . $this->dataset->unit . ')';
        }

        foreach ($headers as $i => $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . '3';
            $sheet->setCellValue($cell, $header);
        }

        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);

        // ── Baris 4+: Isi data ──
        $row = 4;
        foreach ($data as $i => $record) {
            $bgColor = ($i % 2 === 0) ? 'F9FAFB' : 'FFFFFF';

            $sheet->setCellValue("A{$row}", $record->kabupaten_kota);
            $sheet->setCellValue("B{$row}", $record->kode_kabkota);
            $sheet->setCellValue("C{$row}", $record->tahun);

            $col = 4;
            foreach ($this->dataset->columns as $key => $label) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $value     = $record->values[$key] ?? 0;
                $sheet->setCellValue("{$colLetter}{$row}", $value);
                $sheet->getStyle("{$colLetter}{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("{$colLetter}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $col++;
            }

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]],
            ]);

            $row++;
        }

        // ── Baris total ──
        $sheet->setCellValue("A{$row}", 'TOTAL RECORDS');
        $sheet->setCellValue("C{$row}", $data->count());
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF2F2']],
        ]);

        // ── Auto-width ──
        foreach (range(1, $totalCols) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        // ── Nama file ──
        $parts = [\Str::slug($this->dataset->title)];
        if ($this->filterKecamatan) $parts[] = \Str::slug($this->filterKecamatan);
        if ($this->filterTahun)     $parts[] = $this->filterTahun;
        $filename = implode('_', $parts) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────
    private function dispatchChartEvent(): void
    {
        $chartData = $this->dataset->records()
            ->when($this->filterTahun,     fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKecamatan, fn($q) => $q->where('kabupaten_kota', $this->filterKecamatan))
            ->orderBy('nilai_utama', 'desc')
            ->limit(10)
            ->get(['kabupaten_kota', 'tahun', 'nilai_utama']);

        $this->dispatch('renderChart',
            chartData:      $chartData->toArray(),
            unit:           $this->dataset->unit,
            filterTahun:    $this->filterTahun,
            filterKecamatan: $this->filterKecamatan,
        );
    }

    private function dispatchMapEvent(): void
    {
        $mapData = $this->dataset->records()
            ->when($this->filterTahun,     fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKecamatan, fn($q) => $q->where('kabupaten_kota', $this->filterKecamatan))
            ->get(['kabupaten_kota', 'kode_kabkota', 'tahun', 'nilai_utama'])
            ->keyBy('kabupaten_kota')
            ->toArray();

        $this->dispatch('renderMap',
            mapData:     $mapData,
            unit:        $this->dataset->unit,
            filterTahun: $this->filterTahun,
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────────────
    public function render()
    {
        // Daftar kecamatan unik untuk dropdown filter
        $kecamatans = $this->dataset->records()
            ->select('kabupaten_kota')->distinct()
            ->whereNotNull('kabupaten_kota')
            ->orderBy('kabupaten_kota')
            ->pluck('kabupaten_kota');

        $tahuns = $this->dataset->records()
            ->select('tahun')->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $records = $this->dataset->records()
            ->when($this->filterKecamatan, fn($q) => $q->where('kabupaten_kota', $this->filterKecamatan))
            ->when($this->filterTahun,     fn($q) => $q->where('tahun', $this->filterTahun))
            ->orderBy('kabupaten_kota')
            ->orderBy('tahun')
            ->paginate(10);

        $chartData = $this->dataset->records()
            ->when($this->filterTahun,     fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKecamatan, fn($q) => $q->where('kabupaten_kota', $this->filterKecamatan))
            ->orderBy('nilai_utama', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.datasets.show', compact('records', 'kecamatans', 'tahuns', 'chartData'));
    }
}