<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-tags mr-2"></i> Management Kategori
            </h2>
            <a href="{{ route('management.categories.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition text-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Search --}}
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm"
                                   placeholder="Cari nama kategori...">
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dataset</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-center text-sm text-gray-400">
                                            {{ $categories->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                                     style="background-color: {{ $category->color ? $category->color . '22' : '#3B82F622' }}">
                                                    <i class="{{ $category->icon ?? 'fas fa-folder' }}"
                                                       style="color: {{ $category->color ?? '#3B82F6' }}"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                                    <p class="text-xs text-gray-400">{{ $category->slug }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs">
                                            <p class="truncate">{{ $category->description ?? '-' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                                {{ $category->datasets_count }} dataset
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button wire:click="toggleActive({{ $category->id }})"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                                    {{ $category->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $category->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('management.categories.edit', $category) }}"
                                                   class="text-blue-500 hover:text-blue-700 transition p-1" title="Edit">
                                                    <i class="fas fa-pen text-sm"></i>
                                                </a>
                                                <button wire:click="confirmDelete({{ $category->id }})"
                                                        class="transition p-1 {{ $category->datasets_count > 0 ? 'text-gray-300 cursor-not-allowed' : 'text-red-400 hover:text-red-600' }}"
                                                        title="{{ $category->datasets_count > 0 ? 'Tidak bisa dihapus, masih ada dataset' : 'Hapus' }}"
                                                        {{ $category->datasets_count > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                            <i class="fas fa-tags text-3xl mb-3 block"></i>
                                            Tidak ada kategori{{ $search ? ' yang sesuai pencarian' : '' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Hapus --}}
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('confirmingDeletion', false)"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Hapus Kategori</h3>
                        <p class="text-sm text-gray-500 mt-1">Kategori ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDeletion', false)"
                            class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        Batal
                    </button>
                    <button wire:click="deleteCategory"
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        <span wire:loading.remove wire:target="deleteCategory">Hapus</span>
                        <span wire:loading wire:target="deleteCategory"><i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>