{{-- views/livewire/welcome/navigation.blade.php --}}
<div>
    {{-- ===== NAVBAR ===== --}}
    <header class="bg-blue-900 text-white shadow-md sticky top-0 z-50">

        {{-- Baris utama: brand + desktop nav + hamburger --}}
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

            {{-- Brand --}}
            <div>
                <p class="text-xs text-blue-300 uppercase tracking-widest">Sistem Informasi</p>
                <h1 class="text-lg font-bold leading-tight">Satu Data Statistik Sektoral</h1>
                <p class="text-xs text-yellow-400">Pemerintah Kabupaten Barito Utara</p>
            </div>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex gap-1">

                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded text-sm font-semibold transition
                          {{ request()->routeIs('home') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-home text-xs"></i> Home
                </a>

                <a href="{{ route('datasektoral.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded text-sm font-semibold transition
                          {{ request()->routeIs('datasektoral.index') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-database text-xs"></i> Data Sektoral
                </a>

                <a href="{{ route('opd.datasets.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded text-sm font-semibold transition
                          {{ request()->routeIs('opd.datasets.index') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-building text-xs"></i> OPD
                </a>

                <a href="{{ route('sectors.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded text-sm font-semibold transition
                          {{ request()->routeIs('sectors.index') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-chart-bar text-xs"></i> Sektor
                </a>

            </nav>

            {{-- Hamburger (mobile only) --}}
            <button
                id="hamburger-btn"
                class="md:hidden p-2 rounded hover:bg-blue-800 transition"
                aria-label="Buka menu"
            >
                <i id="hamburger-icon" class="fas fa-bars text-lg"></i>
            </button>

        </div>

        {{-- Mobile Nav --}}
        <nav id="mobile-nav" class="hidden md:hidden flex-col border-t border-blue-800 px-4 py-2 gap-1">

            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
                      {{ request()->routeIs('home') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-800' }}">
                <i class="fas fa-home w-4 text-center"></i> Home
            </a>

            <a href="{{ route('datasektoral.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
                      {{ request()->routeIs('datasektoral.index') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-800' }}">
                <i class="fas fa-database w-4 text-center"></i> Data Sektoral
            </a>

            <a href="{{ route('opd.datasets.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
                      {{ request()->routeIs('opd.datasets.index') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-800' }}">
                <i class="fas fa-building w-4 text-center"></i> OPD
            </a>

            <a href="{{ route('sectors.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
                      {{ request()->routeIs('sectors.index') ? 'bg-yellow-500 text-blue-900' : 'hover:bg-blue-800' }}">
                <i class="fas fa-chart-bar w-4 text-center"></i> Sektor
            </a>

        </nav>

    </header>

</div>