<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives - Drive Builder</title>
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
</head>
<body class="dashboard-body font-sans antialiased flex flex-col min-h-screen">

    <!-- Top Neon Bar -->
    <div class="h-1.5 w-full bg-gradient-to-r from-retro-cyan via-retro-purple to-retro-magenta shadow-[0_0_15px_rgba(255,0,127,0.5)]"></div>

    <!-- Navigation Header -->
    <nav class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 border-b border-retro-border border-opacity-40 flex justify-between items-center">
        <a href="{{ url('/') }}" class="flex items-center space-x-3 hover:opacity-85 transition">
            <div class="p-2 bg-retro-card rounded-lg border border-retro-cyan shadow-[0_0_10px_rgba(0,240,255,0.2)]">
                <i class="fa-solid fa-gamepad text-xl text-retro-cyan animate-pulse"></i>
            </div>
            <div>
                <span class="font-arcade text-xl font-black uppercase tracking-wider text-white">Retro Drives</span>
                <span class="block text-[10px] font-tech text-retro-cyan tracking-widest uppercase">Games you want to play</span>
            </div>
        </a>
        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-1.5">
                <i class="fa-solid fa-house text-retro-cyan"></i>
                <span>Home Catalog</span>
            </a>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex-1 py-12">
        <div class="flex items-center justify-between mb-8 border-b border-retro-border border-opacity-35 pb-3">
            <h1 class="font-arcade text-2xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-hard-drive text-retro-cyan"></i>
                <span>Custom Drive Builder</span>
            </h1>
            <span class="font-tech text-xs text-retro-cyan uppercase">Build Selection List</span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-retro-green bg-opacity-15 border border-retro-green rounded-xl text-retro-green font-tech text-sm flex items-center justify-between shadow-[0_0_15px_rgba(57,255,20,0.1)]">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check animate-pulse"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-retro-green hover:brightness-125">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-retro-magenta bg-opacity-10 border border-retro-magenta rounded-xl text-retro-magenta font-tech text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center space-x-2">
                        <span>•</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if(count($items) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- ROMs List Column -->
                <div class="lg:col-span-2 space-y-4">
                    <h2 class="font-arcade text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Selected Cabinet ROMs ({{ count($items) }})</h2>
                    
                    <div class="space-y-3">
                        @foreach($items as $item)
                            <div class="glass-card p-4 rounded-xl border border-retro-border flex items-center justify-between transition hover:border-retro-cyan duration-200">
                                <div>
                                    <div class="flex items-center space-x-3 mb-1">
                                        <span class="font-tech text-sm font-bold text-white uppercase">{{ $item->rom }}</span>
                                        @if($item->year)
                                            <span class="text-[10px] text-gray-500 font-tech">({{ $item->year }})</span>
                                        @endif
                                    </div>
                                    <h3 class="text-gray-300 text-sm font-semibold line-clamp-1">{{ $item->full_name }}</h3>
                                    <div class="flex items-center space-x-3 mt-1.5 text-[10px] text-gray-500">
                                        <span>Mfg: {{ $item->manufacturer ?? 'Unknown' }}</span>
                                        <span>•</span>
                                        <span>HW: {{ $item->hardware ?? $item->driver ?? '----' }}</span>
                                        @if($item->use_chds)
                                            <span>•</span>
                                            <span class="text-retro-magenta uppercase font-tech">Requires CHD</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Remove form button -->
                                <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="mame_id" value="{{ $item->id }}">
                                    <button type="submit" class="p-2 bg-retro-bg hover:bg-retro-magenta hover:bg-opacity-15 border border-retro-border hover:border-retro-magenta text-gray-500 hover:text-retro-magenta rounded-lg transition" title="Remove Game">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Checkout Details Column -->
                <div class="space-y-6">
                    <div class="glass-card p-6 rounded-xl border border-retro-border neon-glow-cyan">
                        <h2 class="font-arcade text-sm font-semibold text-retro-cyan uppercase tracking-wider mb-4 pb-2 border-b border-retro-border border-opacity-30">Order Placement</h2>
                        
                        <form action="{{ route('checkout.submit') }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Drive Media Type -->
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Select Media Format</label>
                                <select name="drive_type" required class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-xs">
                                    <option value="SD Card 64GB">Micro SD Card (64GB) - Classic Catalog</option>
                                    <option value="SD Card 128GB" selected>Micro SD Card (128GB) - Advanced Catalog</option>
                                    <option value="USB Drive 256GB">USB 3.0 Flash Drive (256GB) - Extended Showcase</option>
                                    <option value="Hard Drive 1TB">External USB Hard Drive (1TB) - Complete Catalog</option>
                                </select>
                            </div>

                            <!-- Recipient Name -->
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Recipient Full Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                       placeholder="John Doe" 
                                       class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-xs placeholder-gray-600 transition">
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Delivery Address</label>
                                <textarea name="address" rows="3" required
                                          placeholder="Street address, apartment, suite..." 
                                          class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-xs placeholder-gray-600 transition resize-none"></textarea>
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" required
                                       placeholder="New York" 
                                       class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-xs placeholder-gray-600 transition">
                            </div>

                            <!-- Postal Code & Country -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Zip/Postal Code</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" required
                                           placeholder="10001" 
                                           class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-xs placeholder-gray-600 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Country</label>
                                    <input type="text" name="country" value="{{ old('country', 'United States') }}" required
                                           placeholder="USA" 
                                           class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-xs placeholder-gray-600 transition">
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full py-3 bg-gradient-to-r from-retro-cyan via-retro-purple to-retro-magenta hover:brightness-110 text-white font-arcade text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(0,240,255,0.3)]">
                                    Place Order Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty selection alert -->
            <div class="text-center py-20 bg-retro-card bg-opacity-40 rounded-2xl border border-retro-border border-opacity-50">
                <i class="fa-solid fa-compact-disc text-5xl mb-4 block text-retro-border animate-spin-slow"></i>
                <h2 class="font-arcade text-lg text-white mb-2 uppercase">Your drive list is empty</h2>
                <p class="text-gray-500 text-sm max-w-md mx-auto mb-6">You haven't selected any games yet. Browse our cabinet showcase to select games you want to write to your custom drive media.</p>
                <a href="{{ url('/') }}" class="px-5 py-2.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded-lg text-xs font-tech uppercase tracking-wider transition">Browse Catalog</a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 text-center text-gray-600 text-xs font-tech border-t border-retro-border border-opacity-20 mt-12">
        Retro Drives &copy; {{ date('Y') }} • Games you want to play
    </footer>

</body>
</html>
