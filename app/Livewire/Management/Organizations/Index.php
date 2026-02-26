<?php

namespace App\Livewire\Management\Organizations;

use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search              = '';
    public bool   $confirmingDeletion  = false;
    public ?int   $organizationToDelete = null;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeletion    = true;
        $this->organizationToDelete  = $id;
    }

    public function deleteOrganization(): void
    {
        $org = Organization::findOrFail($this->organizationToDelete);
        $org->delete();

        session()->flash('success', 'OPD "' . $org->name . '" berhasil dihapus!');
        $this->confirmingDeletion    = false;
        $this->organizationToDelete  = null;
    }

    public function toggleActive(int $id): void
    {
        $org = Organization::findOrFail($id);
        $org->update(['is_active' => !$org->is_active]);
    }

    public function render()
    {
        $organizations = Organization::when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
            )
            ->withCount('datasets')
            ->latest()
            ->paginate(15);

        return view('livewire.management.organizations.index', compact('organizations'));
    }
}