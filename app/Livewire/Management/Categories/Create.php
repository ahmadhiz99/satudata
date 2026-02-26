<?php

namespace App\Livewire\Management\Categories;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public string $name        = '';
    public string $description = '';
    public string $icon        = 'fas fa-folder';
    public string $color       = '#3B82F6';
    public bool   $is_active   = true;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'color'       => 'nullable|string|max:20',
            'is_active'   => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama kategori harus diisi',
        'name.unique'   => 'Nama kategori sudah terdaftar',
    ];

    public function save(): mixed
    {
        $this->validate();

        Category::create([
            'name'        => $this->name,
            'slug'        => Str::slug($this->name),
            'description' => $this->description ?: null,
            'icon'        => $this->icon        ?: null,
            'color'       => $this->color        ?: null,
            'is_active'   => $this->is_active,
        ]);

        session()->flash('success', 'Kategori berhasil ditambahkan!');
        return redirect()->route('management.categories.index');
    }

    public function render()
    {
        return view('livewire.management.categories.create');
    }
}