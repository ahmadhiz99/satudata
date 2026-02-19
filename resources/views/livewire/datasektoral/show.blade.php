<div>
    

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    
                    <div name="header">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="font-semibold text-xl text-white leading-tight">
                                    {{ $dataset->title }}
                                </h2>
                                <p class="text-sm text-gray-100 mt-1">
                                    <i class="fas fa-building mr-1"></i> {{ $dataset->organization->name ?? '-' }}
                                </p>
                            </div>
                            <a href="{{ route('datasektoral.index') }}" 
                                class="inline-flex items-center gap-2 text-sm text-gray-100 hover:text-white hover:bg-white hover:bg-opacity-10 px-3 py-1.5 rounded-lg transition-all duration-200">
                                    <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Informasi Dataset --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Informasi Dataset</h3>
                </div>
                <div class="bg-white divide-y divide-gray-100">
                    <div class="flex items-center px-6 py-4">
                        <p class="text-sm text-gray-400 w-48 shrink-0">Nama Dataset</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->title }}</p>
                    </div>
                    <div class="flex items-center px-6 py-4">
                        <p class="text-sm text-gray-400 w-48 shrink-0">OPD</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->organization->name ?? '-' }}</p>
                    </div>
                    <div class="flex items-center px-6 py-4">
                        <p class="text-sm text-gray-400 w-48 shrink-0">Kategori</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->category->name ?? '-' }}</p>
                    </div>
                    <div class="flex items-center px-6 py-4">
                        <p class="text-sm text-gray-400 w-48 shrink-0">Tanggal Upload</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center px-6 py-4">
                        <p class="text-sm text-gray-400 w-48 shrink-0">Terakhir Diubah</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center px-6 py-4">
                        <p class="text-sm text-gray-400 w-48 shrink-0">Total Views</p>
                        <p class="text-sm font-semibold text-gray-800">
                            <i class="fas fa-eye mr-1 text-gray-400"></i>
                            {{ number_format($dataset->view_count) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- File --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">File</h3>
                </div>
                <div class="p-6">
                    @if($dataset->file_path)
                        <div class="flex items-center gap-4 border border-gray-200 rounded-xl p-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-file-excel text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $dataset->file_name }}</p>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                                    <span><i class="fas fa-weight-hanging mr-1"></i>{{ $dataset->file_size_formatted }}</span>
                                    <span>•</span>
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $dataset->created_at->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span class="text-green-600 font-medium">Excel</span>
                                </div>
                            </div>
                            <button
                                wire:click="download"
                                wire:loading.attr="disabled"
                                class="flex bg-green-500 items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50 shrink-0">
                                <span wire:loading.remove wire:target="download">
                                    <i class="fas fa-download mr-1"></i> Download
                                </span>
                                <span wire:loading wire:target="download">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Menyiapkan...
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-file-slash text-4xl mb-3 block"></i>
                            <p>File tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>