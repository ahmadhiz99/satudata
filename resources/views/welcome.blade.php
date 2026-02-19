<x-layouts.public title="Beranda">

    {{-- ===== HERO ===== --}}
    <section class="bg-blue-900 text-white py-16 px-4">
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-block bg-yellow-500 text-blue-900 text-xs font-bold px-4 py-1 rounded-full mb-4 uppercase tracking-wider">
                <i class="fas fa-map-marker-alt mr-1"></i> Kabupaten Barito Utara
            </span>
            <h2 class="text-4xl font-bold mb-2">Satu Data <span class="text-yellow-400">Statistik Sektoral</span></h2>
            <p class="text-blue-300 mb-8 text-sm">Portal Data Terpadu Pemerintah Kabupaten Barito Utara</p>

            <div x-data="{ query: '' }" class="flex bg-white rounded-lg overflow-hidden shadow-lg">
                <span class="bg-yellow-500 text-blue-900 px-4 py-3 font-bold text-sm whitespace-nowrap">
                    <i class="fas fa-search mr-1"></i> Cari Data
                </span>
                <input
                    type="text"
                    x-model="query"
                    placeholder="Cari dataset, bidang, atau OPD..."
                    class="flex-1 px-4 py-3 text-gray-700 outline-none text-sm"
                />
                <button
                    @click="window.location.href = '{{ route('datasektoral.index') }}?search=' + encodeURIComponent(query)"
                    class="px-4 text-blue-900 hover:bg-gray-100 transition">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </section>


    {{-- ===== STAT CARDS ===== --}}
    <section class="max-w-4xl mx-auto px-4 -mt-6 mb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-white rounded-xl shadow p-5 text-center">
                <div class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-database text-white text-sm"></i>
                </div>
                <p class="text-2xl font-bold text-blue-900">2.4K+</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">Dataset Tersedia</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 text-center">
                <div class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-building text-white text-sm"></i>
                </div>
                <p class="text-2xl font-bold text-blue-900">38</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">OPD Tergabung</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 text-center">
                <div class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-layer-group text-white text-sm"></i>
                </div>
                <p class="text-2xl font-bold text-blue-900">6</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">Bidang Sektoral</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5 text-center">
                <div class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-eye text-white text-sm"></i>
                </div>
                <p class="text-2xl font-bold text-blue-900">12K+</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">Total Views</p>
            </div>

        </div>
    </section>


    {{-- ===== CATEGORY CARDS ===== --}}
    <section class="max-w-6xl mx-auto px-4 mb-16">
        <div class="text-center mb-10">
            <p class="text-blue-900 text-xs font-bold uppercase tracking-widest mb-2">Jelajahi Data</p>
            <h3 class="text-3xl font-bold text-blue-900">Satu Data <span class="text-yellow-500">Statistik Sektoral</span></h3>
            <p class="text-gray-500 text-sm mt-2">Temukan data statistik berdasarkan bidang yang Anda butuhkan</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            <a href="#" class="bg-white rounded-xl shadow p-6 border-t-4 border-blue-500 hover:shadow-lg hover:-translate-y-1 transition-all block">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-graduation-cap text-blue-500 text-xl"></i>
                </div>
                <p class="text-xs text-blue-500 font-bold uppercase tracking-wider mb-1">Bidang</p>
                <h4 class="text-lg font-bold mb-2">Pendidikan</h4>
                <p class="text-gray-500 text-sm mb-4">Data sekolah, murid, guru, dan capaian pendidikan di seluruh kecamatan.</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fas fa-database mr-1 text-blue-400"></i> 284 dataset</span>
                    <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 text-xs">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="#" class="bg-white rounded-xl shadow p-6 border-t-4 border-red-500 hover:shadow-lg hover:-translate-y-1 transition-all block">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-heartbeat text-red-500 text-xl"></i>
                </div>
                <p class="text-xs text-red-500 font-bold uppercase tracking-wider mb-1">Bidang</p>
                <h4 class="text-lg font-bold mb-2">Kesehatan</h4>
                <p class="text-gray-500 text-sm mb-4">Fasilitas kesehatan, tenaga medis, dan indikator kesehatan masyarakat.</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fas fa-database mr-1 text-red-400"></i> 196 dataset</span>
                    <span class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-500 text-xs">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="#" class="bg-white rounded-xl shadow p-6 border-t-4 border-green-500 hover:shadow-lg hover:-translate-y-1 transition-all block">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-seedling text-green-500 text-xl"></i>
                </div>
                <p class="text-xs text-green-500 font-bold uppercase tracking-wider mb-1">Bidang</p>
                <h4 class="text-lg font-bold mb-2">Pertanian & Perkebunan</h4>
                <p class="text-gray-500 text-sm mb-4">Luas panen, produksi komoditas, dan data pertanian seluruh wilayah.</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fas fa-database mr-1 text-green-400"></i> 412 dataset</span>
                    <span class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-500 text-xs">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="#" class="bg-white rounded-xl shadow p-6 border-t-4 border-yellow-500 hover:shadow-lg hover:-translate-y-1 transition-all block">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-road text-yellow-600 text-xl"></i>
                </div>
                <p class="text-xs text-yellow-600 font-bold uppercase tracking-wider mb-1">Bidang</p>
                <h4 class="text-lg font-bold mb-2">Infrastruktur</h4>
                <p class="text-gray-500 text-sm mb-4">Jalan, jembatan, sanitasi, perumahan, dan sarana prasarana wilayah.</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fas fa-database mr-1 text-yellow-500"></i> 153 dataset</span>
                    <span class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 text-xs">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="#" class="bg-white rounded-xl shadow p-6 border-t-4 border-purple-500 hover:shadow-lg hover:-translate-y-1 transition-all block">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-purple-500 text-xl"></i>
                </div>
                <p class="text-xs text-purple-500 font-bold uppercase tracking-wider mb-1">Bidang</p>
                <h4 class="text-lg font-bold mb-2">Ekonomi</h4>
                <p class="text-gray-500 text-sm mb-4">PDRB, ketenagakerjaan, koperasi, perdagangan, dan indikator ekonomi daerah.</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fas fa-database mr-1 text-purple-400"></i> 327 dataset</span>
                    <span class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-500 text-xs">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="#" class="bg-white rounded-xl shadow p-6 border-t-4 border-cyan-500 hover:shadow-lg hover:-translate-y-1 transition-all block">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-utensils text-cyan-500 text-xl"></i>
                </div>
                <p class="text-xs text-cyan-500 font-bold uppercase tracking-wider mb-1">Bidang</p>
                <h4 class="text-lg font-bold mb-2">Ketahanan Pangan</h4>
                <p class="text-gray-500 text-sm mb-4">Ketersediaan pangan, distribusi, konsumsi, dan ketahanan pangan masyarakat.</p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400"><i class="fas fa-database mr-1 text-cyan-400"></i> 218 dataset</span>
                    <span class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center text-cyan-500 text-xs">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>

        </div>
    </section>


    {{-- ===== TRENDING ===== --}}
    <section class="max-w-6xl mx-auto px-4 mb-16">
        <div class="bg-blue-900 rounded-2xl p-8">
            <div class="flex items-end justify-between mb-6 flex-wrap gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">Dataset <span class="text-yellow-400">Trending</span></h3>
                    <p class="text-blue-300 text-sm mt-1">Dataset populer berdasarkan jumlah view</p>
                </div>
                <a href="#" class="text-yellow-400 text-sm font-semibold border border-yellow-400 border-opacity-50 px-4 py-2 rounded-full hover:bg-yellow-400 hover:bg-opacity-10 transition">
                    Lihat semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="flex items-center justify-between bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl px-4 py-3 hover:bg-opacity-20 transition cursor-pointer">
                    <p class="text-white text-xs flex-1 mr-3">JUMLAH PEGAWAI NEGERI SIPIL MENURUT TINGKAT PENDIDIKAN</p>
                    <span class="bg-yellow-400 bg-opacity-20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">825 views</span>
                </div>
                <div class="flex items-center justify-between bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl px-4 py-3 hover:bg-opacity-20 transition cursor-pointer">
                    <p class="text-white text-xs flex-1 mr-3">JUMLAH PRODUKSI TANAMAN PERKEBUNAN TAHUNAN (TON)</p>
                    <span class="bg-yellow-400 bg-opacity-20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">648 views</span>
                </div>
                <div class="flex items-center justify-between bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl px-4 py-3 hover:bg-opacity-20 transition cursor-pointer">
                    <p class="text-white text-xs flex-1 mr-3">PENCARI KERJA YANG TERDAFTAR PADA DINAS TENAGA KERJA</p>
                    <span class="bg-yellow-400 bg-opacity-20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">770 views</span>
                </div>
                <div class="flex items-center justify-between bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl px-4 py-3 hover:bg-opacity-20 transition cursor-pointer">
                    <p class="text-white text-xs flex-1 mr-3">JUMLAH RUMAH BERDASARKAN KONDISI TIAP KECAMATAN</p>
                    <span class="bg-yellow-400 bg-opacity-20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">647 views</span>
                </div>
                <div class="flex items-center justify-between bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl px-4 py-3 hover:bg-opacity-20 transition cursor-pointer">
                    <p class="text-white text-xs flex-1 mr-3">PENCARI KERJA DITEMPATKAN MENURUT TINGKAT PENDIDIKAN</p>
                    <span class="bg-yellow-400 bg-opacity-20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">745 views</span>
                </div>
                <div class="flex items-center justify-between bg-white bg-opacity-10 border border-white border-opacity-10 rounded-xl px-4 py-3 hover:bg-opacity-20 transition cursor-pointer">
                    <p class="text-white text-xs flex-1 mr-3">LUAS PANEN TANAMAN SAYURAN MENURUT KECAMATAN</p>
                    <span class="bg-yellow-400 bg-opacity-20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">646 views</span>
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>