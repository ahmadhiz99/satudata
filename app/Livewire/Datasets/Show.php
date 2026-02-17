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
    public $activeTab = 'tabel';
    public $filterKabupaten = '';
    public $filterTahun = '';

    public function mount(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab === 'grafik') $this->dispatchChartEvent();
        if ($tab === 'peta') $this->dispatchMapEvent();
    }

    public function updatedFilterKabupaten()
    {
        $this->resetPage();
        if ($this->activeTab === 'grafik') $this->dispatchChartEvent();
        if ($this->activeTab === 'peta') $this->dispatchMapEvent();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
        if ($this->activeTab === 'grafik') $this->dispatchChartEvent();
        if ($this->activeTab === 'peta') $this->dispatchMapEvent();
    }

    // ✅ BARU: Download Excel dengan filter aktif
    public function downloadExcel(): StreamedResponse
    {
        $query = $this->dataset->records();

        if ($this->filterKabupaten) {
            $query->where('kabupaten_kota', $this->filterKabupaten);
        }
        if ($this->filterTahun) {
            $query->where('tahun', $this->filterTahun);
        }

        $data = $query->orderBy('kabupaten_kota')->orderBy('tahun')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data');

        // ── Judul utama ──
        $totalCols = count($this->dataset->columns) + 3; // kabupaten + kode + tahun + columns
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);

        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $this->dataset->title);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'B91C1C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Sub-judul filter ──
        $filterParts = [];
        if ($this->filterKabupaten) $filterParts[] = $this->filterKabupaten;
        if ($this->filterTahun)     $filterParts[] = 'Tahun ' . $this->filterTahun;
        $filterText = $filterParts ? implode(' | ', $filterParts) : 'Semua Data';

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $filterText);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Header tabel ──
        $headers = ['Kabupaten/Kota', 'Kode', 'Tahun'];
        foreach ($this->dataset->columns as $label) {
            $headers[] = $label . ' (' . $this->dataset->unit . ')';
        }

        $col = 1;
        foreach ($headers as $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '3';
            $sheet->setCellValue($cell, $header);
            $col++;
        }

        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);

        // ── Isi data ──
        $row = 4;
        foreach ($data as $i => $record) {
            $isEven = ($i % 2 === 0);
            $bgColor = $isEven ? 'F9FAFB' : 'FFFFFF';

            $sheet->setCellValue("A{$row}", $record->kabupaten_kota);
            $sheet->setCellValue("B{$row}", $record->kode_kabkota);
            $sheet->setCellValue("C{$row}", $record->tahun);

            $col = 4;
            foreach ($this->dataset->columns as $key => $label) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $value = $record->values[$key] ?? 0;
                $sheet->setCellValue("{$colLetter}{$row}", $value);
                $sheet->getStyle("{$colLetter}{$row}")->getNumberFormat()
                    ->setFormatCode('#,##0');
                $sheet->getStyle("{$colLetter}{$row}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $col++;
            }

            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
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

        // ── Auto-width kolom ──
        foreach (range(1, $totalCols) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        // ── Nama file dinamis ──
        $title = \Str::slug($this->dataset->title);
        $parts = [$title];
        if ($this->filterKabupaten) $parts[] = \Str::slug($this->filterKabupaten);
        if ($this->filterTahun)     $parts[] = $this->filterTahun;
        $filename = implode('_', $parts) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function dispatchChartEvent()
    {
        $chartData = $this->dataset->records()
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKabupaten, fn($q) => $q->where('kabupaten_kota', $this->filterKabupaten))
            ->orderBy('nilai_utama', 'desc')
            ->limit(10)
            ->get(['kabupaten_kota', 'tahun', 'nilai_utama']);

        $this->dispatch('renderChart',
            chartData: $chartData->toArray(),
            unit: $this->dataset->unit,
            filterTahun: $this->filterTahun,
            filterKabupaten: $this->filterKabupaten,
        );
    }

    private function dispatchMapEvent()
    {
        $mapData = $this->dataset->records()
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKabupaten, fn($q) => $q->where('kabupaten_kota', $this->filterKabupaten))
            ->get(['kabupaten_kota', 'kode_kabkota', 'tahun', 'nilai_utama'])
            ->keyBy('kabupaten_kota')
            ->toArray();

        $this->dispatch('renderMap',
            mapData: $mapData,
            unit: $this->dataset->unit,
            filterTahun: $this->filterTahun,
        );
    }

    public function render()
    {
        $kabupatens = $this->dataset->records()
            ->select('kabupaten_kota')->distinct()
            ->whereNotNull('kabupaten_kota')
            ->orderBy('kabupaten_kota')->pluck('kabupaten_kota');

        $tahuns = $this->dataset->records()
            ->select('tahun')->distinct()
            ->orderBy('tahun', 'desc')->pluck('tahun');

        $query = $this->dataset->records();
        if ($this->filterKabupaten) $query->where('kabupaten_kota', $this->filterKabupaten);
        if ($this->filterTahun)     $query->where('tahun', $this->filterTahun);

        $records = $query->orderBy('nilai_utama', 'desc')->paginate(10);

        $chartData = $this->dataset->records()
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKabupaten, fn($q) => $q->where('kabupaten_kota', $this->filterKabupaten))
            ->orderBy('nilai_utama', 'desc')->limit(10)->get();

        return view('livewire.datasets.show', compact('records', 'kabupatens', 'tahuns', 'chartData'));
    }
}