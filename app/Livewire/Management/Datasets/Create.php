<?php

namespace App\Livewire\Management\Datasets;

use App\Models\DatasetUpload;
use App\Models\Organization;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $category_id;
    public $organization_id;
    public $excel_file;

    public $organizations = [];
    public $categories = [];

    protected $rules = [
        'title'           => 'required|string|max:255',
        'organization_id' => 'required|exists:organizations,id',
        'category_id'     => 'required|exists:categories,id',
        'excel_file'      => 'required|file|mimes:xlsx,xls|max:10240',
    ];

    protected $messages = [
        'title.required'           => 'Nama dataset harus diisi',
        'organization_id.required' => 'OPD harus dipilih',
        'category_id.required'     => 'Sektor harus dipilih',
        'excel_file.required'      => 'File Excel harus diupload',
        'excel_file.mimes'         => 'File harus berformat .xlsx atau .xls',
        'excel_file.max'           => 'Ukuran file maksimal 10MB',
    ];

    public function mount()
    {
        $this->organizations = Organization::where('is_active', true)->get();
        $this->categories     = Category::where('is_active', true)->get();
    }

    public function save()
    {
        $this->validate();

        $filename = Str::slug($this->title) . '_' . time() . '.' . $this->excel_file->getClientOriginalExtension();
        $path     = $this->excel_file->storeAs('dataset_uploads', $filename, 'public');

        DatasetUpload::create([
            'title'           => $this->title,
            'organization_id' => $this->organization_id,
            'category_id'     => $this->category_id,
            'file_path'       => $path,
            'file_name'       => $this->excel_file->getClientOriginalName(),
            'file_size'       => $this->excel_file->getSize(),
        ]);

        session()->flash('success', 'Dataset berhasil diupload!');
        return redirect()->route('management.datasets.index');
    }

    public function render()
    {
        return view('livewire.management.datasets.create');
    }
}