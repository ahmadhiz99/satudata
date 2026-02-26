<?php

namespace App\Livewire\Management\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search           = '';
    public bool   $confirmingDeletion = false;
    public ?int   $categoryToDelete   = null;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeletion = true;
        $this->categoryToDelete   = $id;
    }

    public function deleteCategory(): void
    {
        $category = Category::findOrFail($this->categoryToDelete);
        $category->delete();

        session()->flash('success', 'Kategori "' . $category->name . '" berhasil dihapus!');
        $this->confirmingDeletion = false;
        $this->categoryToDelete   = null;
    }

    public function toggleActive(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
    }

    public function render()
    {
        $categories = Category::when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->withCount('datasets')
            ->latest()
            ->paginate(15);

        return view('livewire.management.categories.index', compact('categories'));
    }
}