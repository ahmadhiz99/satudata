<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $dataset->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-building mr-1"></i> {{ $dataset->organization->name }}
                </p>
            </div>
            <a href="{{ route('datasets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Dataset Info --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Kategori</p>
                            <p class="text-sm font-semibold" style="color: {{ $dataset->category->color }}">
                                <i class="fas {{ $dataset->category->icon }} mr-1"></i>
                                {{ $dataset->category->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Periode</p>
                            <p class="text-sm font-semibold">{{ $dataset->start_year }} - {{ $dataset->end_year }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Satuan</p>
                            <p class="text-sm font-semibold">{{ $dataset->unit }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Total Records</p>
                            <p class="text-sm font-semibold">{{ $dataset->records()->count() }}</p>
                        </div>
                    </div>

                    @if($dataset->description)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-700">{{ $dataset->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Main Content --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Tab Navigation --}}
                    <div class="flex gap-2 mb-6 border-b border-gray-200">
                        <button
                            wire:click="setTab('tabel')"
                            class="px-6 py-3 font-medium {{ $activeTab === 'tabel' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <i class="fas fa-table mr-2"></i> Tabel
                        </button>
                        <button
                            wire:click="setTab('grafik')"
                            class="px-6 py-3 font-medium {{ $activeTab === 'grafik' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <i class="fas fa-chart-bar mr-2"></i> Grafik
                        </button>
                        <button
                            wire:click="setTab('peta')"
                            class="px-6 py-3 font-medium {{ $activeTab === 'peta' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <i class="fas fa-map mr-2"></i> Peta
                        </button>
                    </div>

                    {{-- Filters --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-red-50 p-4 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten/Kota</label>
                            <select wire:model.live="filterKabupaten"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Semua Kabupaten/Kota</option>
                                @foreach($kabupatens as $kab)
                                    <option value="{{ $kab }}">{{ $kab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select wire:model.live="filterTahun"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Semua Tahun</option>
                                @foreach($tahuns as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button
                                wire:click="$set('filterKabupaten', ''); $set('filterTahun', '')"
                                class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                Reset Filter
                            </button>
                        </div>

                        {{-- ✅ BARU: Tombol Download --}}
                        <div class="flex items-end">
                            <button
                                wire:click="downloadExcel"
                                wire:loading.attr="disabled"
                                style="background-color: #16a34a; color: white; width: 100%; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer;">
                                <span wire:loading.remove wire:target="downloadExcel">
                                    Download Excel
                                    @if($filterKabupaten || $filterTahun) (Filtered) @endif
                                </span>
                                <span wire:loading wire:target="downloadExcel">
                                    Menyiapkan...
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Tab Content --}}
                    <div>

                        {{-- TABEL --}}
                        @if($activeTab === 'tabel')
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-red-700 text-white">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                                Kabupaten/Kota
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                                Kode
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                                Tahun
                                            </th>
                                            @foreach($dataset->columns as $key => $label)
                                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                                                    {{ $label }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($records as $record)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $record->kabupaten_kota }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    {{ $record->kode_kabkota }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                                    {{ $record->tahun }}
                                                </td>
                                                @foreach($dataset->columns as $key => $label)
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                                        {{ number_format($record->values[$key] ?? 0, 0, ',', '.') }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($dataset->columns) + 3 }}" class="px-6 py-4 text-center text-gray-500">
                                                    Tidak ada data
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $records->links() }}
                            </div>
                        @endif

                        {{-- GRAFIK --}}
                        @if($activeTab === 'grafik')
                            <div class="bg-white p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">
                                    Top 10 {{ $dataset->title }}
                                    @if($filterTahun)
                                        <span class="text-sm font-normal text-gray-600">- Tahun {{ $filterTahun }}</span>
                                    @endif
                                    @if($filterKabupaten)
                                        <span class="text-sm font-normal text-gray-600">- {{ $filterKabupaten }}</span>
                                    @endif
                                </h3>

                                @if($chartData->isEmpty())
                                    <div class="text-center py-12 text-gray-500">
                                        <i class="fas fa-chart-bar text-4xl mb-3"></i>
                                        <p>Tidak ada data untuk filter yang dipilih</p>
                                    </div>
                                @else
                                    <div id="chartWrapper" style="position: relative; height: 400px;">
                                        <canvas id="myDatasetChart"></canvas>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Script Chart --}}
                        <script>
                            let currentChartInstance = null;

                            document.addEventListener('livewire:init', () => {
                                Livewire.on('renderChart', (payload) => {
                                    setTimeout(() => createMyChart(payload), 100);
                                });
                            });

                            function createMyChart(payload) {
                                const { chartData, unit, filterTahun, filterKabupaten } = payload;

                                const canvas = document.getElementById('myDatasetChart');
                                if (!canvas || typeof Chart === 'undefined') return;

                                if (currentChartInstance) {
                                    currentChartInstance.destroy();
                                    currentChartInstance = null;
                                }

                                if (!chartData || chartData.length === 0) {
                                    const wrapper = document.getElementById('chartWrapper');
                                    if (wrapper) {
                                        wrapper.innerHTML = '<p class="text-center text-gray-500 py-8">Tidak ada data untuk filter yang dipilih</p>';
                                    }
                                    return;
                                }

                                const labels = chartData.map(item => item.kabupaten_kota);
                                const values = chartData.map(item => parseFloat(item.nilai_utama));

                                let titleText = 'Top 10 Data';
                                if (filterTahun) titleText += ' - Tahun ' + filterTahun;
                                if (filterKabupaten) titleText += ' - ' + filterKabupaten;

                                currentChartInstance = new Chart(canvas, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: unit,
                                            data: values,
                                            backgroundColor: 'rgba(220, 38, 38, 0.8)',
                                            borderColor: 'rgba(220, 38, 38, 1)',
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: true, position: 'top' },
                                            title: {
                                                display: !!(filterTahun || filterKabupaten),
                                                text: titleText
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: ctx => ctx.parsed.y.toLocaleString('id-ID') + ' ' + unit
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: { callback: val => val.toLocaleString('id-ID') }
                                            },
                                            x: {
                                                ticks: { maxRotation: 45, minRotation: 45 }
                                            }
                                        }
                                    }
                                });
                            }
                        </script>

                        {{-- PETA --}}
                        @if($activeTab === 'peta')
                            <div class="bg-white p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">
                                    Peta {{ $dataset->title }}
                                    @if($filterTahun)
                                        <span class="text-sm font-normal text-gray-600">- Tahun {{ $filterTahun }}</span>
                                    @endif
                                </h3>

                                <div wire:ignore id="mapContainer" style="height: 600px; width: 100%; border-radius: 8px; border: 1px solid #e5e7eb;"></div>

                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-sm font-semibold mb-2">Legend</h4>
                                    <div class="flex flex-wrap gap-4 text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-green-300 border border-green-500"></div>
                                            <span>Rendah</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-yellow-300 border border-yellow-500"></div>
                                            <span>Sedang</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-orange-300 border border-orange-500"></div>
                                            <span>Tinggi</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-red-300 border border-red-500"></div>
                                            <span>Sangat Tinggi</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-gray-200 border border-gray-400"></div>
                                            <span>No Data</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif

                        {{-- Script Peta --}}
                        @script
                        <script>
                            let mapInstance = null;
                            let geoJsonLayer = null;
                            let mapInitialized = false;

                            if (!mapInitialized) {
                                Livewire.on('renderMap', (payload) => {  // ← terima payload
                                    // ✅ Update data global dari payload, bukan dari DOM script tag
                                    window.currentMapData = payload.mapData || {};
                                    window.mapUnit = payload.unit || '';
                                    setTimeout(createMyMap, 300);
                                });
                                mapInitialized = true;
                            }

                            function createMyMap() {
                                const mapContainer = document.getElementById('mapContainer');
                                if (!mapContainer || typeof L === 'undefined') return;

                                if (mapInstance) {
                                    mapInstance.remove();
                                    mapInstance = null;
                                    geoJsonLayer = null;
                                }

                                mapInstance = L.map('mapContainer', {
                                    center: [-1.5, 113.5],
                                    zoom: 8,
                                    minZoom: 7,
                                    maxZoom: 12
                                });

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '© OpenStreetMap contributors',
                                    maxZoom: 18,
                                }).addTo(mapInstance);

                                setTimeout(() => { mapInstance.invalidateSize(); }, 100);

                                const mapData = window.currentMapData || {};
                                const unit = window.mapUnit || '';

                                console.log('🗺️ Map Data Keys:', Object.keys(mapData));

                                // ✅ Normalize nama untuk matching fleksibel
                                function normalize(str) {
                                    if (!str) return '';
                                    return str.toString().toLowerCase()
                                        .replace(/kabupaten|kota|kab\.|kab/gi, '')
                                        .replace(/[^a-z0-9]/gi, '')
                                        .trim();
                                }

                                function findMatchingData(featureName) {
                                    if (!featureName || !mapData) return null;
                                    const cleanFeature = normalize(featureName);

                                    for (const [key, value] of Object.entries(mapData)) {
                                        if (normalize(key) === cleanFeature) return value;
                                        const namaInValue = value.kabupaten_kota || value.nama;
                                        if (namaInValue && normalize(namaInValue) === cleanFeature) return value;
                                    }
                                    return null;
                                }

                                const values = Object.values(mapData)
                                    .map(item => parseFloat(item.nilai_utama))
                                    .filter(v => !isNaN(v));
                                const minValue = values.length > 0 ? Math.min(...values) : 0;
                                const maxValue = values.length > 0 ? Math.max(...values) : 100;

                                function getColor(value) {
                                    if (value === null || value === undefined || isNaN(value)) return '#e5e7eb';
                                    const normalized = (value - minValue) / (maxValue - minValue || 1);
                                    if (normalized > 0.75) return '#fca5a5';
                                    if (normalized > 0.50) return '#fdba74';
                                    if (normalized > 0.25) return '#fde047';
                                    return '#86efac';
                                }

                                fetch('/geojson/kalteng.json')
                                    .then(r => { if (!r.ok) throw new Error('GeoJSON tidak ditemukan'); return r.json(); })
                                    .then(geojson => {
                                        geoJsonLayer = L.geoJSON(geojson, {
                                            style(feature) {
                                                const data = findMatchingData(feature.properties.name);
                                                const value = data ? parseFloat(data.nilai_utama) : null;
                                                return {
                                                    fillColor: getColor(value),
                                                    weight: 2,
                                                    opacity: 1,
                                                    color: '#dc2626',
                                                    dashArray: '',
                                                    fillOpacity: 0.7
                                                };
                                            },
                                            onEachFeature(feature, layer) {
                                                const kabupaten = feature.properties.name;
                                                const kode = feature.properties.kode || '-';
                                                const data = findMatchingData(kabupaten);

                                                let popupContent = `
                                                    <div style="min-width:200px; padding:8px;">
                                                        <h3 style="font-weight:bold; font-size:14px; margin-bottom:6px;">${kabupaten}</h3>
                                                        <p style="font-size:12px; color:#6b7280; margin-bottom:6px;">Kode: ${kode}</p>`;

                                                if (data) {
                                                    const nilaiFormatted = parseFloat(data.nilai_utama).toLocaleString('id-ID');
                                                    popupContent += `
                                                        <hr style="margin:6px 0; border-color:#d1d5db;">
                                                        <p style="font-size:13px;"><strong>Tahun:</strong> ${data.tahun}</p>
                                                        <p style="font-size:13px;"><strong>Value:</strong>
                                                            <span style="font-size:16px; font-weight:bold; color:#4f46e5;">${nilaiFormatted}</span>
                                                            ${unit}
                                                        </p>`;
                                                } else {
                                                    popupContent += `<p style="font-size:12px; color:#ef4444; margin-top:6px; font-style:italic;">Data tidak tersedia</p>`;
                                                }

                                                popupContent += `</div>`;
                                                layer.bindPopup(popupContent, { maxWidth: 300 });

                                                layer.on({
                                                    mouseover(e) {
                                                        e.target.setStyle({ weight: 3, color: '#1e293b', fillOpacity: 0.9 });
                                                        e.target.bringToFront();
                                                    },
                                                    mouseout(e) {
                                                        geoJsonLayer.resetStyle(e.target);
                                                    }
                                                });
                                            }
                                        }).addTo(mapInstance);

                                        mapInstance.fitBounds(geoJsonLayer.getBounds(), { padding: [20, 20] });
                                        console.log('✅ Map created successfully!');
                                    })
                                    .catch(error => {
                                        console.error('❌ Error:', error);
                                        mapContainer.innerHTML = `
                                            <div style="padding:16px; text-align:center; color:#ef4444;">
                                                <p>Gagal memuat GeoJSON: ${error.message}</p>
                                            </div>`;
                                    });
                            }
                        </script>
                        @endscript

                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if ('{{ $activeTab }}' === 'grafik') {
                setTimeout(() => {
                    if (typeof createMyChart === 'function') createMyChart();
                }, 500);
            }
        });
    </script>
    @endscript
</div>