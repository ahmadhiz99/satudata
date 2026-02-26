<div>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ══ Header ══════════════════════════════════════════════════ --}}
            <div class="bg-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="font-semibold text-xl text-white leading-tight">
                            {{ $dataset->title }}
                        </h2>
                        <p class="text-sm text-blue-200 mt-1">
                            <i class="fas fa-building mr-1"></i> {{ $dataset->organization->name ?? '-' }}
                        </p>
                    </div>
                    <a href="{{ route('datasektoral.index') }}"
                       class="inline-flex items-center gap-2 text-sm text-blue-200 hover:text-white hover:bg-white hover:bg-opacity-10 px-3 py-1.5 rounded-lg transition-all duration-200">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- ══ Tab Navigation ══════════════════════════════════════════ --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="flex border-b border-gray-200 px-6">
                    <button wire:click="setTab('info')"
                        class="px-5 py-4 text-sm font-medium transition border-b-2 -mb-px
                            {{ $activeTab === 'info' ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-info-circle mr-2"></i> Informasi
                    </button>

                    @if($linkedDataset)
                        @php $vt = $linkedDataset->visualize_types ?? 'table'; @endphp

                        @if(str_contains($vt, 'table'))
                            <button wire:click="setTab('tabel')"
                                class="px-5 py-4 text-sm font-medium transition border-b-2 -mb-px
                                    {{ $activeTab === 'tabel' ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                <i class="fas fa-table mr-2"></i> Tabel
                            </button>
                        @endif

                        @if(str_contains($vt, 'chart'))
                            <button wire:click="setTab('grafik')"
                                class="px-5 py-4 text-sm font-medium transition border-b-2 -mb-px
                                    {{ $activeTab === 'grafik' ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                <i class="fas fa-chart-bar mr-2"></i> Grafik
                            </button>
                        @endif

                        @if(str_contains($vt, 'map'))
                            <button wire:click="setTab('peta')"
                                class="px-5 py-4 text-sm font-medium transition border-b-2 -mb-px
                                    {{ $activeTab === 'peta' ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                <i class="fas fa-map mr-2"></i> Peta
                            </button>
                        @endif
                    @endif
                </div>

                <div class="p-6">

                    {{-- ══════════ TAB: INFO ══════════════════════════════ --}}
                    @if($activeTab === 'info')
                        <div class="space-y-6">

                            {{-- Metadata --}}
                            <div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                    Metadata Dataset
                                </h3>
                                <div class="divide-y divide-gray-100 border border-gray-100 rounded-lg overflow-hidden">
                                    <div class="flex items-center px-5 py-3">
                                        <p class="text-sm text-gray-400 w-44 shrink-0">Nama Dataset</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->title }}</p>
                                    </div>
                                    <div class="flex items-center px-5 py-3">
                                        <p class="text-sm text-gray-400 w-44 shrink-0">OPD</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->organization->name ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center px-5 py-3">
                                        <p class="text-sm text-gray-400 w-44 shrink-0">Kategori</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->category->name ?? '-' }}</p>
                                    </div>
                                    @if($linkedDataset)
                                        <div class="flex items-center px-5 py-3">
                                            <p class="text-sm text-gray-400 w-44 shrink-0">Periode</p>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $linkedDataset->start_year }}
                                                @if($linkedDataset->end_year && $linkedDataset->end_year != $linkedDataset->start_year)
                                                    – {{ $linkedDataset->end_year }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex items-center px-5 py-3">
                                            <p class="text-sm text-gray-400 w-44 shrink-0">Satuan</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ $linkedDataset->unit ?: '-' }}</p>
                                        </div>
                                        <div class="flex items-center px-5 py-3">
                                            <p class="text-sm text-gray-400 w-44 shrink-0">Frekuensi</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ $linkedDataset->frequency ?: '-' }}</p>
                                        </div>
                                        <div class="flex items-center px-5 py-3">
                                            <p class="text-sm text-gray-400 w-44 shrink-0">Total Records</p>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ number_format($linkedDataset->records()->count()) }} baris
                                            </p>
                                        </div>
                                        @if($linkedDataset->description)
                                            <div class="flex items-start px-5 py-3">
                                                <p class="text-sm text-gray-400 w-44 shrink-0">Deskripsi</p>
                                                <p class="text-sm text-gray-700">{{ $linkedDataset->description }}</p>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="flex items-center px-5 py-3">
                                        <p class="text-sm text-gray-400 w-44 shrink-0">Tanggal Upload</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="flex items-center px-5 py-3">
                                        <p class="text-sm text-gray-400 w-44 shrink-0">Terakhir Diubah</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $dataset->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="flex items-center px-5 py-3">
                                        <p class="text-sm text-gray-400 w-44 shrink-0">Total Views</p>
                                        <p class="text-sm font-semibold text-gray-800">
                                            <i class="fas fa-eye mr-1 text-gray-400"></i>
                                            {{ number_format($dataset->view_count) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- File Download --}}
                            <div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                                    File
                                </h3>
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
                                            class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50 shrink-0">
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
                    @endif

                    {{-- ══════════ TAB: TABEL ══════════════════════════════ --}}
                    @if($activeTab === 'tabel' && $linkedDataset)

                        {{-- Filter bar --}}
                        <div class="flex flex-wrap items-end gap-3 mb-6 bg-blue-50 border border-blue-100 p-4 rounded-xl">
                            {{-- Search --}}
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Cari Data
                                </label>
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                                    <input type="text"
                                           wire:model.live.debounce.400ms="search"
                                           placeholder="Cari di semua kolom..."
                                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                                </div>
                            </div>

                            {{-- Tahun --}}
                            <div class="w-40 shrink-0">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Tahun
                                </label>
                                <select wire:model.live="filterTahun"
                                    class="w-full py-2 border border-gray-300 rounded-lg shadow-sm text-sm bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahuns as $tahun)
                                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Divider --}}
                            <div class="h-9 w-px bg-blue-200 shrink-0 hidden md:block"></div>

                            {{-- Reset --}}
                            <button wire:click="$set('filterTahun', ''); $set('search', '')"
                                class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                                <i class="fas fa-rotate-left text-xs"></i> Reset
                            </button>

                            {{-- Download --}}
                            <button wire:click="downloadExcel" wire:loading.attr="disabled"
                                class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition shadow-sm disabled:opacity-50">
                                <span wire:loading.remove wire:target="downloadExcel">
                                    <i class="fas fa-file-excel mr-1"></i> Download Excel
                                </span>
                                <span wire:loading wire:target="downloadExcel">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Menyiapkan...
                                </span>
                            </button>
                        </div>

                        {{-- Tabel dinamis --}}
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-800 text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-12">No</th>
                                        @foreach($columns as $slug => $label)
                                            <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider whitespace-nowrap">
                                                {{ $label }}
                                                @if($linkedDataset->unit)
                                                    <span class="font-normal opacity-70 normal-case">({{ $linkedDataset->unit }})</span>
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($records as $record)
                                        @php
                                            $values = is_array($record->values)
                                                ? $record->values
                                                : json_decode($record->values, true);
                                        @endphp
                                        <tr class="hover:bg-blue-50 transition">
                                            <td class="px-4 py-3 text-center text-sm text-gray-400">
                                                {{ $records->firstItem() + $loop->index }}
                                            </td>
                                            @foreach($columns as $slug => $label)
                                                @php $val = $values[$slug] ?? null; @endphp
                                                <td class="px-4 py-3 text-sm text-gray-900 {{ is_numeric($val) ? 'text-center' : 'text-center' }}">
                                                    @if(is_numeric($val))
                                                        {{ number_format($val, 0, ',', '.') }}
                                                    @else
                                                        {{ $val ?? '-' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($columns) + 1 }}"
                                                class="px-6 py-12 text-center text-gray-400">
                                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                                Tidak ada data{{ $search || $filterTahun ? ' untuk filter yang dipilih' : '' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                @if($records && $records->total() > 0)
                                    Menampilkan {{ $records->firstItem() }}–{{ $records->lastItem() }}
                                    dari {{ number_format($records->total()) }} records
                                @endif
                            </p>
                            {{ $records?->links() }}
                        </div>
                    @endif

                    {{-- ══════════ TAB: GRAFIK ══════════════════════════════ --}}
                    @if($activeTab === 'grafik' && $linkedDataset)
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-800">{{ $linkedDataset->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        Top 15 nilai tertinggi
                                        @if($filterTahun) · Tahun {{ $filterTahun }} @endif
                                    </p>
                                </div>
                                <div class="w-40">
                                    <select wire:model.live="filterTahun"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="">Semua Tahun</option>
                                        @foreach($tahuns as $tahun)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if($chartData->isEmpty())
                                <div class="text-center py-12 text-gray-400">
                                    <i class="fas fa-chart-bar text-4xl mb-3 block"></i>
                                    <p>Tidak ada data untuk ditampilkan</p>
                                </div>
                            @else
                                <div style="position:relative; height:420px;">
                                    <canvas id="myDatasetChart"></canvas>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ══════════ TAB: PETA ══════════════════════════════ --}}
                    @if($activeTab === 'peta' && $linkedDataset)
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-800">Peta — {{ $linkedDataset->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Visualisasi data berdasarkan wilayah</p>
                                </div>
                                <div class="w-40">
                                    <select wire:model.live="filterTahun"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="">Semua Tahun</option>
                                        @foreach($tahuns as $tahun)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div wire:ignore id="mapContainer"
                                style="height:500px;width:100%;border-radius:8px;border:1px solid #e5e7eb;"></div>

                            {{-- Legend --}}
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Legend</h4>
                                <div class="flex flex-wrap gap-4 text-xs">
                                    @foreach([['#86efac','#22c55e','Rendah'],['#fde047','#eab308','Sedang'],['#fdba74','#f97316','Tinggi'],['#fca5a5','#ef4444','Sangat Tinggi'],['#e5e7eb','#9ca3af','No Data']] as [$bg, $border, $label])
                                        <div class="flex items-center gap-2">
                                            <div style="width:14px;height:14px;background:{{ $bg }};border:1px solid {{ $border }};border-radius:2px;"></div>
                                            <span class="text-gray-600">{{ $label }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- ══ Scripts ══════════════════════════════════════════════════ --}}

    {{-- Chart.js --}}
    <script>
        let currentChartInstance = null;

        document.addEventListener('livewire:init', () => {
            Livewire.on('renderChart', (payload) => {
                setTimeout(() => createDatasetChart(payload), 100);
            });
        });

        function createDatasetChart(payload) {
            const { chartData, unit } = payload;
            const canvas = document.getElementById('myDatasetChart');
            if (!canvas || typeof Chart === 'undefined') return;

            if (currentChartInstance) {
                currentChartInstance.destroy();
                currentChartInstance = null;
            }

            if (!chartData || chartData.length === 0) return;

            const labels = chartData.map(item => item.label || '-');
            const values = chartData.map(item => parseFloat(item.nilai_utama) || 0);

            currentChartInstance = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: unit || 'Nilai',
                        data: values,
                        backgroundColor: 'rgba(30, 58, 95, 0.8)',
                        borderColor: 'rgba(30, 58, 95, 1)',
                        borderWidth: 2,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.parsed.y.toLocaleString('id-ID') + (unit ? ' ' + unit : '')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: val => val.toLocaleString('id-ID') }
                        },
                        x: { ticks: { maxRotation: 45, minRotation: 20 } }
                    }
                }
            });
        }
    </script>

    {{-- Leaflet Map --}}
    @script
    <script>
        let mapInstance    = null;
        let geoJsonLayer   = null;
        let mapInitialized = false;

        if (!mapInitialized) {
            Livewire.on('renderMap', (payload) => {
                window.currentMapData = payload.mapData || {};
                window.mapUnit        = payload.unit   || '';
                setTimeout(createDatasektoralMap, 300);
            });
            mapInitialized = true;
        }

        function createDatasektoralMap() {
            const mapContainer = document.getElementById('mapContainer');
            if (!mapContainer || typeof L === 'undefined') return;

            if (mapInstance) {
                mapInstance.remove();
                mapInstance  = null;
                geoJsonLayer = null;
            }

            mapInstance = L.map('mapContainer', {
                center: [-0.95, 114.85],
                zoom: 9,
                minZoom: 7,
                maxZoom: 15,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18,
            }).addTo(mapInstance);

            setTimeout(() => mapInstance.invalidateSize(), 100);

            const mapData = window.currentMapData || {};
            const unit    = window.mapUnit        || '';

            function normalize(str) {
                if (!str) return '';
                return str.toString().toLowerCase().replace(/[^a-z0-9]/gi, '').trim();
            }

            function findMatchingData(featureName) {
                if (!featureName) return null;
                const clean = normalize(featureName);
                for (const [key, value] of Object.entries(mapData)) {
                    if (normalize(key) === clean) return value;
                }
                return null;
            }

            const allValues = Object.values(mapData)
                .map(item => parseFloat(item.nilai_utama))
                .filter(v => !isNaN(v) && v > 0);

            const minVal = allValues.length ? Math.min(...allValues) : 0;
            const maxVal = allValues.length ? Math.max(...allValues) : 100;

            function getColor(value) {
                if (!value || isNaN(value) || value <= 0) return '#e5e7eb';
                const norm = (value - minVal) / (maxVal - minVal || 1);
                if (norm > 0.75) return '#fca5a5';
                if (norm > 0.50) return '#fdba74';
                if (norm > 0.25) return '#fde047';
                return '#86efac';
            }

            fetch('/geojson/barito_utara.json')
                .then(r => {
                    if (!r.ok) throw new Error('GeoJSON tidak ditemukan');
                    return r.json();
                })
                .then(geojson => {
                    geoJsonLayer = L.geoJSON(geojson, {
                        style(feature) {
                            const data  = findMatchingData(feature.properties.name);
                            const value = data ? parseFloat(data.nilai_utama) : null;
                            return {
                                fillColor:   getColor(value),
                                weight:      2,
                                color:       '#1e3a5f',
                                fillOpacity: 0.7,
                            };
                        },
                        onEachFeature(feature, layer) {
                            const nama = feature.properties.name;
                            const data = findMatchingData(nama);

                            let popup = `<div style="min-width:180px;padding:6px;">
                                <p style="font-weight:bold;font-size:13px;margin-bottom:4px;">${nama}</p>`;

                            if (data && parseFloat(data.nilai_utama) > 0) {
                                const nilai = parseFloat(data.nilai_utama).toLocaleString('id-ID');
                                popup += `
                                    <hr style="margin:4px 0;border-color:#e5e7eb;">
                                    <p style="font-size:12px;"><strong>Tahun:</strong> ${data.tahun}</p>
                                    <p style="font-size:13px;font-weight:bold;color:#1e3a5f;">${nilai} ${unit}</p>`;
                            } else {
                                popup += `<p style="font-size:12px;color:#9ca3af;font-style:italic;">Data belum tersedia</p>`;
                            }

                            popup += `</div>`;
                            layer.bindPopup(popup, { maxWidth: 250 });

                            layer.on({
                                mouseover(e) {
                                    e.target.setStyle({ weight: 3, color: '#0f172a', fillOpacity: 0.9 });
                                    e.target.bringToFront();
                                },
                                mouseout(e) { geoJsonLayer.resetStyle(e.target); },
                            });
                        },
                    }).addTo(mapInstance);

                    mapInstance.fitBounds(geoJsonLayer.getBounds(), { padding: [20, 20] });
                })
                .catch(err => {
                    mapContainer.innerHTML = `
                        <div style="padding:24px;text-align:center;color:#ef4444;">
                            <p style="font-weight:bold;">Gagal memuat peta</p>
                            <p style="font-size:12px;color:#6b7280;margin-top:4px;">${err.message}</p>
                        </div>`;
                });
        }
    </script>
    @endscript

</div>