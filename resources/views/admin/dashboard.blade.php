<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives - Admin Backend</title>
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
                        sans: ['Orbitron', 'sans-serif']
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
                    <i class="fa-solid fa-gamepad text-2xl text-retro-cyan"></i>
                </div>
                <div>
                    <h1 class="font-arcade text-3xl font-extrabold uppercase tracking-wider text-retro-cyan">
                        Retro Drives
                    </h1>
                    <p class="font-tech text-xs text-retro-cyan tracking-widest uppercase">
                        Games you want to play • 
                        @if($group === 'console')
                            Console Portal
                        @elseif($group === 'home_computer')
                            Home Computer Portal
                        @else
                            {{ $system === 'fbneo' ? 'FBNeo Arcade Portal' : 'MAME Arcade Portal' }}
                        @endif
                    </p>
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
        <div class="flex items-center space-x-2 mb-4 border-b border-retro-border border-opacity-20 pb-4">
            <a href="{{ url('/admin?group=arcade&system=mame') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all {{ $group === 'arcade' ? 'bg-retro-cyan text-black' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                <i class="fa-solid fa-gamepad mr-1"></i> Arcade
            </a>
            <a href="{{ route('admin.console') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all {{ $group === 'console' ? 'bg-retro-cyan text-black' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                <i class="fa-solid fa-tv mr-1"></i> Console
            </a>
            <a href="{{ url('/admin?group=home_computer') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all {{ $group === 'home_computer' ? 'bg-retro-cyan text-black' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                <i class="fa-solid fa-computer mr-1"></i> Home Computer
            </a>
            <a href="{{ route('admin.orders') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-truck-ramp-box mr-1"></i> Customer Orders
            </a>
        </div>

        <!-- Sub Level System Tabs (Only shown for Arcade group) -->
        @if($group === 'arcade')
            <div class="flex items-center space-x-2 mb-6 border-b border-retro-border border-opacity-20 pb-4">
                <a href="{{ url('/admin?group=arcade&system=mame') }}" class="px-4 py-2 rounded-lg font-tech text-xs uppercase tracking-wider transition-all {{ $system === 'mame' ? 'bg-retro-bg border-b-2 border-retro-magenta text-white' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                    MAME Catalog
                </a>
                <a href="{{ url('/admin?group=arcade&system=fbneo') }}" class="px-4 py-2 rounded-lg font-tech text-xs uppercase tracking-wider transition-all {{ $system === 'fbneo' ? 'bg-retro-bg border-b-2 border-retro-magenta text-white' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                    FBNeo Catalog
                </a>
                <a href="{{ url('/admin?group=arcade&system=chd') }}" class="px-4 py-2 rounded-lg font-tech text-xs uppercase tracking-wider transition-all {{ $system === 'chd' ? 'bg-retro-bg border-b-2 border-retro-magenta text-white' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                    CHD Catalog
                </a>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-retro-green bg-opacity-15 border border-retro-green rounded-xl text-retro-green font-tech text-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-retro-green hover:opacity-70">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-retro-red bg-opacity-15 border border-retro-red rounded-xl text-retro-red font-tech text-sm flex flex-col space-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span class="font-bold">Validation Errors Occurred:</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-retro-red hover:opacity-70">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <ul class="list-disc pl-5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($group !== 'arcade')
            <div class="text-center py-20 bg-retro-card bg-opacity-40 rounded-2xl border border-retro-border border-opacity-50 my-8">
                <i class="fa-solid fa-compact-disc text-5xl mb-4 block text-retro-border animate-spin-slow"></i>
                <h2 class="font-arcade text-lg text-white mb-2 uppercase">No {{ ucfirst(str_replace('_', ' ', $group)) }} Catalogs Integrated</h2>
                <p class="text-gray-500 text-sm max-w-md mx-auto">This catalog group is currently empty. Console and Home Computer systems are planned for future integration updates.</p>
            </div>
        @else

        <!-- Statistics Grid -->
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <!-- Stat 1 -->
            <div class="glass-card p-4 rounded-xl border border-retro-border neon-glow-cyan">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-tech text-retro-cyan uppercase tracking-wider">Total ROMs</span>
                    <i class="fa-solid fa-database text-retro-cyan text-sm opacity-60"></i>
                </div>
                <div class="mt-2 flex items-baseline">
                    <span class="font-arcade text-2xl font-black text-white">{{ number_format($stats['total']) }}</span>
                </div>
            </div>
            
            <!-- Stat 2 -->
            <div class="glass-card p-4 rounded-xl border border-retro-border neon-glow-magenta">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-tech text-retro-magenta uppercase tracking-wider">BIOS Files</span>
                    <i class="fa-solid fa-microchip text-retro-magenta text-sm opacity-60"></i>
                </div>
                <div class="mt-2 flex items-baseline">
                    <span class="font-arcade text-2xl font-black text-white">{{ number_format($stats['bios']) }}</span>
                    <span class="ml-2 text-xs font-tech text-retro-green">
                        @if($stats['total'] > 0)
                            ({{ round(($stats['bios'] / $stats['total']) * 100, 1) }}%)
                        @else
                            (0%)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="glass-card p-4 rounded-xl border border-retro-border neon-glow-purple">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-tech text-retro-purple uppercase tracking-wider">Clones</span>
                    <i class="fa-solid fa-code-fork text-retro-purple text-sm opacity-60"></i>
                </div>
                <div class="mt-2 flex items-baseline">
                    <span class="font-arcade text-2xl font-black text-white">{{ number_format($stats['clones']) }}</span>
                    <span class="ml-2 text-xs font-tech text-retro-green">
                        @if($stats['total'] > 0)
                            ({{ round(($stats['clones'] / $stats['total']) * 100, 1) }}%)
                        @else
                            (0%)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="glass-card p-4 rounded-xl border border-retro-border neon-glow-cyan">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-tech text-retro-cyan uppercase tracking-wider">CHDs Required</span>
                    <i class="fa-solid fa-compact-disc text-retro-cyan text-sm opacity-60"></i>
                </div>
                <div class="mt-2 flex items-baseline">
                    <span class="font-arcade text-2xl font-black text-white">{{ number_format($stats['chds']) }}</span>
                    <span class="ml-2 text-xs font-tech text-retro-green">
                        @if($stats['total'] > 0)
                            ({{ round(($stats['chds'] / $stats['total']) * 100, 1) }}%)
                        @else
                            (0%)
                        @endif
                    </span>
                </div>
            </div>
        </section>

        <!-- Filters and Search -->
        <section class="glass-card p-6 rounded-xl border border-retro-border border-opacity-60 shadow-xl mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2 pb-3 border-b border-retro-border border-opacity-20">
                <h2 class="font-arcade text-lg font-bold text-white flex items-center space-x-2">
                    <i class="fa-solid fa-sliders text-retro-cyan"></i>
                    <span>Filter Catalog & Search</span>
                </h2>
                <button type="button" onclick="showAdd()" class="px-4 py-2 bg-retro-magenta hover:bg-opacity-85 text-black font-tech text-xs uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5 font-bold">
                    <i class="fa-solid fa-plus text-black"></i>
                    <span>Add New Record</span>
                </button>
            </div>
            
            <form action="{{ url('/admin') }}" method="GET" class="space-y-4">
                <input type="hidden" name="system" value="{{ request('system', 'mame') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2 relative">
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Search Keywords</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="{{ $system === 'chd' ? 'Search ROM name...' : 'Search ROM, full name, manufacturer, driver...' }}" 
                                   class="w-full pl-10 pr-4 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-500 font-sans transition">
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Sort By</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select name="sort_by" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                                @if($system === 'chd')
                                    <option value="rom" {{ request('sort_by') === 'rom' ? 'selected' : '' }}>ROM</option>
                                    <option value="size" {{ request('sort_by') === 'size' ? 'selected' : '' }}>Size</option>
                                @else
                                    <option value="full_name" {{ request('sort_by') === 'full_name' ? 'selected' : '' }}>Full Name</option>
                                    <option value="rom" {{ request('sort_by') === 'rom' ? 'selected' : '' }}>ROM</option>
                                    <option value="year" {{ request('sort_by') === 'year' ? 'selected' : '' }}>Year</option>
                                    <option value="manufacturer" {{ request('sort_by') === 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                    <option value="hardware_board" {{ request('sort_by') === 'hardware_board' ? 'selected' : '' }}>Hardware Board</option>
                                @endif
                            </select>
                            <select name="sort_order" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>ASC</option>
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>DESC</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if($system !== 'chd')
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-4 gap-4 pt-2">
                    <!-- BIOS Boolean -->
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">BIOS</label>
                        <select name="use_bios" class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs">
                            <option value="">Any</option>
                            <option value="1" {{ request('use_bios') === '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ request('use_bios') === '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- CHD Boolean -->
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Uses CHDs</label>
                        <select name="use_chds" class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs">
                            <option value="">Any</option>
                            <option value="1" {{ request('use_chds') === '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ request('use_chds') === '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Hardware Board Filter -->
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Hardware Board</label>
                        <select name="hardware_board" class="w-full px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs select-custom">
                            <option value="">All Boards</option>
                            @foreach($hardwareBoards as $board)
                                <option value="{{ $board->id }}" {{ request('hardware_board') == $board->id ? 'selected' : '' }}>
                                    {{ $board->board }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-retro-cyan hover:bg-opacity-85 rounded-lg text-black font-tech text-xs uppercase tracking-wider transition">
                            Apply
                        </button>
                        <a href="{{ url('/admin?system=' . request('system', 'mame')) }}" class="px-3 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-400 hover:text-white text-xs transition flex items-center justify-center" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </div>
                @else
                <div class="flex justify-end pt-2">
                    <div class="flex items-center space-x-2 w-full sm:w-auto">
                        <button type="submit" class="px-6 py-2 bg-retro-cyan hover:bg-opacity-85 rounded-lg text-black font-tech text-xs uppercase tracking-wider transition font-bold">
                            Apply
                        </button>
                        <a href="{{ url('/admin?system=chd') }}" class="px-3 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-400 hover:text-white text-xs transition flex items-center justify-center" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </div>
                @endif
            </form>
        </section>

        <!-- ROM Table -->
        <main class="glass-card rounded-xl border border-retro-border border-opacity-60 shadow-2xl overflow-hidden mb-6">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-retro-border bg-retro-card bg-opacity-70 text-xs font-tech text-retro-cyan uppercase tracking-wider">
                            @if($system === 'chd')
                                <th class="py-4 px-6">ROM</th>
                                <th class="py-4 px-4 text-center">Size</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            @else
                                <th class="py-4 px-6">ROM</th>
                                <th class="py-4 px-4">Full Name</th>
                                <th class="py-4 px-4">Year</th>
                                <th class="py-4 px-4">Manufacturer</th>
                                <th class="py-4 px-4">Hardware</th>
                                <th class="py-4 px-4 text-center">CHD</th>
                                <th class="py-4 px-4 text-center">Size</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-retro-border divide-opacity-30 text-sm">
                        @forelse($mames as $mame)
                            <tr class="hover:bg-retro-card hover:bg-opacity-40 transition group">
                                @if($system === 'chd')
                                    <td class="py-4 px-6 font-tech font-bold text-white tracking-wide">
                                        <span class="group-hover:text-retro-cyan transition-colors">{{ $mame->rom }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-center font-tech text-gray-400">
                                        {{ formatSizeFromMb($mame->size) }}
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        <button onclick="showEditChd({{ json_encode($mame) }})" 
                                                class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-magenta text-xs font-tech uppercase tracking-wider text-gray-300 hover:text-retro-magenta rounded transition">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.chd.destroy', $mame->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this CHD?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-red text-xs font-tech uppercase tracking-wider text-gray-400 hover:text-retro-red rounded transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                @else
                                    <td class="py-4 px-6 font-tech font-bold text-white tracking-wide flex items-center space-x-2">
                                        <span class="group-hover:text-retro-cyan transition-colors">{{ $mame->rom }}</span>
                                        <button type="button" onclick="fetchArcadeItalia('{{ $mame->rom }}')" class="text-gray-500 hover:text-retro-cyan transition" title="Fetch ArcadeItalia (ADB) Metadata">
                                            <i class="fa-solid fa-earth-europe text-xs"></i>
                                        </button>
                                    </td>
                                    <td class="py-4 px-4 max-w-xs truncate font-sans text-gray-200">
                                        {{ $mame->full_name }}
                                    </td>
                                    <td class="py-4 px-4 font-tech text-gray-400">
                                        {{ $mame->year ?? '----' }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-300 max-w-xxs truncate">
                                        {{ $mame->manufacturer }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-400 max-w-xxs truncate font-tech">
                                        {{ $mame->hardware ?? $mame->driver ?? '----' }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($mame->use_chds)
                                            <i class="fa-solid fa-circle-check text-retro-cyan text-base" title="Requires CHD"></i>
                                        @else
                                            <i class="fa-regular fa-circle text-gray-700 text-base" title="No CHD Required"></i>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center font-tech text-gray-400">
                                        @if($mame->total_size > 0)
                                            <span>{{ formatSizeFromMb($mame->total_size) }}</span>
                                            @if($mame->chd)
                                                <span class="text-[10px] text-pink-500 block leading-none mt-1">(incl. CHD)</span>
                                            @endif
                                        @else
                                            <span>—</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        <button onclick="showEdit({{ json_encode($mame) }})" 
                                                class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-magenta text-xs font-tech uppercase tracking-wider text-gray-300 hover:text-retro-magenta rounded transition">
                                            Edit
                                        </button>
                                        <button onclick="showDetails({{ json_encode($mame) }})" 
                                                class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-cyan text-xs font-tech uppercase tracking-wider text-gray-300 hover:text-retro-cyan rounded transition">
                                            Inspect
                                        </button>
                                        @if($system === 'fbneo')
                                            <form action="{{ route('admin.fbneo.destroy', $mame->rom) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this ROM from FBNeo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-red text-xs font-tech uppercase tracking-wider text-gray-400 hover:text-retro-red rounded transition">
                                                    Remove
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.mame.destroy', $mame->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this MAME ROM?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-red text-xs font-tech uppercase tracking-wider text-gray-400 hover:text-retro-red rounded transition">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $system === 'chd' ? 3 : 8 }}" class="py-12 text-center text-gray-500 font-tech">
                                    <i class="fa-solid fa-circle-exclamation text-3xl mb-3 block text-retro-border"></i>
                                    No entries found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Retro Pagination -->
            @if($mames->hasPages())
                <div class="px-6 py-4 bg-retro-card bg-opacity-40 border-t border-retro-border flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-xs text-gray-400 font-tech">
                        Showing {{ $mames->firstItem() }} to {{ $mames->lastItem() }} of {{ $mames->total() }} ROMs
                    </div>
                    <div class="flex items-center space-x-1">
                        {{-- Custom styled link render --}}
                        @if ($mames->onFirstPage())
                            <span class="px-3 py-1.5 bg-retro-bg border border-retro-border border-opacity-40 text-gray-600 rounded text-xs font-tech uppercase tracking-wider cursor-not-allowed">Prev</span>
                        @else
                            <a href="{{ $mames->previousPageUrl() }}" class="px-3 py-1.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded text-xs font-tech uppercase tracking-wider transition">Prev</a>
                        @endif

                        <span class="px-4 py-1.5 bg-retro-bg border border-retro-border text-retro-cyan text-xs font-tech rounded">
                            Page {{ $mames->currentPage() }} of {{ $mames->lastPage() }}
                        </span>

                        @if ($mames->hasMorePages())
                            <a href="{{ $mames->nextPageUrl() }}" class="px-3 py-1.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded text-xs font-tech uppercase tracking-wider transition">Next</a>
                        @else
                            <span class="px-3 py-1.5 bg-retro-bg border border-retro-border border-opacity-40 text-gray-600 rounded text-xs font-tech uppercase tracking-wider cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </main>
        @endif
        
        <!-- Footer -->
        <footer class="text-center py-6 text-gray-600 text-xs font-tech border-t border-retro-border border-opacity-20 mt-12">
            Retro Drives &copy; {{ date('Y') }} • Games you want to play
        </footer>
    </div>

    <!-- Inspector Modal (Interactive details panel) -->
    <div id="inspector-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-2xl w-full rounded-2xl border border-retro-cyan overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
                <div>
                    <span class="text-xs font-tech text-retro-cyan uppercase tracking-widest">ROM Inspector</span>
                    <h3 id="inspect-rom-name" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
                </div>
                <button onclick="closeDetails()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <div>
                    <h4 class="text-xs font-tech text-retro-magenta uppercase tracking-wider mb-1">Full Name</h4>
                    <p id="inspect-full-name" class="text-white text-base"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Manufacturer</h4>
                        <p id="inspect-manufacturer" class="text-gray-200 text-sm font-semibold"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Year</h4>
                        <p id="inspect-year" class="text-gray-200 text-sm font-semibold"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Hardware Board</h4>
                        <p id="inspect-hardware-board" class="text-gray-200 text-sm font-semibold"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Driver Name</h4>
                        <p id="inspect-driver" class="text-gray-200 text-sm font-tech"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Source File</h4>
                        <p id="inspect-sourcefile" class="text-gray-200 text-sm font-tech"></p>
                    </div>
                </div>

                <div class="border-t border-retro-border border-opacity-40 pt-4 grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Clone of</h4>
                        <p id="inspect-cloneof" class="text-gray-200 text-sm font-tech"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">ROM of</h4>
                        <p id="inspect-romof" class="text-gray-200 text-sm font-tech"></p>
                    </div>
                </div>

                <div class="border-t border-retro-border border-opacity-40 pt-4 grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Display Stats</h4>
                        <p id="inspect-display" class="text-gray-200 text-sm font-tech"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">File Size</h4>
                        <p id="inspect-size" class="text-gray-200 text-sm font-tech"></p>
                    </div>
                </div>

                <!-- Flag Badges Grid -->
                <div class="border-t border-retro-border border-opacity-40 pt-4">
                    <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-3">System Properties</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div id="badge-bios" class="p-2.5 rounded-lg border flex items-center justify-between text-xs font-tech uppercase">
                            <span>BIOS File</span>
                            <span class="status-indicator font-bold"></span>
                        </div>
                        <div id="badge-chds" class="p-2.5 rounded-lg border flex items-center justify-between text-xs font-tech uppercase">
                            <span>Uses CHDs</span>
                            <span class="status-indicator font-bold"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-end">
                <button onclick="closeDetails()" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-80 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition">
                    Close Inspector
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Modal (Interactive form details panel) -->
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-2xl w-full rounded-2xl border border-retro-magenta overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <form id="edit-form" action="" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
                    <div>
                        <span class="text-xs font-tech text-retro-magenta uppercase tracking-widest">Edit MAME ROM</span>
                        <h3 id="edit-rom-title" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
                    </div>
                    <button type="button" onclick="closeEdit()" class="text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="block text-xs font-tech text-retro-magenta uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="full_name" id="edit-full-name" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Manufacturer</label>
                            <input type="text" name="manufacturer" id="edit-manufacturer" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Year</label>
                            <input type="text" name="year" id="edit-year" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Driver Name</label>
                            <input type="text" name="driver" id="edit-driver" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Source File</label>
                            <input type="text" name="sourcefile" id="edit-sourcefile" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                        </div>
                    </div>

                    <!-- Flag Badges Grid -->
                    <div class="border-t border-retro-border border-opacity-40 pt-4">
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-3">System Properties</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center space-x-3 cursor-pointer text-sm font-tech text-retro-cyan">
                                <input type="checkbox" name="use_bios" id="edit-use-bios" value="1" class="h-4 w-4 rounded border-retro-border bg-retro-bg text-retro-cyan focus:ring-retro-cyan focus:ring-opacity-25">
                                <span>System BIOS</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer text-sm font-tech text-pink-400">
                                <input type="checkbox" name="use_chds" id="edit-use-chds" value="1" class="h-4 w-4 rounded border-retro-border bg-retro-bg text-retro-magenta focus:ring-retro-magenta focus:ring-opacity-25">
                                <span>Uses CHDs</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-end space-x-2">
                    <button type="button" onclick="closeEdit()" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-white text-white font-tech text-sm uppercase tracking-wider rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Record Modal -->
    <div id="add-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-2xl w-full rounded-2xl border border-retro-magenta overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <form action="{{ route('admin.store') }}" method="POST">
                @csrf
                <input type="hidden" name="system" value="{{ $system }}">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
                    <div>
                        <span class="text-xs font-tech text-retro-magenta uppercase tracking-widest">Add New Record</span>
                        <h3 class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider">
                            @if($system === 'chd')
                                Add CHD Record
                            @elseif($system === 'fbneo')
                                Add FBNeo ROM
                            @else
                                Add MAME ROM
                            @endif
                        </h3>
                    </div>
                    <button type="button" onclick="closeAdd()" class="text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    @if($system === 'chd')
                        <div>
                            <label class="block text-xs font-tech text-retro-magenta uppercase tracking-wider mb-1">ROM Name (matching MAME ROM)</label>
                            <input type="text" name="rom" required class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Size (MB)</label>
                            <input type="number" step="0.01" name="size" required class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-tech text-retro-magenta uppercase tracking-wider mb-1">ROM Name</label>
                            <input type="text" name="rom" required class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-retro-magenta uppercase tracking-wider mb-1">Full Name / Description</label>
                            <input type="text" name="full_name" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Manufacturer</label>
                                <input type="text" name="manufacturer" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Year</label>
                                <input type="text" name="year" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Driver Name</label>
                                <input type="text" name="driver" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                            </div>
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Source File</label>
                                <input type="text" name="sourcefile" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Base Size (MB)</label>
                                <input type="number" step="0.01" name="size" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                            </div>
                        </div>

                        <!-- Flag Badges Grid -->
                        <div class="border-t border-retro-border border-opacity-40 pt-4">
                            <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-3">System Properties</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center space-x-3 cursor-pointer text-sm font-tech text-retro-cyan">
                                    <input type="checkbox" name="use_bios" value="1" class="h-4 w-4 rounded border-retro-border bg-retro-bg text-retro-cyan focus:ring-retro-cyan focus:ring-opacity-25">
                                    <span>System BIOS</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer text-sm font-tech text-pink-400">
                                    <input type="checkbox" name="use_chds" value="1" class="h-4 w-4 rounded border-retro-border bg-retro-bg text-retro-magenta focus:ring-retro-magenta focus:ring-opacity-25">
                                    <span>Uses CHDs</span>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-end space-x-2">
                    <button type="button" onclick="closeAdd()" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-white text-white font-tech text-sm uppercase tracking-wider rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition font-bold">
                        Add Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit CHD Modal -->
    <div id="edit-chd-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-md w-full rounded-2xl border border-retro-magenta overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <form id="edit-chd-form" action="" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
                    <div>
                        <span class="text-xs font-tech text-retro-magenta uppercase tracking-widest">Edit CHD ROM</span>
                        <h3 id="edit-chd-rom-title" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
                    </div>
                    <button type="button" onclick="closeEditChd()" class="text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Size (MB)</label>
                        <input type="number" step="0.01" name="size" id="edit-chd-size" required class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-end space-x-2">
                    <button type="button" onclick="closeEditChd()" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-white text-white font-tech text-sm uppercase tracking-wider rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ADB Metadata Scraper Modal -->
    <div id="adb-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-4xl w-full rounded-2xl border border-retro-cyan overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
                <div>
                    <span class="text-xs font-tech text-retro-cyan uppercase tracking-widest">ArcadeItalia Scraper Metadata</span>
                    <h3 id="adb-rom-title" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
                </div>
                <button type="button" onclick="closeAdb()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                <div id="adb-loading" class="text-center py-16">
                    <i class="fa-solid fa-satellite-dish text-4xl text-retro-cyan animate-pulse mb-3 block"></i>
                    <p class="font-tech text-gray-400 text-sm">Querying ArcadeItalia Database API...</p>
                </div>
                
                <div id="adb-error" class="text-center py-16 hidden">
                    <i class="fa-solid fa-triangle-exclamation text-4xl text-retro-red mb-3 block"></i>
                    <p id="adb-error-message" class="font-tech text-retro-red text-sm"></p>
                </div>

                <div id="adb-content" class="space-y-5 hidden">
                    <!-- Basic Stats from API -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-retro-bg p-4 rounded-xl border border-retro-border border-opacity-35">
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Title</span>
                            <span id="adb-title" class="text-xs text-white font-semibold block truncate"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Manufacturer</span>
                            <span id="adb-manufacturer" class="text-xs text-white font-semibold block truncate"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Year</span>
                            <span id="adb-year" class="text-xs text-white font-semibold block truncate"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Genre</span>
                            <span id="adb-genre" class="text-xs text-white font-semibold block truncate"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Players</span>
                            <span id="adb-players" class="text-xs text-white font-semibold block"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Cabinet Status</span>
                            <span id="adb-status" class="text-xs text-white font-semibold block"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Controls</span>
                            <span id="adb-controls" class="text-xs text-white font-semibold block"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-tech text-gray-500 uppercase block font-bold">Resolution</span>
                            <span id="adb-resolution" class="text-xs text-white font-semibold block"></span>
                        </div>
                    </div>

                    <!-- Images Section -->
                    <div id="adb-images-section">
                        <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider mb-3">Cabinet & Game Artwork</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <!-- Title -->
                            <div id="adb-img-wrapper-title" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                                <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Title Screen</span>
                                <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                    <img id="adb-img-title" src="" alt="Title Screen" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                                </div>
                            </div>
                            <!-- In-Game -->
                            <div id="adb-img-wrapper-ingame" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                                <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">In-Game Play</span>
                                <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                    <img id="adb-img-ingame" src="" alt="In-Game Play" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                                </div>
                            </div>
                            <!-- Cabinet -->
                            <div id="adb-img-wrapper-cabinet" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                                <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Cabinet</span>
                                <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                    <img id="adb-img-cabinet" src="" alt="Cabinet" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                                </div>
                            </div>
                            <!-- Marquee -->
                            <div id="adb-img-wrapper-marquee" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                                <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Marquee</span>
                                <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                    <img id="adb-img-marquee" src="" alt="Marquee" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                                </div>
                            </div>
                            <!-- Flyer -->
                            <div id="adb-img-wrapper-flyer" class="bg-black bg-opacity-50 p-2 rounded-lg border border-retro-border border-opacity-40 text-center flex flex-col justify-between h-44">
                                <span class="text-[9px] font-tech text-gray-400 uppercase mb-1.5 block font-bold">Flyer Poster</span>
                                <div class="flex-1 flex items-center justify-center overflow-hidden rounded bg-retro-bg">
                                    <img id="adb-img-flyer" src="" alt="Flyer" class="max-h-full max-w-full object-contain cursor-pointer hover:scale-105 transition duration-200" onclick="window.open(this.src)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Section -->
                    <div id="adb-video-wrapper" class="space-y-2 hidden">
                        <h4 class="text-xs font-tech text-retro-magenta uppercase tracking-wider flex items-center justify-between">
                            <span>Gameplay Video Preview</span>
                            <button type="button" onclick="stopAdbVideo()" class="text-gray-500 hover:text-retro-magenta transition text-[10px]">
                                <i class="fa-solid fa-stop"></i> Stop Video
                            </button>
                        </h4>
                        <div class="bg-black rounded-xl border border-retro-border border-opacity-40 overflow-hidden flex justify-center">
                            <video id="adb-video" controls class="max-h-80 w-full" src=""></video>
                        </div>
                    </div>

                    <!-- History / Description Text -->
                    <div id="adb-history-wrapper" class="space-y-2">
                        <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider">Game History & Trivia</h4>
                        <div id="adb-history" class="bg-black bg-opacity-45 p-4 rounded-xl border border-retro-border border-opacity-35 text-xs text-gray-300 leading-relaxed font-sans max-h-48 overflow-y-auto whitespace-pre-wrap select-text"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-between items-center">
                <div>
                    <button id="adb-btn-video" type="button" onclick="playAdbVideo()" class="px-4 py-2 bg-retro-magenta hover:bg-opacity-80 text-black font-tech text-xs uppercase tracking-wider rounded-lg transition font-bold hidden flex items-center space-x-1.5 shadow-md">
                        <i class="fa-solid fa-play text-black text-[10px]"></i>
                        <span>Play Video</span>
                    </button>
                </div>
                <button onclick="closeAdb()" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-80 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition font-bold">
                    Close ADB Window
                </button>
            </div>
        </div>
    </div>

    <!-- Inspector & Edit Logic -->
    <script>
        const detailsModal = document.getElementById('inspector-modal');
        const editModal = document.getElementById('edit-modal');
        const editForm = document.getElementById('edit-form');
        const addModal = document.getElementById('add-modal');
        const editChdModal = document.getElementById('edit-chd-modal');
        const editChdForm = document.getElementById('edit-chd-form');
        const adbModal = document.getElementById('adb-modal');
        
        function showAdd() {
            addModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAdd() {
            addModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showEditChd(chd) {
            editChdForm.action = `/admin/chd/${chd.id}`;
            document.getElementById('edit-chd-rom-title').innerText = chd.rom;
            document.getElementById('edit-chd-size').value = chd.size || '';
            editChdModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditChd() {
            editChdModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        let activeVideoUrl = "";

        function playAdbVideo() {
            if (activeVideoUrl) {
                const video = document.getElementById('adb-video');
                video.src = activeVideoUrl;
                document.getElementById('adb-video-wrapper').classList.remove('hidden');
                video.play();
                document.getElementById('adb-video-wrapper').scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        }

        function stopAdbVideo() {
            const video = document.getElementById('adb-video');
            video.pause();
            video.src = "";
            document.getElementById('adb-video-wrapper').classList.add('hidden');
        }

        function fetchArcadeItalia(rom) {
            document.getElementById('adb-rom-title').innerText = rom;
            
            // Show loading, hide others
            document.getElementById('adb-loading').classList.remove('hidden');
            document.getElementById('adb-error').classList.add('hidden');
            document.getElementById('adb-content').classList.add('hidden');
            
            adbModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            fetch(`/admin/arcade-italia/${rom}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('API request failed or ROM not found.');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('adb-loading').classList.add('hidden');
                    document.getElementById('adb-content').classList.remove('hidden');
                    
                    // ADB scraper response has the game info inside 'result' or root
                    let game = {};
                    if (Array.isArray(data)) {
                        game = data[0] || {};
                    } else if (data.result && Array.isArray(data.result)) {
                        game = data.result[0] || {};
                    } else {
                        game = data;
                    }
                    
                    document.getElementById('adb-title').innerText = game.title || game.description || '—';
                    document.getElementById('adb-manufacturer').innerText = game.manufacturer || '—';
                    document.getElementById('adb-year').innerText = game.year || '—';
                    document.getElementById('adb-genre').innerText = game.genre || '—';
                    document.getElementById('adb-players').innerText = game.nplayers || game.players || '—';
                    document.getElementById('adb-status').innerText = game.status || '—';
                    document.getElementById('adb-controls').innerText = game.input_controls || '—';
                    document.getElementById('adb-resolution').innerText = game.screen_resolution || '—';

                    // Set game history/trivia
                    const historyWrapper = document.getElementById('adb-history-wrapper');
                    const historyEl = document.getElementById('adb-history');
                    if (game.history && game.history.trim() !== '') {
                        historyEl.innerText = game.history;
                        historyWrapper.classList.remove('hidden');
                    } else {
                        historyWrapper.classList.add('hidden');
                    }

                    // Set game artwork images
                    setAdbImage('adb-img-title', 'adb-img-wrapper-title', game.url_image_title);
                    setAdbImage('adb-img-ingame', 'adb-img-wrapper-ingame', game.url_image_ingame);
                    setAdbImage('adb-img-cabinet', 'adb-img-wrapper-cabinet', game.url_image_cabinet);
                    setAdbImage('adb-img-marquee', 'adb-img-wrapper-marquee', game.url_image_marquee);
                    setAdbImage('adb-img-flyer', 'adb-img-wrapper-flyer', game.url_image_flyer);

                    // Check video preview URL
                    const videoBtn = document.getElementById('adb-btn-video');
                    const videoUrl = game.url_video_shortplay_hd || game.url_video_shortplay;
                    if (videoUrl && videoUrl.trim() !== '') {
                        activeVideoUrl = videoUrl;
                        videoBtn.classList.remove('hidden');
                    } else {
                        activeVideoUrl = "";
                        videoBtn.classList.add('hidden');
                    }
                })
                .catch(error => {
                    document.getElementById('adb-loading').classList.add('hidden');
                    document.getElementById('adb-error').classList.remove('hidden');
                    document.getElementById('adb-error-message').innerText = error.message;
                });
        }

        function setAdbImage(imgId, wrapperId, url) {
            const img = document.getElementById(imgId);
            const wrapper = document.getElementById(wrapperId);
            
            if (url && url.trim() !== "") {
                img.src = url;
                wrapper.classList.remove('hidden');
                
                // If it fails to load, hide the wrapper dynamically
                img.onerror = function() {
                    wrapper.classList.add('hidden');
                };
            } else {
                img.src = "";
                wrapper.classList.add('hidden');
            }
        }

        function closeAdb() {
            stopAdbVideo();
            adbModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showDetails(mame) {
            // Text nodes
            document.getElementById('inspect-rom-name').innerText = mame.rom;
            document.getElementById('inspect-full-name').innerText = mame.full_name || 'No description available.';
            document.getElementById('inspect-manufacturer').innerText = mame.manufacturer || 'Unknown';
            document.getElementById('inspect-year').innerText = mame.year || 'Unknown';
            document.getElementById('inspect-hardware-board').innerText = mame.hardware || 'Default / Generic';
            document.getElementById('inspect-driver').innerText = mame.driver || '----';
            document.getElementById('inspect-cloneof').innerText = mame.cloneof || '----';
            document.getElementById('inspect-romof').innerText = mame.romof || '----';
            document.getElementById('inspect-sourcefile').innerText = mame.sourcefile || '----';
            
            // Display specs
            let displayInfo = '----';
            if (mame.display_width || mame.display_height) {
                displayInfo = `Screen size: ${mame.display_width || '?'}x${mame.display_height || '?'}`;
                if (mame.display_orientation) {
                    displayInfo += ` (${mame.display_orientation})`;
                }
                if (mame.display_rotate) {
                    displayInfo += ` (Rotated ${mame.display_rotate}°)`;
                }
            }
            document.getElementById('inspect-display').innerText = displayInfo;

            // Display size info
            function formatSize(mb) {
                if (!mb || mb <= 0) return '—';
                if (mb < 1) return (mb * 1024).toFixed(1) + ' KB';
                if (mb >= 1024) return (mb / 1024).toFixed(2) + ' GB';
                return mb.toFixed(2) + ' MB';
            }

            let sizeInfo = '—';
            if (mame.size && mame.chd) {
                sizeInfo = `${formatSize(mame.size)} + ${formatSize(mame.chd.size)} (Total: ${formatSize(mame.total_size)})`;
            } else if (mame.size) {
                sizeInfo = formatSize(mame.size);
            } else if (mame.chd) {
                sizeInfo = `${formatSize(mame.chd.size)} (CHD Only)`;
            }
            document.getElementById('inspect-size').innerText = sizeInfo;

            // Badges helper
            setModalBadge('badge-bios', mame.use_bios, 'text-retro-cyan', 'border-retro-cyan');
            setModalBadge('badge-chds', mame.use_chds, 'text-pink-400', 'border-pink-400');

            // Show modal
            detailsModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function showEdit(mame) {
            editForm.action = `/admin/mame/${mame.id}`;
            
            document.getElementById('edit-rom-title').innerText = mame.rom;
            document.getElementById('edit-full-name').value = mame.full_name || '';
            document.getElementById('edit-manufacturer').value = mame.manufacturer || '';
            document.getElementById('edit-year').value = mame.year || '';
            document.getElementById('edit-driver').value = mame.driver || '';
            document.getElementById('edit-sourcefile').value = mame.sourcefile || '';
            
            document.getElementById('edit-use-bios').checked = !!mame.use_bios;
            document.getElementById('edit-use-chds').checked = !!mame.use_chds;

            editModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function setModalBadge(elementId, isActive, colorClass, borderClass) {
            const el = document.getElementById(elementId);
            const indicator = el.querySelector('.status-indicator');
            
            // Reset
            el.className = "p-2.5 rounded-lg border flex items-center justify-between text-xs font-tech uppercase bg-retro-bg";
            
            if (isActive) {
                el.className = `p-2.5 rounded-lg border flex items-center justify-between text-xs font-tech uppercase bg-retro-bg ${colorClass} ${borderClass} bg-opacity-50 shadow-inner`;
                indicator.innerText = 'YES';
                indicator.className = `status-indicator font-bold ${colorClass}`;
            } else {
                el.className = "p-2.5 rounded-lg border flex items-center justify-between text-xs font-tech uppercase bg-retro-bg text-gray-600 border-retro-border border-opacity-30";
                indicator.innerText = 'NO';
                indicator.className = 'status-indicator font-bold text-gray-600';
            }
        }

        function closeDetails() {
            detailsModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function closeEdit() {
            editModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetails();
                closeEdit();
                closeAdd();
                closeEditChd();
                closeAdb();
            }
        });

        // Close on click outside modal
        window.addEventListener('click', function(event) {
            if (event.target === detailsModal) {
                closeDetails();
            }
            if (event.target === editModal) {
                closeEdit();
            }
            if (event.target === addModal) {
                closeAdd();
            }
            if (event.target === editChdModal) {
                closeEditChd();
            }
            if (event.target === adbModal) {
                closeAdb();
            }
        });
    </script>

</body>
</html>
