<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives - Games you want to play</title>
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
                            cyan: '#00f0ff',
                            magenta: '#ff007f',
                            purple: '#9d4edd',
                            green: '#39ff14',
                        }
                    },
                    fontFamily: {
                        arcade: ['Orbitron', 'sans-serif'],
                        tech: ['Share Tech Mono', 'monospace'],
                        sans: ['Rajdhani', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External Retro CSS -->
    <link rel="stylesheet" href="{{ asset('css/retro.css') }}">
    
    <style>
        .cabinet-glow:hover {
            border-color: #00f0ff;
        }
    </style>
</head>
<body class="dashboard-body font-sans antialiased flex flex-col min-h-screen">

    <!-- Top Neon Bar -->
    <div class="h-1 w-full bg-retro-cyan"></div>

    <!-- Navigation Header -->
    <nav class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 border-b border-retro-border border-opacity-40 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-retro-card rounded-lg border border-retro-cyan">
                <i class="fa-solid fa-gamepad text-xl text-retro-cyan"></i>
            </div>
            <div>
                <span class="font-arcade text-xl font-black uppercase tracking-wider text-white">Retro Drives</span>
                <span class="block text-[10px] font-tech text-retro-cyan tracking-widest uppercase">Games you want to play</span>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('cart.index') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5 shadow-[0_0_10px_rgba(0,240,255,0.1)] relative">
                <i class="fa-solid fa-hard-drive text-retro-cyan"></i>
                <span>Drive Builder</span>
                @if(session()->has('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-2 -right-2 bg-retro-magenta text-white text-[9px] font-tech font-bold px-1.5 py-0.5 rounded-full shadow-[0_0_8px_rgba(255,0,127,0.6)]">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
            
            @guest
                <a href="{{ route('register') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-user-plus text-retro-cyan"></i>
                    <span>Register</span>
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-magenta text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-lock text-retro-magenta"></i>
                    <span>Admin Login</span>
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-user-shield text-retro-cyan"></i>
                    <span>Admin Portal</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-magenta text-gray-400 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Exit</span>
                    </button>
                </form>
            @endguest
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex-1 py-12 flex flex-col justify-center">
        
        <!-- Hero Section -->
        <section class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="font-arcade text-5xl md:text-7xl font-black tracking-wider uppercase text-retro-cyan mb-4">
                Retro Drives
            </h1>
            <p class="font-arcade text-lg md:text-xl text-retro-cyan uppercase tracking-widest mb-6">
                Games you want to play
            </p>
            <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-8">
                Welcome to the ultimate arcade cabinet archive. Explore, search, and inspect vintage ROM hardware configurations across thousands of classic games from our <span class="text-retro-cyan font-semibold">MAME</span> and <span class="text-retro-magenta font-semibold">FBNeo</span> catalogs.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3.5 bg-retro-cyan hover:bg-opacity-85 text-black font-arcade text-xs uppercase tracking-wider rounded-xl transition">
                    Explore Database
                </a>
            </div>
        </section>

        <!-- Featured Games Showcase -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-8 border-b border-retro-border border-opacity-35 pb-3">
                <h2 class="font-arcade text-lg font-bold text-white flex items-center space-x-2">
                    <i class="fa-solid fa-circle-play text-retro-magenta animate-spin-slow"></i>
                    <span>Cabinet Catalog Highlights</span>
                </h2>
                <span class="font-tech text-xs text-gray-500 uppercase">Featured Hardware Configs</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($featuredGames as $game)
                    <div class="glass-card rounded-2xl border border-retro-border p-6 cabinet-glow transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="px-2 py-0.5 rounded bg-retro-cyan bg-opacity-15 border border-retro-cyan border-opacity-35 text-[10px] text-retro-cyan font-tech uppercase">
                                    {{ $game->rom }}
                                </span>
                                <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="mame_id" value="{{ $game->id }}">
                                    @if(session()->has('cart') && isset(session('cart')[$game->id]))
                                        <button type="button" class="text-retro-green text-xs font-tech flex items-center space-x-1" disabled>
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Added</span>
                                        </button>
                                    @else
                                        <button type="submit" class="text-retro-cyan hover:text-white text-xs font-tech flex items-center space-x-1 border border-retro-border bg-retro-card px-2 py-1 rounded transition">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                            <span>Add to Drive</span>
                                        </button>
                                    @endif
                                </form>
                            </div>
                            <h3 class="font-sans text-lg font-bold text-white mb-2 line-clamp-1">
                                {{ $game->full_name }}
                            </h3>
                            <p class="text-gray-400 text-xs line-clamp-2 mb-4 leading-relaxed font-sans">
                                Manufacturer: {{ $game->manufacturer ?? 'Unknown' }}
                            </p>
                        </div>
                        
                        <div class="border-t border-retro-border border-opacity-30 pt-3 flex justify-between items-center text-xs">
                            <span class="text-gray-500 font-tech">Hardware:</span>
                            <span class="text-gray-300 font-tech line-clamp-1 max-w-[150px]">
                                {{ $game->hardware ?? $game->driver ?? '----' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 font-tech">
                        <i class="fa-solid fa-gamepad text-3xl mb-2 block opacity-40"></i>
                        No highlight ROMs loaded yet. Check back soon!
                    </div>
                @endforelse
            </div>
        </section>
        
    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 text-center text-gray-600 text-xs font-tech border-t border-retro-border border-opacity-20 mt-12">
        Retro Drives &copy; {{ date('Y') }} • Games you want to play
    </footer>

</body>
</html>
