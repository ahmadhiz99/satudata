<?php

namespace App\Livewire\Management\Datasets;

use App\Models\Dataset;
use App\Models\DatasetRecord;
use App\Models\DatasetUpload;
use App\Models\Organization;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $category_id;
    public $organization_id;
    public $description;
    public $unit;
    public $frequency;
    public $start_year;
    public $end_year;
    public $visualize_table = false; 
    public $visualize_chart = false;
    public $visualize_map = false;

    public $excel_file;

    public bool $extract_to_db = false;
    public int $start_row = 5;
    public string $start_col = 'A';

    public $organizations = [];
    public $categories = [];

    protected function rules(): array
    {
        $rules = [
            'title'           => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'category_id'     => 'required|exists:categories,id',
            'excel_file'      => 'required|file|mimes:xlsx,xls|max:10240',
        ];

        if ($this->extract_to_db) {
            $rules['start_row'] = 'required|integer|min:1';
            $rules['start_col'] = 'required|string|regex:/^[A-Za-z]+$/|max:3';
        }

        return $rules;
    }

    protected $messages = [
        'title.required'           => 'Nama dataset harus diisi',
        'organization_id.required' => 'OPD harus dipilih',
        'category_id.required'     => 'Sektor harus dipilih',
        'excel_file.required'      => 'File Excel harus diupload',
        'excel_file.mimes'         => 'File harus berformat .xlsx atau .xls',
        'excel_file.max'           => 'Ukuran file maksimal 10MB',
        'start_row.required'       => 'Baris awal harus diisi',
        'start_row.integer'        => 'Baris awal harus berupa angka',
        'start_row.min'            => 'Baris awal minimal 1',
        'start_col.required'       => 'Kolom awal harus diisi',
        'start_col.regex'          => 'Kolom awal harus berupa huruf (contoh: A, B, AA)',
    ];

    public function mount(): void
    {
        $this->organizations = Organization::where('is_active', true)->get();
        $this->categories    = Category::where('is_active', true)->get();
    }

    // Livewire lifecycle hook — dipanggil otomatis saat $extract_to_db berubah
    // Tidak perlu parameter, cukup reset ke default
    public function updatedExtractToDb(): void
    {
        if ($this->extract_to_db) {
            $this->start_row = 5;
            $this->start_col = 'A';
        }
    }

    public function save(): mixed
    {
        $this->validate();

        $filename = Str::slug($this->title) . '_' . time() . '.' . $this->excel_file->getClientOriginalExtension();
        $path     = $this->excel_file->storeAs('dataset_uploads', $filename, 'public');

        // Selalu simpan ke dataset_uploads
        $upload = DatasetUpload::create([
            'title'           => $this->title,
            'organization_id' => $this->organization_id,
            'category_id'     => $this->category_id,
            'file_path'       => $path,
            'file_name'       => $this->excel_file->getClientOriginalName(),
            'file_size'       => $this->excel_file->getSize(),
            'extract_to_db'   => $this->extract_to_db,
            'start_row'       => $this->extract_to_db ? $this->start_row : null,
            'start_col'       => $this->extract_to_db ? strtoupper($this->start_col) : null,
        ]);

        // Jika dicentang, ekstrak ke dataset & dataset_records
        if ($this->extract_to_db) {
            // Ambil full path file yang sudah tersimpan
            $fullPath = storage_path('app/public/' . $path);
            $this->extractToDatabase($upload, $fullPath);
        }

        session()->flash('success', 'Dataset berhasil diupload!' . ($this->extract_to_db ? ' Data berhasil diekstrak ke database.' : ''));

        return redirect()->route('management.datasets.index');
    }

    /**
     * Ekstrak data dari file Excel ke tabel datasets & dataset_records.
     * Baris header diambil dari baris tepat sebelum start_row.
     * Setiap baris data → 1 DatasetRecord dengan semua kolom dikemas ke JSON values.
     */
    private function extractToDatabase(DatasetUpload $upload, string $filePath): void
{
    $spreadsheet = IOFactory::load($filePath);
    $sheet       = $spreadsheet->getActiveSheet();

    $startColIndex   = Coordinate::columnIndexFromString(strtoupper($this->start_col));
    $highestColIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
    $highestRow      = $sheet->getHighestRow();

    // ── Baris header = start_row ──────────────────────────────────
    // Key: index kolom (int) → slug header untuk key JSON values
    // Val: label asli untuk disimpan di dataset->columns
    $headersByColIndex = []; // [colIndex => slug]   → key di values JSON
    $columnsForDataset = []; // [slug => labelAsli]  → disimpan di dataset->columns

    for ($col = $startColIndex; $col <= $highestColIndex; $col++) {
        $colLetter  = Coordinate::stringFromColumnIndex($col);
        $rawHeader  = trim((string) $sheet->getCell($colLetter . $this->start_row)->getValue());

        // Jika header kosong, pakai nama kolom Excel sebagai fallback
        $slug  = $rawHeader !== '' ? Str::slug($rawHeader, '_') : strtolower($colLetter);
        $label = $rawHeader !== '' ? $rawHeader : strtoupper($colLetter);

        $headersByColIndex[$col] = $slug;
        $columnsForDataset[$slug] = $label;
    }

    // ── Buat Dataset ──────────────────────────────────────────────
    $visualizes = [];
    if ($this->visualize_table) {
        array_push($visualizes, 'table');
    }
    if ($this->visualize_chart) {
        array_push($visualizes, 'chart');
    }
    if ($this->visualize_map) {
        array_push($visualizes, 'map');
    }
    // visualisasi dari array ke string dengan separator koma, contoh: "table,chart"
    $visualize_types = implode(',', $visualizes);

    $dataset = Dataset::create([
        'title'             => $this->title,
        'slug'              => Str::slug($this->title),
        'description'       => $this->description,
        'category_id'       => $this->category_id,
        'organization_id'   => $this->organization_id,
        'columns'           => $columnsForDataset, // {"nama_kolom": "Label Asli", ...}
        'dataset_upload_id' => $upload->id,
        'unit'              => $this->unit,
        'frequency'         => $this->frequency,
        'start_year'        => $this->start_year,
        'end_year'          => $this->end_year,
        'status'            => 'published',
        'visualize_types'   => $visualize_types,
        'published_at'      => now(),
    ]);

    // ── Baca data mulai baris SETELAH header ──────────────────────
    $dataStartRow = $this->start_row + 1;
    $records      = [];

    for ($row = $dataStartRow; $row <= $highestRow; $row++) {
        $values     = [];
        $isEmptyRow = true;

        for ($col = $startColIndex; $col <= $highestColIndex; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $cellValue = $sheet->getCell($colLetter . $row)->getFormattedValue();
            $key       = $headersByColIndex[$col]; // slug dari header kolom ini

            // Nilai numerik: cast ke float agar tidak ada floating point string aneh
            if (is_numeric($cellValue) && $cellValue !== '') {
                $values[$key] = (float) $cellValue;
            } else {
                $values[$key] = $cellValue !== '' ? $cellValue : null;
            }

            if ($cellValue !== '') {
                $isEmptyRow = false;
            }
        }

        if ($isEmptyRow) {
            continue;
        }

        $records[] = [
            'dataset_id'  => $dataset->id,
            'values'      => json_encode($values),
            'nilai_utama' => $this->resolveNilaiUtama($values),
            'tahun'       => $this->start_year ?? now()->year,
            'bulan'       => null,
            'satuan'      => $this->unit,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];

        if (count($records) >= 500) {
            DatasetRecord::insert($records);
            $records = [];
        }
    }

    if (!empty($records)) {
        DatasetRecord::insert($records);
    }
}

    /**
     * Coba cari nilai numerik utama dari values untuk keperluan sorting/chart.
     * Ambil value numerik pertama yang ditemukan.
     */
    private function resolveNilaiUtama(array $values): ?float
    {
        foreach ($values as $value) {
            if (is_numeric($value) && $value !== null) {
                return (float) $value;
            }
        }

        return null;
    }

    public function render()
    {
        return view('livewire.management.datasets.create');
    }
}