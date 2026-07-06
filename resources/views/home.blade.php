<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives – Custom Retro Gaming Drives | USB & SD Card</title>
    <meta name="description" content="Build your own custom retro gaming drive. Choose from thousands of arcade, console and home computer games. Supplied on USB stick or SD card for Raspberry Pi.">

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
                            yellow: '#ffd700',
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
        .cabinet-glow:hover { border-color: #00f0ff; }
        .media-card:hover { border-color: #00f0ff; transform: translateY(-2px); }
        .media-card { transition: border-color 0.2s, transform 0.2s; }
        .step-number {
            width: 2.5rem; height: 2.5rem;
            background: #00f0ff;
            color: #0a051b;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="dashboard-body font-sans antialiased flex flex-col min-h-screen">

    <!-- Top Neon Bar -->
    <div class="h-1 w-full bg-retro-cyan"></div>

    <!-- Navigation Header -->
    <nav class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-5 border-b border-retro-border border-opacity-40 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-retro-card rounded-lg border border-retro-cyan">
                <i class="fa-solid fa-hard-drive text-xl text-retro-cyan"></i>
            </div>
            <div>
                <span class="font-arcade text-xl font-black uppercase tracking-wider text-white">Retro Drives</span>
                <span class="block text-[10px] font-tech text-retro-cyan tracking-widest uppercase">Games you want to play</span>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('cart.index') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5 relative">
                <i class="fa-solid fa-hard-drive text-retro-cyan"></i>
                <span>My Drive</span>
                @if(session()->has('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-2 -right-2 bg-retro-magenta text-white text-[9px] font-tech font-bold px-1.5 py-0.5 rounded-full">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

            @guest
                <a href="{{ route('register') }}" class="px-4 py-2 bg-retro-cyan text-black text-xs font-tech uppercase tracking-wider rounded-lg transition hover:bg-opacity-85 flex items-center space-x-1.5">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Get Started</span>
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-magenta text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-lock text-retro-magenta"></i>
                    <span>Login</span>
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-user-shield text-retro-cyan"></i>
                    <span>Admin</span>
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
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex-1 py-12">

        <!-- Hero Section -->
        <section class="text-center max-w-4xl mx-auto mb-20">
            <div class="inline-flex items-center space-x-2 bg-retro-card border border-retro-border rounded-full px-4 py-1.5 mb-6">
                <span class="h-2 w-2 rounded-full bg-retro-green"></span>
                <span class="font-tech text-xs text-gray-400 uppercase tracking-widest">Handcrafted &amp; posted to you</span>
            </div>
            <h1 class="font-arcade text-4xl md:text-6xl font-black tracking-wider uppercase text-retro-cyan mb-6 leading-tight">
                Your Custom<br>Retro Gaming Drive
            </h1>
            <p class="text-gray-300 text-base md:text-lg leading-relaxed mb-4 max-w-2xl mx-auto">
                Pick the classic games you love from our library of <span class="text-retro-cyan font-semibold">{{ number_format(\App\Models\Mame::count() + \App\Models\Snes::count()) }}+</span> ROMs across arcade, console and home computer platforms.
            </p>
            <p class="text-gray-500 text-sm leading-relaxed mb-10 max-w-2xl mx-auto">
                We load them onto your chosen media and post it straight to your door — ready to plug into your PC or Raspberry Pi.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('register') }}" class="px-7 py-3.5 bg-retro-cyan text-black font-arcade text-xs uppercase tracking-wider rounded-xl transition hover:bg-opacity-85">
                    Start Building Your Drive
                </a>
                <a href="#how-it-works" class="px-7 py-3.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white font-arcade text-xs uppercase tracking-wider rounded-xl transition">
                    How It Works
                </a>
            </div>
        </section>

        <!-- Media Format Cards -->
        <section class="mb-20">
            <h2 class="font-arcade text-lg font-bold text-white text-center uppercase tracking-wider mb-2">Choose Your Media</h2>
            <p class="text-center text-gray-500 font-tech text-xs uppercase tracking-wider mb-8">USB stick for PC &bull; SD card for Raspberry Pi</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- 16 GB -->
                <div class="media-card glass-card rounded-xl border border-retro-border p-5">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-retro-card rounded-lg border border-retro-border">
                            <i class="fa-solid fa-memory text-retro-cyan text-lg"></i>
                        </div>
                        <div>
                            <div class="font-arcade text-xl font-bold text-white">16 GB</div>
                            <div class="font-tech text-[10px] text-gray-500 uppercase tracking-wider">Starter</div>
                        </div>
                    </div>
                    <ul class="space-y-1.5 mb-4">
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>~2,000 arcade ROMs</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>USB 3.0 or MicroSD</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>PC &amp; Raspberry Pi</span>
                        </li>
                    </ul>
                    <span class="inline-block w-full text-center text-[10px] font-tech text-gray-600 uppercase border-t border-retro-border pt-3">Good for casual play</span>
                </div>

                <!-- 32 GB -->
                <div class="media-card glass-card rounded-xl border border-retro-border p-5">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-retro-card rounded-lg border border-retro-border">
                            <i class="fa-solid fa-memory text-retro-purple text-lg"></i>
                        </div>
                        <div>
                            <div class="font-arcade text-xl font-bold text-white">32 GB</div>
                            <div class="font-tech text-[10px] text-gray-500 uppercase tracking-wider">Standard</div>
                        </div>
                    </div>
                    <ul class="space-y-1.5 mb-4">
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>~5,000+ ROMs</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>USB 3.0 or MicroSD</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>Arcade + Console mix</span>
                        </li>
                    </ul>
                    <span class="inline-block w-full text-center text-[10px] font-tech text-gray-600 uppercase border-t border-retro-border pt-3">Most popular</span>
                </div>

                <!-- 64 GB -->
                <div class="media-card glass-card rounded-xl border border-retro-cyan p-5">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[9px] font-tech text-retro-cyan uppercase tracking-widest bg-retro-cyan bg-opacity-10 px-2 py-0.5 rounded-full border border-retro-cyan border-opacity-30">Best Value</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-retro-card rounded-lg border border-retro-cyan">
                            <i class="fa-solid fa-hard-drive text-retro-cyan text-lg"></i>
                        </div>
                        <div>
                            <div class="font-arcade text-xl font-bold text-retro-cyan">64 GB</div>
                            <div class="font-tech text-[10px] text-gray-500 uppercase tracking-wider">Ultimate</div>
                        </div>
                    </div>
                    <ul class="space-y-1.5 mb-4">
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>Full catalog selection</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>USB 3.0 or MicroSD</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>Arcade, Console &amp; Home Computer</span>
                        </li>
                    </ul>
                    <span class="inline-block w-full text-center text-[10px] font-tech text-retro-cyan uppercase border-t border-retro-cyan border-opacity-30 pt-3">For the serious collector</span>
                </div>

                <!-- Raspberry Pi -->
                <div class="media-card glass-card rounded-xl border border-retro-border p-5">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-retro-card rounded-lg border border-retro-border">
                            <i class="fa-brands fa-raspberry-pi text-retro-magenta text-lg"></i>
                        </div>
                        <div>
                            <div class="font-arcade text-base font-bold text-white leading-tight">Raspberry<br>Pi Ready</div>
                        </div>
                    </div>
                    <ul class="space-y-1.5 mb-4">
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>MicroSD cards</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>16, 32 or 64 GB</span>
                        </li>
                        <li class="flex items-center space-x-2 text-xs font-tech text-gray-400">
                            <i class="fa-solid fa-check text-retro-green text-[10px]"></i>
                            <span>RetroPie compatible</span>
                        </li>
                    </ul>
                    <span class="inline-block w-full text-center text-[10px] font-tech text-gray-600 uppercase border-t border-retro-border pt-3">Plug in and play</span>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="mb-20">
            <h2 class="font-arcade text-lg font-bold text-white text-center uppercase tracking-wider mb-2">How It Works</h2>
            <p class="text-center text-gray-500 font-tech text-xs uppercase tracking-wider mb-10">Three simple steps to your custom drive</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-card rounded-xl border border-retro-border p-6 flex flex-col">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="step-number">1</div>
                        <div>
                            <h3 class="font-arcade text-sm font-bold text-white uppercase tracking-wide mb-1">Browse &amp; Select</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Create a free account and browse our full ROM catalog. Search by game title, platform, or manufacturer and add the titles you want to your drive.</p>
                        </div>
                    </div>
                    <div class="mt-auto pt-4 border-t border-retro-border border-opacity-30">
                        <div class="flex items-center space-x-2 text-xs font-tech text-gray-500">
                            <i class="fa-solid fa-gamepad text-retro-cyan"></i>
                            <span>{{ number_format(\App\Models\Mame::count()) }} arcade &bull; {{ number_format(\App\Models\Snes::count()) }} SNES and growing</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-xl border border-retro-border p-6 flex flex-col">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="step-number">2</div>
                        <div>
                            <h3 class="font-arcade text-sm font-bold text-white uppercase tracking-wide mb-1">Choose Your Media</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">Select a 16, 32, or 64 GB USB stick for Windows/PC, or a MicroSD card if you're running a Raspberry Pi with RetroPie or Recalbox.</p>
                        </div>
                    </div>
                    <div class="mt-auto pt-4 border-t border-retro-border border-opacity-30">
                        <div class="flex items-center space-x-2 text-xs font-tech text-gray-500">
                            <i class="fa-solid fa-sd-card text-retro-purple"></i>
                            <span>USB 3.0 &bull; MicroSD &bull; All sizes</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-xl border border-retro-border p-6 flex flex-col">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="step-number">3</div>
                        <div>
                            <h3 class="font-arcade text-sm font-bold text-white uppercase tracking-wide mb-1">We Post It To You</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">We load your selected games onto the drive and post it to your door. Just plug it in and start playing — no setup, no fuss.</p>
                        </div>
                    </div>
                    <div class="mt-auto pt-4 border-t border-retro-border border-opacity-30">
                        <div class="flex items-center space-x-2 text-xs font-tech text-gray-500">
                            <i class="fa-solid fa-truck text-retro-green"></i>
                            <span>Handcrafted &bull; Posted direct</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Catalog Stats -->
        <section class="mb-20">
            <h2 class="font-arcade text-lg font-bold text-white text-center uppercase tracking-wider mb-2">What's In The Catalog</h2>
            <p class="text-center text-gray-500 font-tech text-xs uppercase tracking-wider mb-8">Thousands of titles across three platform groups</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="glass-card rounded-xl border border-retro-border p-6 text-center">
                    <i class="fa-solid fa-gamepad text-3xl text-retro-cyan mb-3 block"></i>
                    <div class="font-arcade text-3xl font-bold text-retro-cyan mb-1">{{ number_format(\App\Models\Mame::count()) }}</div>
                    <div class="font-arcade text-sm text-white uppercase tracking-wider mb-2">Arcade ROMs</div>
                    <p class="text-gray-500 text-xs font-tech">MAME &amp; FBNeo — the classic arcade experience. Street Fighter, Pac-Man, Metal Slug and thousands more.</p>
                </div>
                <div class="glass-card rounded-xl border border-retro-border p-6 text-center">
                    <i class="fa-solid fa-tv text-3xl text-retro-purple mb-3 block"></i>
                    <div class="font-arcade text-3xl font-bold text-retro-purple mb-1">{{ number_format(\App\Models\Snes::count()) }}</div>
                    <div class="font-arcade text-sm text-white uppercase tracking-wider mb-2">SNES ROMs</div>
                    <p class="text-gray-500 text-xs font-tech">Super Nintendo — Mario, Zelda, Donkey Kong Country and the full golden-age console library.</p>
                </div>
                <div class="glass-card rounded-xl border border-retro-border p-6 text-center">
                    <i class="fa-solid fa-computer text-3xl text-retro-yellow mb-3 block"></i>
                    <div class="font-arcade text-3xl font-bold text-retro-yellow mb-1">Soon</div>
                    <div class="font-arcade text-sm text-white uppercase tracking-wider mb-2">Home Computer</div>
                    <p class="text-gray-500 text-xs font-tech">ZX Spectrum, Amstrad CPC, Commodore 64 and more — coming very soon.</p>
                </div>
            </div>
        </section>

        <!-- Featured Games -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6 border-b border-retro-border border-opacity-35 pb-3">
                <h2 class="font-arcade text-lg font-bold text-white uppercase tracking-wider">Featured Titles</h2>
                <a href="{{ route('register') }}" class="font-tech text-xs text-retro-cyan hover:underline uppercase tracking-wider">Browse full catalog →</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($featuredGames as $game)
                    <div class="glass-card rounded-xl border border-retro-border p-5 cabinet-glow flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <span class="px-2 py-0.5 rounded bg-retro-card border border-retro-border text-[10px] text-gray-400 font-tech uppercase">{{ $game->rom }}</span>
                                <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="mame_id" value="{{ $game->id }}">
                                    @if(session()->has('cart') && isset(session('cart')[$game->id]))
                                        <button type="button" class="text-retro-green text-xs font-tech flex items-center space-x-1" disabled>
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Added</span>
                                        </button>
                                    @else
                                        <button type="submit" class="text-xs font-tech flex items-center space-x-1 bg-retro-cyan text-black px-2 py-1 rounded transition hover:bg-opacity-85">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                            <span>Add</span>
                                        </button>
                                    @endif
                                </form>
                            </div>
                            <h3 class="font-sans text-base font-bold text-white mb-1 line-clamp-1">{{ $game->full_name }}</h3>
                            <p class="text-gray-500 text-xs font-tech">{{ $game->manufacturer ?? 'Unknown manufacturer' }}</p>
                        </div>
                        <div class="border-t border-retro-border border-opacity-25 pt-3 mt-3">
                            <span class="text-gray-600 text-xs font-tech">{{ $game->hardware ?? $game->driver ?? '—' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 font-tech">
                        <i class="fa-solid fa-gamepad text-3xl mb-2 block opacity-40"></i>
                        No featured games loaded yet.
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-retro-border border-opacity-20 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fa-solid fa-hard-drive text-retro-cyan"></i>
                        <span class="font-arcade text-sm text-white uppercase tracking-wider">Retro Drives</span>
                    </div>
                    <p class="text-gray-600 text-xs font-tech leading-relaxed">Custom retro gaming drives, handcrafted and posted to your door. Your games, your media.</p>
                </div>
                <div>
                    <h4 class="font-tech text-xs text-gray-500 uppercase tracking-widest mb-3">Media Options</h4>
                    <ul class="space-y-1.5 text-xs font-tech text-gray-600">
                        <li>USB Stick — 16 GB, 32 GB, 64 GB</li>
                        <li>MicroSD — 16 GB, 32 GB, 64 GB</li>
                        <li>Raspberry Pi (RetroPie / Recalbox)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-tech text-xs text-gray-500 uppercase tracking-widest mb-3">Platforms</h4>
                    <ul class="space-y-1.5 text-xs font-tech text-gray-600">
                        <li>Arcade — MAME &amp; FBNeo</li>
                        <li>Console — SNES &amp; more coming</li>
                        <li>Home Computer — coming soon</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-retro-border border-opacity-20 pt-4 text-center text-gray-700 text-xs font-tech">
                Retro Drives &copy; {{ date('Y') }} — Games you want to play.
            </div>
        </div>
    </footer>

</body>
</html>
