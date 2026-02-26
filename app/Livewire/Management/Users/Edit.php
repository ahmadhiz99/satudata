<?php

namespace App\Livewire\Management\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public User $user;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'selectedRoles' => 'sometimes|array',
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] 
                ? Hash::make($validated['password']) 
                : $this->user->password,
        ]);

        if (isset($validated['selectedRoles'])) {
            $this->user->syncRoles($validated['selectedRoles']);
        }

        session()->flash('success', 'User updated successfully.');

        return redirect()->route('management.users.index');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.management.users.edit', [
            'roles' => $roles
        ]);
    }
}