<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives - Admin Login</title>
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
                            bg: '#0f0c1b',
                            card: '#16122c',
                            border: '#2a2456',
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
    <!-- External Retro CSS -->
    <link rel="stylesheet" href="{{ asset('css/retro.css') }}">
</head>
<body class="login-body font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Top Neon Bar -->
    <div class="fixed top-0 left-0 w-full h-1 bg-retro-cyan"></div>

    <div class="max-w-md w-full">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-retro-card rounded-2xl border border-retro-magenta mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-retro-magenta" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="font-arcade text-3xl font-black uppercase tracking-wider text-retro-cyan mb-1">
                Retro Drives
            </h1>
            <p class="font-tech text-xs text-retro-cyan tracking-widest uppercase">Games you want to play</p>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl border border-retro-border neon-glow-magenta p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="p-4 bg-retro-magenta bg-opacity-10 border border-retro-magenta rounded-xl text-retro-magenta font-tech text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center space-x-2">
                                <span>•</span>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Email Input -->
                <div>
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Admin Email</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="admin@retro-gaming.com" 
                               class="w-full pl-4 pr-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm placeholder-gray-600 transition">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Admin Password</label>
                    <div class="relative">
                        <input type="password" name="password" required autocomplete="current-password"
                               placeholder="••••••••" 
                               class="w-full pl-4 pr-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm placeholder-gray-600 transition">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer text-xs font-tech text-retro-cyan">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-retro-border bg-retro-bg text-retro-cyan focus:ring-retro-cyan focus:ring-opacity-25">
                        <span>Remember Gateway</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full py-3 bg-retro-cyan hover:bg-opacity-85 rounded-lg text-black font-arcade text-sm uppercase tracking-wider transition">
                        Request Access
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600 text-xs font-tech">
            Authorized admin credentials required.
        </div>
    </div>

</body>
</html>
