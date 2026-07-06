<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives – Super Nintendo Entertainment System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800;900&family=Rajdhani:wght@500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        retro: {
                            bg: '#0a051b',
                            card: '#120b2d',
                            border: '#3b257e',
                            cyan: '#00b4c8',
                            magenta: '#ff007f',
                            purple: '#9d4edd',
                            green: '#39ff14',
                            yellow: '#ffd700',
                            red: '#ff3333'
                        }
                    },
                    fontFamily: {
                        arcade: ['Orbitron', 'sans-serif'],
                        tech: ['Share Tech Mono', 'monospace'],
                        sans: ['Rajdhani', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- External Retro CSS -->
    <link rel="stylesheet" href="{{ asset('css/retro.css') }}">
</head>
<body class="dashboard-body font-sans antialiased">

    <!-- Top Neon Bar -->
    <div class="h-1 w-full bg-retro-cyan"></div>

    <div class="w-full max-w-none px-4 sm:px-8 lg:px-12 py-6">

        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-center mb-8 pb-6 border-b border-retro-border border-opacity-40">
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <div class="p-3 bg-retro-card rounded-lg border border-retro-cyan">
                    <i class="fa-solid fa-tv text-2xl text-retro-cyan"></i>
                </div>
                <div>
                    <h1 class="font-arcade text-3xl font-extrabold uppercase tracking-wider text-retro-cyan">
                        Retro Drives
                    </h1>
                    <p class="font-tech text-xs text-retro-cyan tracking-widest uppercase">Super Nintendo Entertainment System</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Valet Indicator -->
                <div class="flex items-center space-x-2 bg-retro-card px-4 py-2 rounded-full border border-retro-border border-opacity-60 text-xs font-tech">
                    <span class="h-2 w-2 rounded-full bg-retro-green animate-ping"></span>
                    <span class="text-retro-green uppercase">Valet Active</span>
                    <span class="text-gray-500">|</span>
                    <span class="text-gray-400">retro-gaming.test</span>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-magenta text-gray-400 hover:text-white text-xs font-tech uppercase tracking-wider rounded-full transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Exit Portal</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Top Level Device Groups -->
        <div class="flex flex-wrap items-center gap-2 mb-4 border-b border-retro-border border-opacity-20 pb-4">
            <a href="{{ url('/admin?group=arcade&system=mame') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-gamepad mr-1"></i> Arcade
            </a>
            <a href="{{ route('admin.console') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-cyan text-black">
                <i class="fa-solid fa-tv mr-1"></i> Console
            </a>
            <a href="{{ url('/admin?group=home_computer') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-computer mr-1"></i> Home Computer
            </a>
            <a href="{{ route('admin.orders') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-truck-ramp-box mr-1"></i> Customer Orders
            </a>
        </div>

        <!-- Console Sub Tabs (SNES active) -->
        <div class="flex flex-wrap items-center gap-2 mb-6 pb-4">
            <a href="{{ route('admin.snes') }}" class="px-4 py-2 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card border border-retro-cyan text-retro-cyan">
                SNES
            </a>
            {{-- Future consoles can be added here --}}
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-retro-card rounded-xl border border-retro-border p-4 text-center">
                <div class="text-2xl font-arcade font-bold text-retro-cyan">{{ number_format($stats['total']) }}</div>
                <div class="text-xs font-tech text-gray-400 uppercase tracking-wider mt-1">Total ROMs</div>
            </div>
            <div class="bg-retro-card rounded-xl border border-retro-border p-4 text-center">
                <div class="text-2xl font-arcade font-bold text-retro-purple">{{ $stats['regions'] }}</div>
                <div class="text-xs font-tech text-gray-400 uppercase tracking-wider mt-1">Regions</div>
            </div>
            <div class="bg-retro-card rounded-xl border border-retro-border p-4 text-center col-span-2 sm:col-span-1">
                <div class="text-2xl font-arcade font-bold text-retro-yellow">SNES</div>
                <div class="text-xs font-tech text-gray-400 uppercase tracking-wider mt-1">Super Nintendo</div>
            </div>
        </div>

        <!-- Search & Filter -->
        <section class="bg-retro-card rounded-xl border border-retro-border p-5 mb-6">
            <form method="GET" action="{{ route('admin.snes') }}" class="flex flex-wrap gap-3 items-end">
                <!-- Search -->
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Search ROMs</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="ROM name, region…"
                        class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs placeholder-gray-600 font-tech outline-none transition"
                    >
                </div>

                <!-- Region Filter -->
                <div class="min-w-[140px]">
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Region</label>
                    <select name="region" class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs select-custom">
                        <option value="">All Regions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}" {{ request('region') === $region ? 'selected' : '' }}>{{ $region }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div class="min-w-[130px]">
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Sort By</label>
                    <select name="sort_by" class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs select-custom">
                        <option value="rom"          {{ request('sort_by','rom') === 'rom'          ? 'selected' : '' }}>ROM Name</option>
                        <option value="region"       {{ request('sort_by','rom') === 'region'       ? 'selected' : '' }}>Region</option>
                        <option value="release_date" {{ request('sort_by','rom') === 'release_date' ? 'selected' : '' }}>Release Date</option>
                        <option value="size_mb"      {{ request('sort_by','rom') === 'size_mb'      ? 'selected' : '' }}>Size</option>
                    </select>
                </div>

                <div class="min-w-[110px]">
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Order</label>
                    <select name="sort_order" class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs select-custom">
                        <option value="asc"  {{ request('sort_order','asc') === 'asc'  ? 'selected' : '' }}>A → Z</option>
                        <option value="desc" {{ request('sort_order','asc') === 'desc' ? 'selected' : '' }}>Z → A</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-retro-cyan hover:bg-opacity-85 rounded-lg text-black font-tech text-xs uppercase tracking-wider transition">
                        Apply
                    </button>
                    <a href="{{ route('admin.snes') }}" class="px-3 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-400 hover:text-white text-xs transition flex items-center justify-center" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </section>

        <!-- ROM Table -->
        <main class="bg-retro-card rounded-xl border border-retro-border overflow-hidden">
            <div class="px-6 py-4 border-b border-retro-border border-opacity-40 flex items-center justify-between">
                <h2 class="font-tech text-sm text-white uppercase tracking-widest">
                    Super Nintendo Entertainment System
                    <span class="ml-2 text-gray-500">·</span>
                    <span class="ml-2 text-retro-cyan">{{ number_format($roms->total()) }} ROMs</span>
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-retro-border border-opacity-40">
                            @php
                                $sortBy    = request('sort_by', 'rom');
                                $sortOrder = request('sort_order', 'asc');
                                $nextOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
                                $cols = [
                                    'rom'          => 'ROM / Title',
                                    'region'       => 'Region',
                                    'release_date' => 'Release Date',
                                    'size_mb'      => 'Size',
                                    'crc32'        => 'CRC32',
                                ];
                            @endphp
                            @foreach($cols as $colKey => $colLabel)
                                <th class="px-4 py-3 text-left">
                                    @if($colKey !== 'crc32')
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => $colKey, 'sort_order' => ($sortBy === $colKey ? $nextOrder : 'asc')]) }}"
                                           class="font-tech text-xs uppercase tracking-wider {{ $sortBy === $colKey ? 'text-retro-cyan' : 'text-gray-500 hover:text-white' }} transition flex items-center space-x-1">
                                            <span>{{ $colLabel }}</span>
                                            @if($sortBy === $colKey)
                                                <i class="fa-solid fa-chevron-{{ $sortOrder === 'asc' ? 'up' : 'down' }} text-[10px]"></i>
                                            @endif
                                        </a>
                                    @else
                                        <span class="font-tech text-xs uppercase tracking-wider text-gray-500">{{ $colLabel }}</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-retro-border divide-opacity-20">
                        @forelse($roms as $snes)
                            <tr class="hover:bg-retro-bg hover:bg-opacity-40 transition group">
                                <td class="px-4 py-3">
                                    <span class="font-tech text-xs text-white">{{ $snes->rom }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($snes->region)
                                        <span class="px-2 py-0.5 rounded-full bg-retro-bg border border-retro-border text-xs font-tech text-gray-300">
                                            {{ $snes->region }}
                                        </span>
                                    @else
                                        <span class="text-gray-600 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-tech text-xs text-gray-400">{{ $snes->release_date ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-tech text-xs text-gray-400">{{ formatSizeFromMb($snes->size_mb) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-tech text-xs text-gray-600 tracking-widest">{{ $snes->crc32 ?? '—' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <i class="fa-solid fa-circle-xmark text-4xl text-gray-700 mb-3 block"></i>
                                    <p class="font-tech text-gray-500 text-sm">No ROMs found matching your filters.</p>
                                    <a href="{{ route('admin.snes') }}" class="mt-3 inline-block font-tech text-xs text-retro-cyan hover:underline">Clear filters</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($roms->hasPages())
                <div class="px-6 py-4 bg-retro-card bg-opacity-40 border-t border-retro-border flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-xs text-gray-400 font-tech">
                        Showing {{ $roms->firstItem() }} to {{ $roms->lastItem() }} of {{ number_format($roms->total()) }} ROMs
                    </div>
                    <div class="flex items-center space-x-1">
                        @if($roms->onFirstPage())
                            <span class="px-3 py-1.5 bg-retro-bg border border-retro-border border-opacity-40 text-gray-600 rounded text-xs font-tech uppercase tracking-wider cursor-not-allowed">Prev</span>
                        @else
                            <a href="{{ $roms->previousPageUrl() }}" class="px-3 py-1.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded text-xs font-tech uppercase tracking-wider transition">Prev</a>
                        @endif

                        <span class="px-4 py-1.5 bg-retro-bg border border-retro-border text-retro-cyan text-xs font-tech rounded">
                            Page {{ $roms->currentPage() }} of {{ $roms->lastPage() }}
                        </span>

                        @if($roms->hasMorePages())
                            <a href="{{ $roms->nextPageUrl() }}" class="px-3 py-1.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded text-xs font-tech uppercase tracking-wider transition">Next</a>
                        @else
                            <span class="px-3 py-1.5 bg-retro-bg border border-retro-border border-opacity-40 text-gray-600 rounded text-xs font-tech uppercase tracking-wider cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </main>

        <!-- Footer -->
        <footer class="text-center py-6 text-gray-600 text-xs font-tech border-t border-retro-border border-opacity-20 mt-12">
            Retro Drives Admin &mdash; Super Nintendo Entertainment System Library
        </footer>
    </div>

</body>
</html>
