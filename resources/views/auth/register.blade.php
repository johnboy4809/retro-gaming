<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives - Create Account</title>
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
                        }
                    },
                    fontFamily: {
                        arcade: ['Orbitron', 'sans-serif'],
                        tech: ['Share Tech Mono', 'monospace'],
                        sans: ['Orbitron', 'sans-serif'],
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
            <div class="inline-block p-4 bg-retro-card rounded-2xl border border-retro-cyan mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-retro-cyan" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="font-arcade text-3xl font-black uppercase tracking-wider text-retro-cyan mb-1">
                Retro Drives
            </h1>
            <p class="font-tech text-xs text-retro-cyan tracking-widest uppercase">Games you want to play</p>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl border border-retro-border neon-glow-cyan p-8">
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
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

                <!-- Name Input -->
                <div>
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="John Doe" 
                           class="w-full pl-4 pr-4 py-2.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-600 transition">
                </div>

                <!-- Email Input -->
                <div>
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="john@example.com" 
                           class="w-full pl-4 pr-4 py-2.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-600 transition">
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" required autocomplete="new-password"
                           placeholder="••••••••" 
                           class="w-full pl-4 pr-4 py-2.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-600 transition">
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           placeholder="••••••••" 
                           class="w-full pl-4 pr-4 py-2.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-600 transition">
                </div>

                <!-- Register Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-retro-cyan hover:bg-opacity-85 rounded-lg text-black font-arcade text-sm uppercase tracking-wider transition">
                        Register Account
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-xs font-tech">
            <span class="text-gray-600">Already registered?</span>
            <a href="{{ route('login') }}" class="text-retro-cyan hover:underline ml-1">Access Portal</a>
        </div>
    </div>

</body>
</html>
