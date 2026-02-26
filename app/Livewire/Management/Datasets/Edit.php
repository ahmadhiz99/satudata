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

class Edit extends Component
{
    use WithFileUploads;

    // Simpan ID saja — hindari Eloquent model sebagai public property
    public int $uploadId;

    public $title;
    public $category_id;
    public $organization_id;
    public $description;
    public $unit;
    public $frequency;
    public $start_year;
    public $end_year;
    public bool $visualize_table = false;
    public bool $visualize_chart = false;
    public bool $visualize_map   = false;

    public $excel_file;

    public bool $extract_to_db = false;
    public bool $re_extract    = false;
    public int  $start_row     = 5;
    public string $start_col   = 'A';

    protected function rules(): array
    {
        $rules = [
            'title'           => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'category_id'     => 'required|exists:categories,id',
            'excel_file'      => 'nullable|file|mimes:xlsx,xls|max:10240',
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
        'excel_file.mimes'         => 'File harus berformat .xlsx atau .xls',
        'excel_file.max'           => 'Ukuran file maksimal 10MB',
        'start_row.required'       => 'Baris awal harus diisi',
        'start_row.integer'        => 'Baris awal harus berupa angka',
        'start_row.min'            => 'Baris awal minimal 1',
        'start_col.required'       => 'Kolom awal harus diisi',
        'start_col.regex'          => 'Kolom awal harus berupa huruf (contoh: A, B, AA)',
    ];

    public function mount(int $id): void
    {
        $upload = DatasetUpload::with('dataset')->findOrFail($id);

        $this->uploadId        = $upload->id;
        $this->title           = $upload->title;
        $this->organization_id = $upload->organization_id;
        $this->category_id     = $upload->category_id;
        $this->extract_to_db   = (bool) $upload->extract_to_db;
        $this->start_row       = $upload->start_row ?? 5;
        $this->start_col       = $upload->start_col ?? 'A';

        $linkedDataset = $upload->dataset;
        if ($linkedDataset) {
            $this->description = $linkedDataset->description;
            $this->unit        = $linkedDataset->unit;
            $this->frequency   = $linkedDataset->frequency;
            $this->start_year  = $linkedDataset->start_year;
            $this->end_year    = $linkedDataset->end_year;

            $vt = $linkedDataset->visualize_types ?? '';
            $this->visualize_table = str_contains($vt, 'table');
            $this->visualize_chart = str_contains($vt, 'chart');
            $this->visualize_map   = str_contains($vt, 'map');
        }
    }

    public function updatedExtractToDb(): void
    {
        if (!$this->extract_to_db) {
            $this->re_extract = false;
        }
    }

    public function save(): mixed
    {
        $this->validate();

        $upload = DatasetUpload::with('dataset')->findOrFail($this->uploadId);

        // Ganti file jika ada upload baru
        if ($this->excel_file) {
            if ($upload->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($upload->file_path);
            }
            $filename       = Str::slug($this->title) . '_' . time() . '.' . $this->excel_file->getClientOriginalExtension();
            $path           = $this->excel_file->storeAs('dataset_uploads', $filename, 'public');
            $upload->file_path = $path;
            $upload->file_name = $this->excel_file->getClientOriginalName();
            $upload->file_size = $this->excel_file->getSize();
        }

        $upload->update([
            'title'           => $this->title,
            'organization_id' => $this->organization_id,
            'category_id'     => $this->category_id,
            'file_path'       => $upload->file_path,
            'file_name'       => $upload->file_name,
            'file_size'       => $upload->file_size,
            'extract_to_db'   => $this->extract_to_db,
            'start_row'       => $this->extract_to_db ? $this->start_row : null,
            'start_col'       => $this->extract_to_db ? strtoupper($this->start_col) : null,
        ]);

        $visualizes = [];
        if ($this->visualize_table) $visualizes[] = 'table';
        if ($this->visualize_chart) $visualizes[] = 'chart';
        if ($this->visualize_map)   $visualizes[] = 'map';
        $visualize_types = implode(',', $visualizes);

        $linkedDataset = $upload->dataset;

        if ($this->extract_to_db) {
            if ($linkedDataset) {
                $linkedDataset->update([
                    'title'           => $this->title,
                    'description'     => $this->description,
                    'category_id'     => $this->category_id,
                    'organization_id' => $this->organization_id,
                    'unit'            => $this->unit,
                    'frequency'       => $this->frequency,
                    'start_year'      => $this->start_year,
                    'end_year'        => $this->end_year,
                    'visualize_types' => $visualize_types,
                ]);

                if ($this->re_extract) {
                    $linkedDataset->records()->delete();
                    $filePath = storage_path('app/public/' . $upload->file_path);
                    $this->extractToDatabase($upload, $filePath, $linkedDataset);
                }
            } else {
                $filePath = storage_path('app/public/' . $upload->file_path);
                $this->extractToDatabase($upload, $filePath);
            }
        } else {
            if ($linkedDataset) {
                $linkedDataset->records()->delete();
                $linkedDataset->delete();
            }
        }

        session()->flash('success', 'Dataset berhasil diperbarui!');
        return redirect()->route('management.datasets.index');
    }

    private function extractToDatabase(DatasetUpload $upload, string $filePath, ?Dataset $existingDataset = null): void
    {
        $spreadsheet     = IOFactory::load($filePath);
        $sheet           = $spreadsheet->getActiveSheet();
        $startColIndex   = Coordinate::columnIndexFromString(strtoupper($this->start_col));
        $highestColIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $highestRow      = $sheet->getHighestRow();

        $headersByColIndex = [];
        $columnsForDataset = [];

        for ($col = $startColIndex; $col <= $highestColIndex; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $rawHeader = trim((string) $sheet->getCell($colLetter . $this->start_row)->getValue());
            $slug      = $rawHeader !== '' ? Str::slug($rawHeader, '_') : strtolower($colLetter);
            $label     = $rawHeader !== '' ? $rawHeader : strtoupper($colLetter);

            $headersByColIndex[$col]  = $slug;
            $columnsForDataset[$slug] = $label;
        }

        $visualizes = [];
        if ($this->visualize_table) $visualizes[] = 'table';
        if ($this->visualize_chart) $visualizes[] = 'chart';
        if ($this->visualize_map)   $visualizes[] = 'map';
        $visualize_types = implode(',', $visualizes);

        if ($existingDataset) {
            $existingDataset->update([
                'columns'         => $columnsForDataset,
                'visualize_types' => $visualize_types,
            ]);
            $dataset = $existingDataset;
        } else {
            $dataset = Dataset::create([
                'title'             => $this->title,
                'slug'              => Str::slug($this->title),
                'description'       => $this->description,
                'category_id'       => $this->category_id,
                'organization_id'   => $this->organization_id,
                'columns'           => $columnsForDataset,
                'dataset_upload_id' => $upload->id,
                'unit'              => $this->unit,
                'frequency'         => $this->frequency,
                'start_year'        => $this->start_year,
                'end_year'          => $this->end_year,
                'status'            => 'published',
                'visualize_types'   => $visualize_types,
                'published_at'      => now(),
            ]);
        }

        $dataStartRow = $this->start_row + 1;
        $records      = [];

        for ($row = $dataStartRow; $row <= $highestRow; $row++) {
            $values     = [];
            $isEmptyRow = true;

            for ($col = $startColIndex; $col <= $highestColIndex; $col++) {
                $colLetter = Coordinate::stringFromColumnIndex($col);
                $cellValue = $sheet->getCell($colLetter . $row)->getFormattedValue();
                $key       = $headersByColIndex[$col];

                if (is_numeric($cellValue) && $cellValue !== '') {
                    $values[$key] = (float) $cellValue;
                } else {
                    $values[$key] = $cellValue !== '' ? $cellValue : null;
                }

                if ($cellValue !== '') $isEmptyRow = false;
            }

            if ($isEmptyRow) continue;

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
        // Query di sini agar selalu fresh dan ter-pass ke view
        $upload        = DatasetUpload::with('dataset')->findOrFail($this->uploadId);
        $organizations = Organization::where('is_active', true)->get();
        $categories    = Category::where('is_active', true)->get();

        return view('livewire.management.datasets.edit', compact(
            'upload',
            'organizations',
            'categories',
        ));
    }
}