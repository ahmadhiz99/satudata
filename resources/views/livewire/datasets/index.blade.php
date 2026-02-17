<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Dataset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Search & Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Search --}}
                        <div class="md:col-span-3">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="search"
                                placeholder="Cari dataset..." 
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            />
                        </div>

                        {{-- Filter Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select 
                                wire:model.live="filterCategory"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Organization --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organisasi</label>
                            <select 
                                wire:model.live="filterOrganization"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Organisasi</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Reset --}}
                        <div class="flex items-end">
                            <button 
                                wire:click="$set('search', ''); $set('filterCategory', ''); $set('filterOrganization', '')"
                                class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dataset Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($datasets as $dataset)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            {{-- Category Badge --}}
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full" 
                                      style="background-color: {{ $dataset->category->color }}20; color: {{ $dataset->category->color }}">
                                    <i class="fas {{ $dataset->category->icon }} mr-1"></i>
                                    {{ $dataset->category->name }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $dataset->title }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ $dataset->description ?? 'Tidak ada deskripsi' }}
                            </p>

                            {{-- Meta Info --}}
                            <div class="flex items-center text-xs text-gray-500 mb-4 gap-4">
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $dataset->start_year }}-{{ $dataset->end_year }}
                                </span>
                                <span>
                                    <i class="fas fa-database mr-1"></i>
                                    {{ $dataset->records()->count() }} records
                                </span>
                            </div>

                            {{-- Organization --}}
                            <div class="text-xs text-gray-500 mb-4">
                                <i class="fas fa-building mr-1"></i>
                                {{ $dataset->organization->name }}
                            </div>

                            {{-- Button --}}
                            <a 
                                href="{{ route('datasets.show', $dataset->slug) }}"
                                class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                Lihat Data
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-3"></i>
                        <p>Tidak ada dataset ditemukan</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $datasets->links() }}
            </div>

        </div>
    </div>

    {{-- Add Tailwind line-clamp utility --}}
    @push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush
</div>