<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    public $name = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        Role::create([
            'name' => strtolower($validated['name']),
        ]);

        session()->flash('success', 'Role created successfully.');

        return redirect()->route('roles.index');
    }

    public function render()
    {
        return view('livewire.roles.create');
    }
}