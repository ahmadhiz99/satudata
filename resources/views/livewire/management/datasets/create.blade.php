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

                    {{-- Ekstrak ke Database --}}
                    <div class="space-y-4">

                        <div class="flex items-center gap-2">
                            <input type="checkbox"
                                   wire:model.live="extract_to_db"
                                   id="extract_to_db"
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="extract_to_db" class="block text-sm font-medium text-gray-700 cursor-pointer">
                                Ekstrak Ke Database
                            </label>
                        </div>

                        @if($extract_to_db)
                            <div class="ml-6 space-y-4 border-l-2 border-blue-200 pl-4">

                                {{-- Info posisi ekstraksi --}}
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg space-y-4">
                                    <p class="text-xs text-blue-600 font-medium">
                                        <i class="fas fa-table mr-1"></i>
                                        Konfigurasi Posisi Data Excel
                                    </p>
                                    <p class="text-xs text-blue-500">
                                        Tentukan posisi awal data pada file Excel. Baris header diambil otomatis dari baris sebelum baris mulai.
                                    </p>

                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Start Row --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Baris Mulai <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   wire:model="start_row"
                                                   min="2"
                                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('start_row') border-red-500 @enderror"
                                                   placeholder="5">
                                            @error('start_row')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-xs text-gray-400 mt-1">Default: 5 (header di baris 4)</p>
                                        </div>

                                        {{-- Start Col --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Kolom Mulai <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   wire:model="start_col"
                                                   maxlength="3"
                                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('start_col') border-red-500 @enderror uppercase"
                                                   placeholder="A">
                                            @error('start_col')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-xs text-gray-400 mt-1">Default: A</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2 text-xs text-blue-700 bg-blue-100 rounded p-2">
                                        <i class="fas fa-lightbulb mt-0.5 shrink-0"></i>
                                        <span>
                                            Baris <strong>{{ $start_row }}</strong>, Kolom <strong>{{ strtoupper($start_col) }}</strong>
                                            → data dimulai dari sel <strong>{{ strtoupper($start_col) }}{{ $start_row }}</strong>,
                                            header dibaca dari baris <strong>{{ $start_row - 1 }}</strong>.
                                        </span>
                                    </div>
                                </div>

                                {{-- Visualisasi Dataset --}}
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg space-y-4">
                                    <p class="text-xs text-gray-500 font-medium">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Cara Visualisasi Dataset (Visualisasi apa saja yang akan di set ketika disajikan)
                                    </p>

                                    {{-- Visualitation --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tipe Visualisasi
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox"
                                                wire:model.live="visualize_table"
                                                id="visualize_table"
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label for="visualize_table" class="block text-sm font-medium text-gray-700 cursor-pointer">
                                                Tabel
                                            </label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox"
                                                wire:model.live="visualize_chart"
                                                id="visualize_chart"
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label for="visualize_chart" class="block text-sm font-medium text-gray-700 cursor-pointer">
                                                Grafik
                                            </label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox"
                                                wire:model.live="visualize_map"
                                                id="visualize_map"
                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                            <label for="visualize_map" class="block text-sm font-medium text-gray-700 cursor-pointer">
                                                Peta
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg space-y-4">
                                    <p class="text-xs text-gray-500 font-medium">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Metadata Dataset (untuk katalog database)
                                    </p>

                                    {{-- Deskripsi --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Deskripsi
                                        </label>
                                        <textarea wire:model="description"
                                                  rows="3"
                                                  class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('description') border-red-500 @enderror"
                                                  placeholder="Deskripsi singkat tentang dataset ini..."></textarea>
                                        @error('description')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Unit --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Satuan
                                            </label>
                                            <input type="text"
                                                   wire:model="unit"
                                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('unit') border-red-500 @enderror"
                                                   placeholder="Contoh: Orang, Ton, Km">
                                            @error('unit')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Frekuensi --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Frekuensi
                                            </label>
                                            <select wire:model="frequency"
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('frequency') border-red-500 @enderror">
                                                <option value="">Pilih Frekuensi</option>
                                                <option value="Harian">Harian</option>
                                                <option value="Mingguan">Mingguan</option>
                                                <option value="Bulanan">Bulanan</option>
                                                <option value="Triwulanan">Triwulanan</option>
                                                <option value="Semesteran">Semesteran</option>
                                                <option value="Tahunan">Tahunan</option>
                                            </select>
                                            @error('frequency')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Tahun Mulai --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Tahun Mulai
                                            </label>
                                            <input type="number"
                                                   wire:model="start_year"
                                                   min="1900"
                                                   max="{{ date('Y') + 5 }}"
                                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('start_year') border-red-500 @enderror"
                                                   placeholder="{{ date('Y') }}">
                                            @error('start_year')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Tahun Akhir --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Tahun Akhir
                                            </label>
                                            <input type="number"
                                                   wire:model="end_year"
                                                   min="1900"
                                                   max="{{ date('Y') + 5 }}"
                                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500 @error('end_year') border-red-500 @enderror"
                                                   placeholder="{{ date('Y') }}">
                                            @error('end_year')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif

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