<?php

namespace App\Livewire\Datasektoral;

use App\Models\DatasetUpload;
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

    public DatasetUpload $dataset;

    // Dataset yang ter-link (jika extract_to_db = true)
    public ?Dataset $linkedDataset = null;

    public $activeTab   = 'info';
    public $filterTahun = '';
    public $search      = '';

    public function mount(DatasetUpload $dataset): void
    {
        $this->dataset = $dataset;

        // Cek apakah ada dataset yang ter-ekstrak dari upload ini
        $this->linkedDataset = Dataset::where('dataset_upload_id', $dataset->id)->first();

        // Increment view count (throttle per IP per jam)
        $sessionKey = 'viewed_dataset_upload_' . $dataset->id . '_' . request()->ip();
        if (!cache()->has($sessionKey)) {
            $dataset->increment('view_count');
            cache()->put($sessionKey, true, now()->addHour());
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab === 'grafik' && $this->linkedDataset) {
            $this->dispatchChartEvent();
        }
    }

    public function updatedFilterTahun(): void
    {
        $this->resetPage();
        if ($this->activeTab === 'grafik' && $this->linkedDataset) {
            $this->dispatchChartEvent();
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ─────────────────────────────────────────────────────────────
    // Download file Excel asli
    // ─────────────────────────────────────────────────────────────
    public function download(): mixed
    {
        $filePath = storage_path('app/public/' . $this->dataset->file_path);

        if (!file_exists($filePath)) {
            session()->flash('error', 'File tidak ditemukan!');
            return null;
        }

        return response()->download($filePath, $this->dataset->file_name);
    }

    // ─────────────────────────────────────────────────────────────
    // Download Excel dari data di database (terfilter)
    // ─────────────────────────────────────────────────────────────
    public function downloadExcel(): StreamedResponse
    {
        abort_unless($this->linkedDataset, 404);

        $columns = $this->linkedDataset->columns ?? [];
        $data    = $this->baseQuery()->orderBy('id')->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        $totalCols = count($columns) + 1;
        $lastCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);

        // Baris 1: Judul
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $this->linkedDataset->title);
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Baris 2: Filter info
        $filterText = $this->filterTahun ? 'Tahun: ' . $this->filterTahun : 'Semua Tahun';
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $filterText);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Baris 3: Header kolom
        $headers = ['No'];
        foreach ($columns as $slug => $label) {
            $headers[] = $label . ($this->linkedDataset->unit ? ' (' . $this->linkedDataset->unit . ')' : '');
        }
        foreach ($headers as $i => $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . '3';
            $sheet->setCellValue($cell, $header);
        }
        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);

        // Baris 4+: Data
        $row = 4;
        foreach ($data as $i => $record) {
            $bgColor = ($i % 2 === 0) ? 'F8FAFC' : 'FFFFFF';
            $values  = is_array($record->values) ? $record->values : json_decode($record->values, true);

            $sheet->setCellValue("A{$row}", $i + 1);
            $col = 2;
            foreach ($columns as $slug => $label) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $value     = $values[$slug] ?? '';
                $sheet->setCellValue("{$colLetter}{$row}", $value);
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

        // Baris total
        $sheet->setCellValue("A{$row}", 'TOTAL: ' . $data->count() . ' baris');
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
        ]);

        foreach (range(1, $totalCols) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        $parts    = [\Str::slug($this->linkedDataset->title)];
        if ($this->filterTahun) $parts[] = $this->filterTahun;
        $filename = implode('_', $parts) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Query dasar records (hanya jika linkedDataset ada)
    // ─────────────────────────────────────────────────────────────
    private function baseQuery()
    {
        abort_unless($this->linkedDataset, 404);

        return $this->linkedDataset->records()
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->search, fn($q) =>
                $q->whereRaw("LOWER(CAST(values AS TEXT)) LIKE ?", ['%' . strtolower($this->search) . '%'])
            );
    }

    // ─────────────────────────────────────────────────────────────
    // Chart dispatch
    // ─────────────────────────────────────────────────────────────
    private function dispatchChartEvent(): void
    {
        $columns  = $this->linkedDataset->columns ?? [];
        // Kolom pertama sebagai label sumbu X
        $labelKey = array_key_first($columns);

        $chartData = $this->baseQuery()
            ->orderBy('nilai_utama', 'desc')
            ->limit(15)
            ->get(['values', 'tahun', 'nilai_utama']);

        $mapped = $chartData->map(function ($record) use ($labelKey) {
            $values = is_array($record->values) ? $record->values : json_decode($record->values, true);
            return [
                'label'       => $labelKey ? ($values[$labelKey] ?? '-') : '-',
                'nilai_utama' => $record->nilai_utama,
                'tahun'       => $record->tahun,
            ];
        });

        $this->dispatch('renderChart',
            chartData: $mapped->toArray(),
            unit:      $this->linkedDataset->unit ?? '',
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────────────
    public function render()
    {
        $columns   = $this->linkedDataset?->columns ?? [];
        $tahuns    = [];
        $records   = null;
        $chartData = collect();

        if ($this->linkedDataset) {
            $tahuns = $this->linkedDataset->records()
                ->select('tahun')->distinct()
                ->whereNotNull('tahun')
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            $records = $this->baseQuery()
                ->orderBy('id')
                ->paginate(15);

            $chartData = $this->baseQuery()
                ->orderBy('nilai_utama', 'desc')
                ->limit(15)
                ->get();
        }

        return view('livewire.datasektoral.show', compact('columns', 'tahuns', 'records', 'chartData'))
            ->layout('components.layouts.public');
    }
}