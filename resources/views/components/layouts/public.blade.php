<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Satu Data' }} — Barito Utara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">


  <!-- <livewire:welcome.navigation />   -->
   <div class="sticky top-0 z-50">
    <livewire:welcome.navigation />
</div>

  <!-- Script navbar: toggle hidden/flex pada mobile-nav -->
  <script>
    const btn  = document.getElementById('hamburger-btn');
    const nav  = document.getElementById('mobile-nav');
    const icon = document.getElementById('hamburger-icon');

    btn.addEventListener('click', () => {
      // Cek apakah nav sedang tersembunyi
      const isHidden = nav.classList.contains('hidden');

      if (isHidden) {
        // Buka menu
        nav.classList.remove('hidden');
        nav.classList.add('flex');
        icon.classList.replace('fa-bars', 'fa-xmark'); // ☰ → ✕
      } else {
        // Tutup menu
        nav.classList.add('hidden');
        nav.classList.remove('flex');
        icon.classList.replace('fa-xmark', 'fa-bars'); // ✕ → ☰
      }
    });

    // Tutup menu otomatis kalau layar diperbesar ke desktop
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) {
        nav.classList.add('hidden');
        nav.classList.remove('flex');
        icon.classList.replace('fa-xmark', 'fa-bars');
      }
    });
  </script>

     <main class="flex-1">
        {{ $slot }}   {{-- konten diisi oleh Livewire component --}}
    </main>

    <footer class="bg-blue-900 text-white">
        {{-- Main Footer --}}
        <div class="max-w-6xl mx-auto px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Kolom 1: Identitas --}}
                <div>
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-database text-blue-900"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-300 uppercase tracking-widest">Sistem Informasi</p>
                            <h3 class="font-bold text-sm leading-tight">Satu Data Statistik Sektoral</h3>
                            <p class="text-xs text-yellow-400">Pemerintah Kabupaten Barito Utara</p>
                        </div>
                    </div>
                    <p class="text-xs text-blue-300 leading-relaxed">
                        Portal data terpadu Pemerintah Kabupaten Barito Utara untuk mendukung keterbukaan informasi publik dan pengambilan keputusan berbasis data.
                    </p>
                </div>

                {{-- Kolom 2: Navigasi --}}
                <div>
                    <h4 class="text-sm font-bold mb-4 text-white uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-xs text-blue-300 hover:text-yellow-400 transition flex items-center gap-2">
                                <i class="fas fa-home w-4"></i> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('datasektoral.index') }}" class="text-xs text-blue-300 hover:text-yellow-400 transition flex items-center gap-2">
                                <i class="fas fa-database w-4"></i> Data Sektoral
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('opd.datasets.index') }}" class="text-xs text-blue-300 hover:text-yellow-400 transition flex items-center gap-2">
                                <i class="fas fa-building w-4"></i> OPD
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('sectors.index') }}" class="text-xs text-blue-300 hover:text-yellow-400 transition flex items-center gap-2">
                                <i class="fas fa-chart-bar w-4"></i> Sektor
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kolom 3: Kontak --}}
                <div>
                    <h4 class="text-sm font-bold mb-4 text-white uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 text-xs text-blue-300">
                            <i class="fas fa-map-marker-alt w-4 mt-0.5 text-yellow-400"></i>
                            <span>Jl. Pramuka No.21, Lanjas, Kec. Teweh Tengah, Kabupaten Barito Utara, Kalimantan Tengah</span>
                        </li>
                        <li class="flex items-center gap-3 text-xs text-blue-300">
                            <i class="fas fa-phone w-4 text-yellow-400"></i>
                            <span>(0519) 21901</span>
                        </li>
                        <li class="flex items-center gap-3 text-xs text-blue-300">
                            <i class="fas fa-envelope w-4 text-yellow-400"></i>
                            <span>diskominfosandi@baritoutarakab.go.id</span>
                        </li>
                        <li class="flex items-center gap-3 text-xs text-blue-300">
                            <i class="fas fa-globe w-4 text-yellow-400"></i>
                            <a href="https://diskominfosandi.baritoutarakab.go.id" target="_blank" class="hover:text-yellow-400 transition">
                                baritoutarakab.go.id
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-blue-800">
            <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-blue-400">
                    © {{ date('Y') }} Pemerintah Kabupaten Barito Utara. Hak Cipta Dilindungi.
                </p>
                <p class="text-xs text-blue-500">
                    Dikembangkan oleh <span class="text-yellow-400 font-medium">Dinas Komunikasi dan Informatika</span>
                </p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>