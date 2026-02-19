<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-database mr-2"></i> Management Dataset
            </h2>
            <a href="{{ route('management.datasets.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition text-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Dataset
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Search & Filter --}}
                    <div class="mb-6 flex flex-col sm:flex-row gap-3">
                        {{-- Search --}}
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                wire:model.live.debounce.300ms="search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm"
                                placeholder="Cari nama dataset atau OPD...">
                        </div>

                        {{-- Filter OPD --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400 text-xs"></i>
                            </div>
                            <select wire:model.live="opd"
                                    class="block w-full sm:w-52 pl-8 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm appearance-none bg-white">
                                <option value="">Semua OPD</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>

                        {{-- Filter Sektor --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-layer-group text-gray-400 text-xs"></i>
                            </div>
                            <select wire:model.live="sector"
                                    class="block w-full sm:w-52 pl-8 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm appearance-none bg-white">
                                <option value="">Semua Sektor</option>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>

                        {{-- Reset Filter --}}
                        @if($search || $opd || $sector)
                            <button wire:click="resetFilters"
                                    class="flex items-center gap-2 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition whitespace-nowrap">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        @endif
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dataset</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OPD</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($datasets as $dataset)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-400">
                                            {{ $datasets->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $dataset->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $dataset->organization->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($dataset->file_path)
                                                <span class="inline-flex items-center gap-1 text-green-700 bg-green-50 px-2 py-0.5 rounded text-xs">
                                                    <i class="fas fa-file-excel"></i>
                                                    {{ $dataset->file_name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">Tidak ada file</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-xs">
                                            {{ $dataset->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-3">
                                                {{-- Detail --}}
                                                <a href="{{ route('datasektoral.show', $dataset->id) }}"
                                                   class="text-blue-500 hover:text-blue-700 transition"
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- Download --}}
                                                @if($dataset->file_path)
                                                    <a href="{{ Storage::url($dataset->file_path) }}"
                                                       class="text-green-600 hover:text-green-800 transition"
                                                       title="Download Excel"
                                                       download="{{ $dataset->file_name }}">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                            <p>Belum ada dataset</p>
                                            <p class="text-xs mt-1">Klik "Tambah Dataset" untuk menambahkan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $datasets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('confirmingDeletion', false)"></div>

            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Hapus Dataset</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Dataset dan file Excel terkait akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDeletion', false)"
                            class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        Batal
                    </button>
                    <button wire:click="deleteDataset"
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>