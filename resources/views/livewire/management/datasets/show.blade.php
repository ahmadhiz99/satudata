<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-file-excel mr-2"></i> Detail Dataset
            </h2>
            <a href="{{ route('management.datasets.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info Card --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                        Informasi Dataset
                    </h3>
                </div>
                <div class="p-6 divide-y divide-gray-100">

                    <div class="flex items-start gap-4 py-3 first:pt-0 last:pb-0">
                        <span class="w-40 text-sm text-gray-500 flex-shrink-0">Nama Dataset</span>
                        <span class="text-sm font-medium text-gray-900">{{ $upload->title }}</span>
                    </div>

                    <div class="flex items-start gap-4 py-3">
                        <span class="w-40 text-sm text-gray-500 flex-shrink-0">OPD</span>
                        <span class="text-sm text-gray-900">{{ $upload->organization->name ?? '-' }}</span>
                    </div>

                    <div class="flex items-start gap-4 py-3">
                        <span class="w-40 text-sm text-gray-500 flex-shrink-0">Tanggal Upload</span>
                        <span class="text-sm text-gray-900">{{ $upload->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="flex items-start gap-4 py-3 last:pb-0">
                        <span class="w-40 text-sm text-gray-500 flex-shrink-0">Terakhir Diubah</span>
                        <span class="text-sm text-gray-900">{{ $upload->updated_at->format('d M Y, H:i') }}</span>
                    </div>

                </div>
            </div>

            {{-- File Card --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                        File
                    </h3>
                </div>
                <div class="p-6">
                    @if($upload->file_path)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0 px-4 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-excel text-green-600 text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $upload->file_name }}</p>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-weight-hanging mr-1"></i>
                                        {{ $upload->file_size_formatted }}
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $upload->created_at->format('d M Y') }}
                                    </span>
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded">
                                        Excel
                                    </span>
                                    <a href="{{ Storage::url($upload->file_path) }}"
                                       download="{{ $upload->file_name }}"
                                       class="inline-flex items-center gap-1.5 text-xs text-white bg-green-500 hover:bg-green-700 px-2.5 py-0.5 rounded transition">
                                        <i class="fas fa-download"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-file-slash text-3xl mb-2 block"></i>
                            <p class="text-sm">Tidak ada file tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>