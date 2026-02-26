<?php

namespace App\Livewire\Management\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public Role $role;
    public $name = '';

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role->id,
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $this->role->update([
            'name' => strtolower($validated['name']),
        ]);

        session()->flash('success', 'Role updated successfully.');

        return redirect()->route('management.roles.index');
    }

    public function render()
    {
        return view('livewire.management.roles.edit');
    }
}