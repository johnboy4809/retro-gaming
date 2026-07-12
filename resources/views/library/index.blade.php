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
                            bg: '#120224',
                            card: '#170b3b',
                            border: '#3f1b77',
                            cyan: '#00f3ff',
                            magenta: '#ff00a0',
                            teal: '#008fa3',
                            dark: '#051c24',
                            purple: '#9d4edd',
                            green: '#39ff14',
                            yellow: '#ffd700',
                            red: '#ff3333'
                        },
                        gray: {
                            200: '#ffffff',
                            300: '#f8fafc',
                            400: '#f1f5f9',
                            500: '#e2e8f0',
                            600: '#cbd5e1',
                            700: '#94a3b8',
                            800: '#64748b',
                            900: '#475569',
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
        <x-header />

        <!-- Section Two: Select Your Games -->
        <div class="mb-8 text-center max-w-2xl mx-auto">
            <h2 class="font-arcade text-3xl font-bold text-white mb-3 flex items-center justify-center space-x-4">
                <i class="fa-solid fa-gamepad text-retro-magenta"></i>
                <span>Select Your Games</span>
            </h2>
            <p class="font-tech text-base text-gray-400">Select from the best arcade, console and home computer games from the 70s, 80s, 90s and early 2000s.</p>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-6 mb-8 border-b border-retro-border border-opacity-20 pb-8">
            @foreach($platforms as $platform)
                @php
                    $firstSubPlatform = $platform->subPlatforms->first();
                    $systemParam = $firstSubPlatform ? '&system=' . $firstSubPlatform->slug : '';
                @endphp
                <a href="{{ url('/library?group=' . $platform->slug . $systemParam) }}" 
                   class="px-8 py-4 rounded-xl font-arcade text-sm uppercase tracking-wider transition-all duration-300 flex items-center shadow-lg {{ $group === $platform->slug ? 'bg-retro-cyan text-black shadow-[0_0_20px_rgba(0,243,255,0.4)] scale-105' : 'bg-retro-card text-gray-400 hover:text-white hover:border-retro-cyan border border-retro-border hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(0,243,255,0.2)]' }}">
                    <i class="icon-svg {{ $platform->icon }} text-lg mr-2 {{ $group === $platform->slug ? 'text-black' : 'text-' . $platform->color }}"></i> {{ $platform->name }}
                </a>
            @endforeach
        </div>

        <!-- Section One: Drive Capacity -->
        <div class="mb-10 border-b border-retro-border border-opacity-30 pb-8">
            <section class="p-6 bg-retro-card bg-opacity-40 rounded-2xl border border-retro-border border-opacity-50">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <h3 class="font-arcade text-xl text-white mb-1 flex items-center space-x-2">
                            <i class="fa-solid fa-hard-drive text-retro-cyan text-lg"></i>
                            <span>Drive Capacity ({{ session('drive_size', 16) }}GB)</span>
                        </h3>
                        <p class="text-xs font-tech text-gray-400 uppercase tracking-widest">
                            Platform: {{ strtoupper(session('drive_platform', 'PC')) }} | Size: {{ session('drive_size', 16) }} GB
                        </p>
                    </div>
                    <div class="text-right">
                        @if($driveStats['total_mb'] - $driveStats['used_mb'] < 0)
                            <div class="text-2xl font-tech text-retro-magenta font-bold">Over Capacity</div>
                        @else
                            <div class="text-2xl font-tech text-retro-cyan font-bold">{{ number_format(($driveStats['total_mb'] - $driveStats['used_mb']) / 1024, 2) }} <span class="text-sm text-gray-500">GB Remaining</span></div>
                        @endif
                    </div>
                </div>
                <div class="w-full h-4 bg-retro-card rounded-full border border-retro-border border-opacity-60 relative flex">
                    <!-- OS Space -->
                    <div class="h-full bg-retro-border bg-opacity-70 flex items-center justify-center relative group rounded-l-full" 
                         style="width: {{ ($driveStats['os_mb'] / $driveStats['total_mb']) * 100 }}%">
                        <div class="opacity-0 group-hover:opacity-100 absolute -top-8 bg-black text-white text-[10px] font-tech px-2 py-1 rounded whitespace-nowrap transition-opacity z-10">
                            OS System Files (9 GB)
                        </div>
                    </div>
                    <!-- BIOS Space -->
                    <div class="h-full bg-retro-purple bg-opacity-70 flex items-center justify-center relative group" 
                         style="width: {{ ($driveStats['bios_mb'] / $driveStats['total_mb']) * 100 }}%">
                        <div class="opacity-0 group-hover:opacity-100 absolute -top-8 bg-black text-white text-[10px] font-tech px-2 py-1 rounded whitespace-nowrap transition-opacity z-10">
                            BIOS Files (1 GB)
                        </div>
                    </div>
                    <!-- ROM Space -->
                    <div class="h-full bg-retro-cyan transition-all duration-500 flex items-center justify-center relative group {{ $driveStats['used_mb'] >= $driveStats['total_mb'] ? 'rounded-r-full' : '' }}"
                         style="width: {{ min((($driveStats['used_mb'] - $driveStats['os_mb'] - $driveStats['bios_mb']) / $driveStats['total_mb']) * 100, 100 - ((($driveStats['os_mb'] + $driveStats['bios_mb']) / $driveStats['total_mb']) * 100)) }}%">
                         @if($driveStats['used_mb'] > ($driveStats['os_mb'] + $driveStats['bios_mb']))
                            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 bg-black text-white text-[10px] font-tech px-2 py-1 rounded whitespace-nowrap transition-opacity z-10">
                                ROMs ({{ number_format(($driveStats['used_mb'] - $driveStats['os_mb'] - $driveStats['bios_mb']) / 1024, 2) }} GB)
                            </div>
                         @endif
                    </div>
                </div>
                <div class="flex justify-between mt-2 text-[10px] font-tech text-gray-500 uppercase tracking-widest">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center space-x-1"><span class="w-2 h-2 rounded-full bg-retro-border bg-opacity-70 block"></span><span>OS (9GB)</span></span>
                        <span class="flex items-center space-x-1"><span class="w-2 h-2 rounded-full bg-retro-purple bg-opacity-70 block"></span><span>BIOS (1GB)</span></span>
                        <span class="flex items-center space-x-1"><span class="w-2 h-2 rounded-full bg-retro-cyan block"></span><span>Games</span></span>
                    </div>
                    <div class="text-retro-magenta">
                        @if($driveStats['total_mb'] - $driveStats['used_mb'] < 0)
                            Over capacity!
                        @else
                            {{ number_format(($driveStats['total_mb'] - $driveStats['used_mb']) / 1024, 2) }} GB Free
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-retro-border border-opacity-20">
                    <p class="font-tech text-xs text-gray-500"><i class="fa-solid fa-circle-info mr-1 text-retro-cyan opacity-70"></i> Keep track on how much storage you are using. Each time you add a game this will be shown in your drive's capacity. Please note 9GB is already used by the BATOCERA OS and 1GB for the required BIOS.</p>
                </div>
            </section>
        </div>

        @php
            $activePlatform = $platforms->firstWhere('slug', $group);
        @endphp

        <!-- Sub Level System Tabs -->
        @if($activePlatform && $activePlatform->subPlatforms->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-4 mb-8">
                @foreach($activePlatform->subPlatforms as $subPlatform)
                    <a href="{{ url('/library?group=' . $activePlatform->slug . '&system=' . $subPlatform->slug) }}" 
                       class="px-6 py-2 rounded-lg font-tech text-xs uppercase tracking-widest transition-all duration-300 {{ $system === $subPlatform->slug ? 'bg-' . $activePlatform->color . ' text-black font-bold shadow-[0_0_10px_rgba(' . ($activePlatform->color === 'retro-cyan' ? '0,243,255' : '255,255,255') . ',0.5)]' : 'bg-retro-card text-gray-400 hover:text-white border border-retro-border' }}">
                        {{ $subPlatform->name }}
                    </a>
                @endforeach
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

        @if(!$activePlatform || $activePlatform->subPlatforms->isEmpty())
            <div class="text-center py-20 bg-retro-card bg-opacity-40 rounded-2xl border border-retro-border border-opacity-50 my-8">
                <i class="fa-solid fa-compact-disc text-5xl mb-4 block text-retro-border animate-spin-slow"></i>
                <h2 class="font-arcade text-lg text-white mb-2 uppercase">No {{ $activePlatform ? $activePlatform->name : ucfirst(str_replace('_', ' ', $group)) }} Catalogs Integrated</h2>
                <p class="text-gray-500 text-sm max-w-md mx-auto">{{ $activePlatform && $activePlatform->description ? $activePlatform->description : 'This catalog group is currently empty.' }}</p>
            </div>
        @else

        <!-- Filters and Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <form action="{{ url('/library') }}" method="GET" class="w-full sm:w-1/2 relative flex items-center">
                <input type="hidden" name="group" value="{{ $group }}">
                <input type="hidden" name="system" value="{{ $system }}">
                @if(request()->has('sort_by'))
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                @endif
                @if(request()->has('manufacturer')) <input type="hidden" name="manufacturer" value="{{ request('manufacturer') }}"> @endif
                @if(request()->has('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                @if(request()->has('region')) <input type="hidden" name="region" value="{{ request('region') }}"> @endif
                @if(request()->has('hardware_board')) <input type="hidden" name="hardware_board" value="{{ request('hardware_board') }}"> @endif
                
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ $system === 'chd' ? 'Search ROM name...' : 'Search ROM, full name, manufacturer, driver...' }}" 
                       class="w-full pl-10 pr-4 py-2 bg-retro-bg rounded-l-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-500 font-sans transition">
                <button type="submit" class="px-4 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech font-bold uppercase tracking-wider rounded-r-lg border border-retro-cyan transition">
                    Search
                </button>
            </form>
            
            <div class="flex items-center space-x-2">
                @if(request()->has('search') || request()->has('sort_by') || request()->has('manufacturer') || request()->has('year') || request()->has('region') || request()->has('hardware_board'))
                    <a href="{{ url('/library?group=' . $group . '&system=' . request('system', 'mame')) }}" class="px-4 py-2 bg-retro-card hover:bg-opacity-80 text-gray-400 hover:text-white font-tech text-xs uppercase tracking-wider rounded-lg border border-retro-border transition flex items-center space-x-2">
                        <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                    </a>
                @endif
                <button type="button" onclick="document.getElementById('filter-modal').classList.remove('hidden')" class="px-4 py-2 bg-retro-purple hover:bg-opacity-80 text-white font-tech text-sm uppercase tracking-wider rounded-lg border border-retro-purple transition flex items-center space-x-2 shadow-[0_0_10px_rgba(157,78,221,0.3)]">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filters</span>
                </button>
            </div>
        </div>

        <!-- Filter Modal -->
        <div id="filter-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 transition-opacity">
            <div class="glass-card rounded-2xl border border-retro-border border-opacity-50 overflow-hidden shadow-2xl p-8 w-full max-w-2xl relative max-h-[90vh] overflow-y-auto">
                <button type="button" onclick="document.getElementById('filter-modal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <h2 class="font-arcade text-xl font-bold text-white mb-6 border-b border-retro-border pb-4 flex items-center space-x-2">
                    <i class="fa-solid fa-filter text-retro-purple"></i>
                    <span>Advanced Filters</span>
                </h2>
                
                <form action="{{ url('/library') }}" method="GET" class="space-y-6">
                    <input type="hidden" name="group" value="{{ $group }}">
                    <input type="hidden" name="system" value="{{ $system }}">
                    @if(request()->has('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request()->has('sort_by'))
                        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                    @endif

                    @if($system !== 'chd')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Manufacturer Filter -->
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Manufacturer</label>
                            <select name="manufacturer" class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                                <option value="">Any Manufacturer</option>
                                @foreach($manufacturers as $mfg)
                                    <option value="{{ $mfg }}" {{ request('manufacturer') === $mfg ? 'selected' : '' }}>{{ $mfg }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- By Decade Filter -->
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">By Decade</label>
                            <select name="year" class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                                <option value="">Any Decade</option>
                                @for($year = 1970; $year <= 2000; $year += 10)
                                    <option value="{{ $year }}s" {{ request('year') === $year . 's' ? 'selected' : '' }}>{{ $year }}s</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Region Filter -->
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Region</label>
                            <select name="region" class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                                <option value="">Any Region</option>
                                @if(isset($regions))
                                    @foreach($regions as $reg)
                                        <option value="{{ $reg }}" {{ request('region') === $reg ? 'selected' : '' }}>{{ $reg }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Hardware Board Filter -->
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Hardware Board</label>
                            <select name="hardware_board" class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm select-custom">
                                <option value="">All Boards</option>
                                @foreach($hardwareBoards as $board)
                                    <option value="{{ $board->id }}" {{ request('hardware_board') == $board->id ? 'selected' : '' }}>
                                        {{ $board->board }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                        <div class="text-gray-400 font-tech text-sm mb-6 text-center italic">
                            No advanced filters available for CHDs.
                        </div>
                    @endif
                    
                    <div class="flex justify-end pt-6 border-t border-retro-border space-x-3 mt-8">
                        <button type="button" onclick="document.getElementById('filter-modal').classList.add('hidden')" class="px-6 py-2 bg-retro-card hover:bg-opacity-80 text-white font-tech uppercase tracking-wider rounded-lg border border-retro-border transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-retro-purple hover:bg-opacity-80 text-white font-tech font-bold uppercase tracking-wider rounded-lg border border-retro-purple transition flex items-center space-x-2">
                            <i class="fa-solid fa-check"></i> <span>Apply Filters</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ROM Table -->
        <main class="glass-card rounded-xl border border-retro-border border-opacity-60 shadow-2xl overflow-hidden mb-6">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        @php
                            $currentSort = request('sort_by');
                            $currentOrder = request('sort_order', 'asc');
                            
                            $buildSortUrl = function($column) use ($currentSort, $currentOrder) {
                                $order = ($currentSort === $column && $currentOrder === 'asc') ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort_by' => $column, 'sort_order' => $order]);
                            };
                            
                            $sortIcon = function($column) use ($currentSort, $currentOrder) {
                                if ($currentSort !== $column) return '<i class="fa-solid fa-sort text-gray-600 ml-1"></i>';
                                return $currentOrder === 'asc' 
                                    ? '<i class="fa-solid fa-sort-up text-retro-cyan ml-1"></i>' 
                                    : '<i class="fa-solid fa-sort-down text-retro-cyan ml-1"></i>';
                            };
                        @endphp
                        <tr class="border-b border-retro-border bg-retro-card bg-opacity-70 text-xs font-tech text-retro-cyan uppercase tracking-wider">
                            @if($system === 'chd')
                                <th class="py-4 px-6 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('rom') }}" class="flex items-center">ROM {!! $sortIcon('rom') !!}</a>
                                </th>
                                <th class="py-4 px-4 text-center hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('size') }}" class="flex items-center justify-center">Size {!! $sortIcon('size') !!}</a>
                                </th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            @elseif(!in_array($system, ['mame', 'fbneo', 'chd']))
                                <th class="py-4 px-6 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('rom') }}" class="flex items-center">ROM {!! $sortIcon('rom') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('title') }}" class="flex items-center">Title {!! $sortIcon('title') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('release_date') }}" class="flex items-center">Release Date {!! $sortIcon('release_date') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('region') }}" class="flex items-center">Region {!! $sortIcon('region') !!}</a>
                                </th>
                                <th class="py-4 px-4 text-center hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('size_bytes') }}" class="flex items-center justify-center">Size {!! $sortIcon('size_bytes') !!}</a>
                                </th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            @else
                                <th class="py-4 px-6 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('rom') }}" class="flex items-center">ROM {!! $sortIcon('rom') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('full_name') }}" class="flex items-center">Full Name {!! $sortIcon('full_name') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('year') }}" class="flex items-center">Year {!! $sortIcon('year') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('manufacturer') }}" class="flex items-center">Manufacturer {!! $sortIcon('manufacturer') !!}</a>
                                </th>
                                <th class="py-4 px-4 hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('hardware_board') }}" class="flex items-center">Hardware {!! $sortIcon('hardware_board') !!}</a>
                                </th>
                                <th class="py-4 px-4 text-center hover:text-white transition cursor-pointer">
                                    <a href="{{ $buildSortUrl('size_bytes') }}" class="flex items-center justify-center">Size {!! $sortIcon('size_bytes') !!}</a>
                                </th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-retro-border divide-opacity-30 text-sm">
                        @forelse($mames as $mame)
                            <tr class="hover:bg-retro-card hover:bg-opacity-40 transition group">
                                @if($system === 'chd')
                                    <td class="py-4 px-6 font-sans font-bold text-white">
                                        <span class="group-hover:text-retro-cyan transition-colors">{{ $mame->rom }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-center font-tech text-gray-400">
                                        {{ formatSizeFromBytes($mame->size_bytes) }}
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                                        <button onclick="showDetails({{ json_encode($mame) }})" 
                                                class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-cyan text-xs font-tech uppercase tracking-wider text-gray-300 hover:text-retro-cyan rounded transition">
                                            Info
                                        </button>
                                        <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                            @csrf
                                            @php
                                                $gameClass = get_class($mame);
                                                $cartKey = $gameClass . '_' . $mame->id;
                                            @endphp
                                            <input type="hidden" name="game_id" value="{{ $mame->id }}">
                                            <input type="hidden" name="game_type" value="{{ $gameClass }}">
                                            @if(session()->has('cart') && isset(session('cart')[$cartKey]))
                                                <button type="button" class="px-2 py-1 bg-retro-card border border-retro-green text-retro-green text-xs font-tech uppercase tracking-wider rounded flex items-center space-x-1" disabled>
                                                    <i class="fa-solid fa-circle-check"></i> <span>Added</span>
                                                </button>
                                            @else
                                                <button type="submit" class="px-2 py-1 bg-retro-cyan text-black hover:bg-opacity-85 text-xs font-tech uppercase tracking-wider rounded transition flex items-center space-x-1">
                                                    <i class="fa-solid fa-plus"></i> <span>Add</span>
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                @elseif(!in_array($system, ['mame', 'fbneo', 'chd']))
                                    <td class="py-4 px-6 font-sans font-bold text-white flex items-center space-x-2">
                                        <span class="group-hover:text-retro-cyan transition-colors">{{ $mame->rom }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-300">
                                        @if(isset($activeSubPlatform) && $activeSubPlatform->screenscraper_id)
                                            <button type="button" onclick="fetchScreenScraper('{{ addslashes($mame->rom) }}', '{{ $mame->crc32 ?? '' }}')" class="hover:text-retro-cyan transition-colors font-semibold text-left group flex items-center space-x-2" title="Preview Game">
                                                <span>{{ $mame->title ?? 'N/A' }}</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                            </button>
                                        @else
                                            <button type="button" onclick="showDetails({{ json_encode($mame) }})" class="hover:text-retro-cyan transition-colors font-semibold text-left group flex items-center space-x-2" title="View Info">
                                                <span>{{ $mame->title ?? 'N/A' }}</span>
                                                <i class="fa-solid fa-circle-info text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-gray-400 font-tech">{{ $mame->release_date ? \Carbon\Carbon::parse($mame->release_date)->format('Y') : 'N/A' }}</td>
                                    <td class="py-4 px-4 text-gray-400 font-sans">{{ $mame->region ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center font-tech text-gray-400">
                                        {{ formatSizeFromBytes($mame->size_bytes) }}
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                                        <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                            @csrf
                                            @php
                                                $gameClass = get_class($mame);
                                                $cartKey = $gameClass . '_' . $mame->id;
                                            @endphp
                                            <input type="hidden" name="game_id" value="{{ $mame->id }}">
                                            <input type="hidden" name="game_type" value="{{ $gameClass }}">
                                            @if(session()->has('cart') && isset(session('cart')[$cartKey]))
                                                <button type="button" class="px-2 py-1 bg-retro-card border border-retro-green text-retro-green text-xs font-tech uppercase tracking-wider rounded flex items-center space-x-1" disabled>
                                                    <i class="fa-solid fa-circle-check"></i> <span>Added</span>
                                                </button>
                                            @else
                                                <button type="submit" class="px-2 py-1 bg-retro-cyan text-black hover:bg-opacity-85 text-xs font-tech uppercase tracking-wider rounded transition flex items-center space-x-1">
                                                    <i class="fa-solid fa-plus"></i> <span>Add</span>
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                @else
                                    <td class="py-4 px-6 font-sans font-bold text-white flex items-center space-x-2">
                                        <span class="group-hover:text-retro-cyan transition-colors">{{ $mame->rom }}</span>
                                    </td>
                                    <td class="py-4 px-4 max-w-xs truncate font-sans text-gray-200">
                                        <button type="button" onclick="fetchArcadeItalia('{{ $mame->rom }}')" class="hover:text-retro-cyan transition-colors font-semibold text-left group flex items-center space-x-2 w-full truncate" title="Preview Game">
                                            <span class="truncate">{{ $mame->title }}</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0"></i>
                                        </button>
                                    </td>
                                    <td class="py-4 px-4 font-tech text-gray-400">
                                        {{ $mame->metadata['year'] ?? ($mame->release_date ? \Carbon\Carbon::parse($mame->release_date)->format('Y') : '----') }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-300 max-w-xxs truncate">
                                        {{ $mame->metadata['manufacturer'] ?? '----' }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-400 max-w-xxs truncate font-tech">
                                        {{ $mame->hardware ?? $mame->metadata['driver'] ?? '----' }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-tech text-gray-400">
                                        @if(($mame->total_size_bytes ?? 0) > 0)
                                            <span>{{ formatSizeFromBytes($mame->total_size_bytes) }}</span>
                                        @else
                                            <span>—</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                                        <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                            @csrf
                                            @php
                                                $gameClass = get_class($mame);
                                                $cartKey = $gameClass . '_' . $mame->id;
                                            @endphp
                                            <input type="hidden" name="game_id" value="{{ $mame->id }}">
                                            <input type="hidden" name="game_type" value="{{ $gameClass }}">
                                            @if(session()->has('cart') && isset(session('cart')[$cartKey]))
                                                <button type="button" class="px-2 py-1 bg-retro-card border border-retro-green text-retro-green text-xs font-tech uppercase tracking-wider rounded flex items-center space-x-1" disabled>
                                                    <i class="fa-solid fa-circle-check"></i> <span>Added</span>
                                                </button>
                                            @else
                                                <button type="submit" class="px-2 py-1 bg-retro-cyan text-black hover:bg-opacity-85 text-xs font-tech uppercase tracking-wider rounded transition flex items-center space-x-1">
                                                    <i class="fa-solid fa-plus"></i> <span>Add</span>
                                                </button>
                                            @endif
                                        </form>
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
        function showDetails(mame) {
            // Helper to get metadata or default
            const meta = mame.metadata || {};

            // Text nodes
            document.getElementById('inspect-rom-name').innerText = mame.rom;
            document.getElementById('inspect-full-name').innerText = mame.title || mame.full_name || 'No description available.';
            document.getElementById('inspect-manufacturer').innerText = meta.manufacturer || mame.manufacturer || 'Unknown';
            document.getElementById('inspect-year').innerText = meta.year || mame.year || mame.release_date || 'Unknown';
            document.getElementById('inspect-hardware-board').innerText = mame.hardware || 'Default / Generic';
            document.getElementById('inspect-driver').innerText = meta.driver || mame.driver || '----';
            document.getElementById('inspect-cloneof').innerText = meta.cloneof || mame.cloneof || '----';
            document.getElementById('inspect-romof').innerText = meta.romof || mame.romof || '----';
            document.getElementById('inspect-sourcefile').innerText = meta.sourcefile || mame.sourcefile || '----';
            
            // Display specs
            let displayInfo = '----';
            if (meta.display_width || meta.display_height || mame.display_width || mame.display_height) {
                displayInfo = `Screen size: ${meta.display_width || mame.display_width || '?'}x${meta.display_height || mame.display_height || '?'}`;
                if (meta.display_orientation || mame.display_orientation) {
                    displayInfo += ` (${meta.display_orientation || mame.display_orientation})`;
                }
                if (meta.display_rotate || mame.display_rotate) {
                    displayInfo += ` (Rotated ${meta.display_rotate || mame.display_rotate}°)`;
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
            let baseSize = mame.size_mb || mame.size;
            if (baseSize && mame.chd) {
                sizeInfo = `${formatSize(baseSize)} + ${formatSize(mame.chd.size)} (Total: ${formatSize(mame.total_size_mb || mame.total_size)})`;
            } else if (baseSize) {
                sizeInfo = formatSize(baseSize);
            } else if (mame.chd) {
                sizeInfo = `${formatSize(mame.chd.size)} (CHD Only)`;
            }
            document.getElementById('inspect-size').innerText = sizeInfo;

            // Badges helper
            setModalBadge('badge-bios', meta.use_bios || mame.use_bios, 'text-retro-cyan', 'border-retro-cyan');
            setModalBadge('badge-chds', meta.use_chds || mame.use_chds, 'text-pink-400', 'border-pink-400');

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
            }
        });

        // Close on click outside modal
        window.addEventListener('click', function(event) {
            if (event.target === detailsModal) {
                closeDetails();
            }
        });
    </script>

    <x-arcade-italia-modal apiRoutePrefix="/library" />
    <x-screenscraper-modal apiRoutePrefix="/library" systemId="{{ $activeSubPlatform->screenscraper_id ?? 4 }}" />
    @include('components.loading-modal')
</body>
</html>
