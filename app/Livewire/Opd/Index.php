<?php

namespace App\Livewire\Opd;

use App\Models\DatasetUpload;
use App\Models\Organization;
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
    
    public function render()
    {
        $datasets = Organization::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.opd.index', compact('datasets'))
        ->layout('components.layouts.public');
        
    }
}