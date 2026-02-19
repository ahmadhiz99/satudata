<?php

namespace App\Livewire\Datasektoral;

use App\Models\DatasetUpload;
use Livewire\Component;
use App\Models\Organization;
use App\Models\Category as Sector;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $opd = '';
    public $sector = '';
    public $confirmingDeletion = false;
    public $datasetToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'opd'    => ['except' => ''],
        'sector' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOpd()
    {
        $this->resetPage();
    }

    public function updatingSector()
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

    public function resetFilters()
    {
        $this->search = '';
        $this->opd    = '';
        $this->sector = '';
        $this->resetPage();
    }

    public function render()
    {
        $organizations = Organization::orderBy('name')->get();
        $sectors       = Sector::orderBy('name')->get();

        $datasets = DatasetUpload::with('organization')
            ->when($this->opd, function ($query) {
                $query->where('organization_id', $this->opd);
            })
            ->when($this->sector, function ($query) {
                $query->where('category_id', $this->sector);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('organization', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.datasektoral.index', compact('datasets', 'organizations', 'sectors'))
            ->layout('components.layouts.public');
    }
}