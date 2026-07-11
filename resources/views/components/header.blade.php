<header
    class="flex flex-col md:flex-row justify-between items-center mb-8 pb-6 border-b border-retro-border border-opacity-40">
    <a href="{{ url('/') }}"
        class="flex flex-col items-center md:items-start space-y-1 mb-4 md:mb-0 hover:opacity-85 transition">
        <img src="{{ asset('images/Retro-Drives-Logo-v2.png') }}" alt="Retro Drives Logo"
            class="h-16 w-auto drop-shadow-[0_0_8px_rgba(0,255,255,0.5)]">
        <div>
            <p class="font-tech text-xs tracking-widest uppercase">
                Games you want to play
            </p>
        </div>
    </a>

    <div class="flex flex-wrap justify-center items-center gap-3">
        @auth
            <!-- Admin Menu -->
            <a href="{{ url('/') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="fa-solid fa-house text-retro-cyan"></i>
                <span>Public Site</span>
            </a>
            <a href="{{ route('admin.master-platform', 'arcade') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="icon-svg icon-arcade text-retro-cyan"></i>
                <span>Arcade</span>
            </a>
            <a href="{{ route('admin.master-platform', 'console') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="icon-svg icon-console text-retro-magenta"></i>
                <span>Console</span>
            </a>
            <a href="{{ route('admin.master-platform', 'handhelds') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="icon-svg icon-hand-held text-retro-purple"></i>
                <span>Handhelds</span>
            </a>
            <a href="{{ route('admin.master-platform', 'home_computer') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="icon-svg icon-home-computer text-retro-yellow"></i>
                <span>Computers</span>
            </a>
            <a href="{{ route('admin.chds.index') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="fa-solid fa-compact-disc text-retro-teal"></i>
                <span>CHDs</span>
            </a>
            <a href="{{ route('admin.orders') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="fa-solid fa-truck-ramp-box text-retro-cyan"></i>
                <span>Orders</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-red text-retro-red hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        @else
            <!-- Public User Menu -->
            <a href="{{ url('/') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="fa-solid fa-house text-retro-cyan"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('library.index') }}"
                class="px-4 py-2 bg-retro-card hover:bg-opacity-80 rounded-lg border border-retro-border text-gray-300 hover:text-white text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="icon-svg icon-arcade text-retro-magenta"></i>
                <span>Library</span>
            </a>
            <a href="{{ route('cart.index') }}"
                class="px-4 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-bold rounded-lg border border-retro-cyan text-xs font-tech uppercase tracking-wider transition flex items-center space-x-1.5">
                <i class="fa-solid fa-hard-drive"></i>
                <span>Cart ({{ count(session('cart', [])) }})</span>
            </a>

        @endauth
    </div>
</header>