<?php

namespace App\Livewire\Management\Datasets;

use App\Models\DatasetUpload;
use App\Models\Dataset;
use App\Models\DatasetRecord;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $datasetToDelete = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->datasetToDelete    = $id;
    }

    public function deleteDataset()
    {
        $upload = DatasetUpload::findOrFail($this->datasetToDelete);
        // 1. Hapus records terlebih dahulu (child paling dalam)
        $datasetIds = Dataset::where('dataset_upload_id', $upload->id)->pluck('id');
        if ($datasetIds->isNotEmpty()) {
            $datasetRecordsDeleted = DatasetRecord::whereIn('dataset_id', $datasetIds);
            $datasetsDeleted = Dataset::whereIn('id', $datasetIds);
            DatasetRecord::whereIn('dataset_id', $datasetIds)->delete();
            Dataset::whereIn('id', $datasetIds)->delete();
        }

        // 2. Hapus file fisik dari storage
        if ($upload->file_path) {
            Storage::disk('public')->delete($upload->file_path);
        }

        // 3. Hapus upload record
        $upload->delete();

        session()->flash('success', 'Dataset berhasil dihapus!');
        $this->confirmingDeletion = false;
        $this->datasetToDelete    = null;
    }

    public function render()
    {
        $datasets = DatasetUpload::with('organization')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('organization', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.management.datasets.index', compact('datasets'));
    }
}