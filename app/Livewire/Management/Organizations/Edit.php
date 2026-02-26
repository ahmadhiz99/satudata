<?php

namespace App\Livewire\Management\Organizations;

use App\Models\Organization;
use Livewire\Component;
use Illuminate\Support\Str;

class Edit extends Component
{
    public int    $organizationId;
    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public string $email       = '';
    public string $phone       = '';
    public string $address     = '';
    public string $website     = '';
    public bool   $is_active   = true;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:organizations,name,' . $this->organizationId,
            'code'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'website'     => 'nullable|url|max:255',
            'is_active'   => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama OPD harus diisi',
        'name.unique'   => 'Nama OPD sudah terdaftar',
        'email.email'   => 'Format email tidak valid',
        'website.url'   => 'Format website tidak valid (harus diawali https://)',
    ];

    public function mount(Organization $organization): void
    {
        $this->organizationId = $organization->id;
        $this->name           = $organization->name;
        $this->code           = $organization->code        ?? '';
        $this->description    = $organization->description ?? '';
        $this->email          = $organization->email        ?? '';
        $this->phone          = $organization->phone        ?? '';
        $this->address        = $organization->address      ?? '';
        $this->website        = $organization->website      ?? '';
        $this->is_active      = $organization->is_active;
    }

    public function save(): mixed
    {
        $this->validate();

        Organization::findOrFail($this->organizationId)->update([
            'name'        => $this->name,
            'slug'        => Str::slug($this->name),
            'code'        => $this->code        ?: null,
            'description' => $this->description ?: null,
            'email'       => $this->email        ?: null,
            'phone'       => $this->phone        ?: null,
            'address'     => $this->address      ?: null,
            'website'     => $this->website      ?: null,
            'is_active'   => $this->is_active,
        ]);

        session()->flash('success', 'OPD berhasil diperbarui!');
        return redirect()->route('management.organizations.index');
    }

    public function render()
    {
        return view('livewire.management.organizations.edit');
    }
}