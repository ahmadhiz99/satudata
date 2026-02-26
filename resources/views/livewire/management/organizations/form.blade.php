<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <form wire:submit.prevent="save" class="p-6 space-y-5">

        {{-- Nama OPD --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama OPD <span class="text-red-500">*</span>
            </label>
            <input type="text" wire:model="name"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('name') border-red-500 @enderror"
                   placeholder="Contoh: Dinas Pendidikan">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kode --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kode OPD</label>
            <input type="text" wire:model="code"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('code') border-red-500 @enderror"
                   placeholder="Contoh: DISDIK">
            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea wire:model="description" rows="3"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"
                      placeholder="Deskripsi singkat tentang OPD ini..."></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model="email"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('email') border-red-500 @enderror"
                       placeholder="opd@example.go.id">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Telepon --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" wire:model="phone"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('phone') border-red-500 @enderror"
                       placeholder="0521-XXXXXXX">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Alamat --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <textarea wire:model="address" rows="2"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"
                      placeholder="Alamat kantor OPD..."></textarea>
        </div>

        {{-- Website --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
            <input type="text" wire:model="website"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('website') border-red-500 @enderror"
                   placeholder="https://...">
            @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Status --}}
        <div class="flex items-center gap-3 py-3 px-4 rounded-lg border transition-colors
            {{ $is_active ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
            <input type="checkbox" wire:model.live="is_active" id="is_active"
                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
            <label for="is_active" class="flex items-center gap-2 text-sm cursor-pointer">
                <span class="font-medium {{ $is_active ? 'text-green-700' : 'text-gray-500' }}">
                    {{ $is_active ? 'OPD Aktif' : 'OPD Nonaktif' }}
                </span>
                <span class="text-xs {{ $is_active ? 'text-green-500' : 'text-gray-400' }}">
                    {{ $is_active ? '— tampil di pilihan dataset' : '— tersembunyi dari pilihan dataset' }}
                </span>
            </label>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 pt-2">
            <button type="submit" wire:loading.attr="disabled"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow transition disabled:opacity-50">
                <span wire:loading.remove wire:target="save">
                    <i class="fas fa-save mr-2"></i> Simpan
                </span>
                <span wire:loading wire:target="save">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...
                </span>
            </button>
            <a href="{{ route('management.organizations.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition">
                Batal
            </a>
        </div>

    </form>
</div>