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
    public $activeTab   = 'tabel';
    public $filterTahun = '';
    public $search      = '';

    // Kolom yang tersedia dari dataset (diambil dari $dataset->columns)
    // Digunakan untuk render tabel & Excel secara dinamis
    protected $columns = [];

    public function mount(Dataset $dataset): void
    {
        $this->dataset = $dataset;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab === 'grafik') $this->dispatchChartEvent();
    }

    public function updatedFilterTahun(): void
    {
        $this->resetPage();
        if ($this->activeTab === 'grafik') $this->dispatchChartEvent();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ─────────────────────────────────────────────────────────────
    // Query dasar dengan filter aktif
    // ─────────────────────────────────────────────────────────────
    private function baseQuery()
    {
        return $this->dataset->records()
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->search, function ($q) {
                // Cari di semua nilai JSON values (cast ke string)
                $q->whereRaw("LOWER(CAST(values AS TEXT)) LIKE ?", ['%' . strtolower($this->search) . '%']);
            });
    }

    // ─────────────────────────────────────────────────────────────
    // Download Excel — kolom dinamis dari dataset->columns
    // ─────────────────────────────────────────────────────────────
    public function downloadExcel(): StreamedResponse
    {
        $columns = $this->dataset->columns ?? []; // array key slug dari header Excel
        $data    = $this->baseQuery()
            ->orderBy('id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        $totalCols = count($columns) + 1; // +1 untuk kolom No
        $lastCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);

        // ── Baris 1: Judul ──
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $this->dataset->title);
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'B91C1C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Baris 2: Info filter ──
        $filterText = $this->filterTahun ? 'Tahun: ' . $this->filterTahun : 'Semua Tahun';
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $filterText);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Baris 3: Header kolom — No + semua kolom dari dataset->columns ──
        $headers = ['No'];
        foreach ($columns as $col) {
            // Tampilkan label yang lebih rapi (ucwords dari slug)
            $headers[] = ucwords(str_replace('_', ' ', $col))
                . ($this->dataset->unit ? ' (' . $this->dataset->unit . ')' : '');
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

        // ── Baris 4+: Data dinamis dari values JSON ──
        $row = 4;
        foreach ($data as $i => $record) {
            $bgColor = ($i % 2 === 0) ? 'F9FAFB' : 'FFFFFF';
            $values  = is_array($record->values) ? $record->values : json_decode($record->values, true);

            $sheet->setCellValue("A{$row}", $i + 1); // No

            $col = 2;
            foreach ($columns as $key) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $value     = $values[$key] ?? '';

                $sheet->setCellValue("{$colLetter}{$row}", $value);

                // Format angka jika numerik
                if (is_numeric($value)) {
                    $sheet->getStyle("{$colLetter}{$row}")->getNumberFormat()->setFormatCode('#,##0.##');
                    $sheet->getStyle("{$colLetter}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                $col++;
            }

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]],
            ]);

            $row++;
        }

        // ── Baris total ──
        $sheet->setCellValue("A{$row}", 'TOTAL: ' . $data->count() . ' baris');
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF2F2']],
        ]);

        // ── Auto-width ──
        foreach (range(1, $totalCols) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        // ── Nama file ──
        $parts    = [\Str::slug($this->dataset->title)];
        if ($this->filterTahun) $parts[] = $this->filterTahun;
        $filename = implode('_', $parts) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Chart — ambil nilai_utama untuk top 10
    // ─────────────────────────────────────────────────────────────
    private function dispatchChartEvent(): void
    {
        $columns   = $this->dataset->columns ?? [];
        $labelKey  = $columns[0] ?? null; // kolom pertama sebagai label

        $chartData = $this->baseQuery()
            ->orderBy('nilai_utama', 'desc')
            ->limit(10)
            ->get(['values', 'tahun', 'nilai_utama']);

        // Petakan ke format [{label, value}]
        $mapped = $chartData->map(function ($record) use ($labelKey) {
            $values = is_array($record->values) ? $record->values : json_decode($record->values, true);
            return [
                'label'      => $labelKey ? ($values[$labelKey] ?? '-') : '-',
                'nilai_utama'=> $record->nilai_utama,
                'tahun'      => $record->tahun,
            ];
        });

        $this->dispatch('renderChart',
            chartData: $mapped->toArray(),
            unit:      $this->dataset->unit,
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────────────
    public function render()
    {
        $columns = $this->dataset->columns ?? [];

        $tahuns = $this->dataset->records()
            ->select('tahun')->distinct()
            ->whereNotNull('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $records = $this->baseQuery()
            ->orderBy('id')
            ->paginate(15);

        $chartData = $this->baseQuery()
            ->orderBy('nilai_utama', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.datasets.show', compact('records', 'tahuns', 'chartData', 'columns'));
    }
}