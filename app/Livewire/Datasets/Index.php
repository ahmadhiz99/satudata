<?php

namespace App\Livewire\Datasets;

use App\Models\Category;
use App\Models\Dataset;
use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $filterOrganization = '';

    protected $queryString = ['search', 'filterCategory', 'filterOrganization'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterOrganization()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $organizations = Organization::where('is_active', true)->get();

        $datasets = Dataset::query()
            ->with(['category', 'organization'])
            ->published()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($this->search) . '%'])
                      ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($this->search) . '%']);
                });
            })
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->when($this->filterOrganization, function ($query) {
                $query->where('organization_id', $this->filterOrganization);
            })
            ->latest('published_at')
            ->paginate(12);

        return view('livewire.datasets.index', [
            'datasets' => $datasets,
            'categories' => $categories,
            'organizations' => $organizations,
        ]);
    }
}