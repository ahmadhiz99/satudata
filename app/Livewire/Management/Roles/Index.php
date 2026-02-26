<?php

namespace App\Livewire\Management\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingRoleDeletion = false;
    public $roleIdToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($roleId)
    {
        $this->confirmingRoleDeletion = true;
        $this->roleIdToDelete = $roleId;
    }

    public function deleteRole()
    {
        $role = Role::find($this->roleIdToDelete);
        
        if ($role) {
            // Check if role has users
            if ($role->users()->count() > 0) {
                session()->flash('error', 'Cannot delete role that has users assigned.');
            } else {
                $role->delete();
                session()->flash('success', 'Role deleted successfully.');
            }
        }

        $this->confirmingRoleDeletion = false;
        $this->roleIdToDelete = null;
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount('users')
            ->when($this->search, function ($query) {
                // $query->where('name', 'like', '%' . $this->search . '%');
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%']);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.management.roles.index', [
            'roles' => $roles
        ]);
    }
}