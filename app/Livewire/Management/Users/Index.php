<?php

namespace App\Livewire\Management\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingUserDeletion = false;
    public $userIdToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($userId)
    {
        $this->confirmingUserDeletion = true;
        $this->userIdToDelete = $userId;
    }

    public function deleteUser()
    {
        $user = User::find($this->userIdToDelete);
        
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            session()->flash('success', 'User deleted successfully.');
        } else {
            session()->flash('error', 'You cannot delete yourself.');
        }

        $this->confirmingUserDeletion = false;
        $this->userIdToDelete = null;
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                // $query->where('name', 'like', '%' . $this->search . '%')
                //       ->orWhere('email', 'like', '%' . $this->search . '%');
                 $query->where(DB::raw('LOWER(name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWhere(DB::raw('LOWER(email)'), 'like', '%' . strtolower($this->search) . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.management.users.index', [
            'users' => $users
        ]);
    }
}