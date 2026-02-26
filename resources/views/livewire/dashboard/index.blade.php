<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-gauge-high mr-2 text-red-500"></i> Dashboard
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Ringkasan data sistem</p>
            </div>
            <span class="text-xs text-gray-400">
                <i class="fas fa-clock mr-1"></i> {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ══ Stat Cards ══════════════════════════════════════════════ --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                {{-- Upload --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                        <i class="fas fa-upload text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_uploads']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total Upload</p>
                    </div>
                </div>

                {{-- Dataset --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-database text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_datasets']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Dataset Aktif</p>
                    </div>
                </div>

                {{-- Records --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-violet-50 flex items-center justify-center">
                        <i class="fas fa-table-list text-violet-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_records']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total Records</p>
                    </div>
                </div>

                {{-- OPD --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-building text-amber-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['active_orgs']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">OPD Aktif</p>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <i class="fas fa-tags text-green-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['active_categories']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Kategori Aktif</p>
                    </div>
                </div>

                {{-- Users --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center">
                        <i class="fas fa-users text-sky-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Pengguna</p>
                    </div>
                </div>

            </div>

            {{-- ══ Row 2: Chart + OPD ══════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Upload per Bulan --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Upload per Bulan</h3>
                            <p class="text-xs text-gray-400 mt-0.5">6 bulan terakhir</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                            <i class="fas fa-chart-line text-red-400 text-sm"></i>
                        </div>
                    </div>
                    @if($uploadsPerMonth->isEmpty())
                        <div class="flex items-center justify-center h-48 text-gray-300">
                            <div class="text-center">
                                <i class="fas fa-chart-line text-4xl mb-2 block"></i>
                                <p class="text-sm">Belum ada data</p>
                            </div>
                        </div>
                    @else
                        <div style="height: 200px; position: relative;">
                            <canvas id="uploadsChart"></canvas>
                        </div>
                    @endif
                </div>

                {{-- Top OPD --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Top OPD</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Berdasarkan jumlah dataset</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                            <i class="fas fa-building text-amber-400 text-sm"></i>
                        </div>
                    </div>
                    @if($datasetByOrg->isEmpty())
                        <div class="flex items-center justify-center h-40 text-gray-300">
                            <p class="text-sm">Belum ada data</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @php $maxOrg = $datasetByOrg->max('datasets_count') ?: 1; @endphp
                            @foreach($datasetByOrg as $i => $org)
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-xs text-gray-600 truncate max-w-[160px]" title="{{ $org->name }}">
                                            {{ $org->name }}
                                        </p>
                                        <span class="text-xs font-semibold text-gray-700 ml-2 shrink-0">
                                            {{ $org->datasets_count }}
                                        </span>
                                    </div>
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-amber-400 transition-all duration-500"
                                             style="width: {{ ($org->datasets_count / $maxOrg) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- ══ Row 3: Recent + Kategori ════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Upload Terbaru --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Upload Terbaru</h3>
                            <p class="text-xs text-gray-400 mt-0.5">5 dataset terakhir yang diupload</p>
                        </div>
                        <a href="{{ route('management.datasets.index') }}"
                           class="text-xs text-red-500 hover:text-red-700 transition font-medium">
                            Lihat semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($recentUploads as $upload)
                            <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition">
                                <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-file-excel text-green-500 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $upload->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $upload->organization->name ?? '-' }}
                                        <span class="mx-1">·</span>
                                        {{ $upload->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="shrink-0">
                                    @if($upload->extract_to_db)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-600">
                                            <i class="fas fa-database mr-1 text-xs"></i> DB
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                            <i class="fas fa-file mr-1 text-xs"></i> File
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center text-gray-300">
                                <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                <p class="text-sm">Belum ada upload</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Dataset per Kategori --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Per Kategori</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Distribusi dataset</p>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                            <i class="fas fa-tags text-green-400 text-sm"></i>
                        </div>
                    </div>
                    @if($datasetByCategory->isEmpty())
                        <div class="flex items-center justify-center h-40 text-gray-300">
                            <p class="text-sm">Belum ada data</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @php $maxCat = $datasetByCategory->max('datasets_count') ?: 1; @endphp
                            @foreach($datasetByCategory as $cat)
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <i class="{{ $cat->icon ?? 'fas fa-folder' }} text-xs shrink-0"
                                               style="color: {{ $cat->color ?? '#3B82F6' }}"></i>
                                            <p class="text-xs text-gray-600 truncate">{{ $cat->name }}</p>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 ml-2 shrink-0">
                                            {{ $cat->datasets_count }}
                                        </span>
                                    </div>
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500"
                                             style="width: {{ ($cat->datasets_count / $maxCat) * 100 }}%; background-color: {{ $cat->color ?? '#3B82F6' }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- ══ Row 4: Quick Links ══════════════════════════════════════ --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Akses Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('management.datasets.create') }}"
                       class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-red-50 group-hover:bg-red-100 flex items-center justify-center transition">
                            <i class="fas fa-plus text-red-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Upload Dataset</p>
                            <p class="text-xs text-gray-400">Tambah data baru</p>
                        </div>
                    </a>
                    <a href="{{ route('management.organizations.index') }}"
                       class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-amber-200 hover:bg-amber-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition">
                            <i class="fas fa-building text-amber-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Kelola OPD</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_organizations'] }} OPD terdaftar</p>
                        </div>
                    </a>
                    <a href="{{ route('management.categories.index') }}"
                       class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-green-200 hover:bg-green-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-green-50 group-hover:bg-green-100 flex items-center justify-center transition">
                            <i class="fas fa-tags text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Kelola Kategori</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_categories'] }} kategori</p>
                        </div>
                    </a>
                    <a href="{{ route('management.datasets.index') }}"
                       class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition group">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition">
                            <i class="fas fa-database text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Semua Dataset</p>
                            <p class="text-xs text-gray-400">{{ $stats['total_uploads'] }} file upload</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    @if($uploadsPerMonth->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('uploadsChart');
            if (!ctx || typeof Chart === 'undefined') return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($uploadsPerMonth->pluck('month')),
                    datasets: [{
                        label: 'Jumlah Upload',
                        data: @json($uploadsPerMonth->pluck('total')),
                        backgroundColor: 'rgba(239, 68, 68, 0.12)',
                        borderColor: 'rgba(239, 68, 68, 0.8)',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleFont: { size: 12 },
                            bodyFont: { size: 12 },
                            padding: 10,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.y + ' upload'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { size: 11 },
                                color: '#9ca3af',
                            },
                            grid: { color: '#f3f4f6' },
                            border: { display: false },
                        },
                        x: {
                            ticks: { font: { size: 11 }, color: '#9ca3af' },
                            grid: { display: false },
                            border: { display: false },
                        }
                    }
                }
            });
        });
    </script>
    @endif

</div>