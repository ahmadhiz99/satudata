<?php

namespace App\Livewire\Management\Datasets;

use App\Models\DatasetUpload;
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

        if ($upload->file_path) {
            Storage::disk('public')->delete($upload->file_path);
        }

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