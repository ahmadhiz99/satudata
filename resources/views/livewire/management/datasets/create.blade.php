<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Dataset
            </h2>
            <a href="{{ route('management.datasets.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form wire:submit.prevent="save" class="p-6 space-y-6">

                    {{-- Nama Dataset --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Dataset <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="title"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('title') border-red-500 @enderror"
                               placeholder="Contoh: Jumlah Penduduk Per Kecamatan 2024">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- OPD --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Produsen Data (OPD) <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="organization_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('organization_id') border-red-500 @enderror">
                            <option value="">Pilih OPD</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sektor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sektor <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="category_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Pilih Sektor</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Excel --}}
                    <div class="p-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File Excel <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                               wire:model="excel_file"
                               accept=".xlsx,.xls"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">

                        @if($excel_file)
                            <div class="mt-2 text-sm text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ $excel_file->getClientOriginalName() }}
                            </div>
                        @endif

                        <div wire:loading wire:target="excel_file" class="mt-2 text-sm text-blue-600">
                            <i class="fas fa-spinner fa-spin mr-1"></i>
                            Mengupload file...
                        </div>

                        @error('excel_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format .xlsx atau .xls, maksimal 10MB.
                        </p>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...
                            </span>
                        </button>
                        <a href="{{ route('management.datasets.index') }}"
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>