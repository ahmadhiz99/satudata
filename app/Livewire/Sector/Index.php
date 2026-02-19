<?php

namespace App\Livewire\Sector;

use App\Models\DatasetUpload;
use App\Models\Category;
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
        $datasets = Category::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.sector.index', compact('datasets'))
        ->layout('components.layouts.public');
        
    }
}