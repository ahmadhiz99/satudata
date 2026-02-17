<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'selectedRoles' => 'sometimes|array',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (!empty($validated['selectedRoles'])) {
            $user->syncRoles($validated['selectedRoles']);
        }

        session()->flash('success', 'User created successfully.');

        return redirect()->route('users.index');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.users.create', [
            'roles' => $roles
        ]);
    }
}