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

                    {{-- Search --}}
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm"
                                   placeholder="Cari nama dataset atau OPD...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                         @foreach($datasets as $dataset)
                            <a href="{{ route('datasektoral.index', 'opd='.$dataset->id ) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $dataset->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $dataset->code ?? 'Kode tidak tersedia' }}</p>
                                </div>
                            </a>
                        @endforeach

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