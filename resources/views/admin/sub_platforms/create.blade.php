<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Platform - Admin Backend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800;900&family=Rajdhani:wght@500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/retro.css') }}">
</head>
<body class="dashboard-body font-sans antialiased">
    <div class="h-1 w-full bg-retro-purple"></div>

    <div class="w-full max-w-4xl mx-auto px-4 sm:px-8 py-6">
        <x-header />

        @if($errors->any())
            <div class="mb-6 p-4 bg-retro-red bg-opacity-15 border border-retro-red rounded-xl text-retro-red font-tech text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center space-x-3 mb-6">
            <a href="javascript:history.back()" class="text-retro-cyan hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="glass-card rounded-2xl border border-retro-border border-opacity-50 overflow-hidden shadow-2xl p-8">
            <h2 class="font-arcade text-2xl font-bold text-white mb-6 border-b border-retro-border pb-4">Add New Sub-Platform</h2>
            
            <form action="{{ route('sub-platforms.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Parent Platform</label>
                        <select name="platform_id" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                            <option value="">Select Platform...</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>{{ $platform->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Sub-Platform Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm" placeholder="e.g. MAME">
                    </div>
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Slug (URL Parameter)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm" placeholder="e.g. mame">
                    </div>
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">ScreenScraper System <span class="text-xs text-retro-magenta ml-2" id="ss-loading">(Loading...)</span></label>
                        <select name="screenscraper_id" id="screenscraper_id" class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                            <option value="">None / Custom</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Display Order</label>
                        <input type="number" name="order_index" value="{{ old('order_index', 0) }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                    </div>
                    <div class="flex items-center h-full pt-6">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-retro-cyan bg-retro-bg border-retro-border rounded focus:ring-retro-cyan focus:ring-2">
                            <span class="text-sm font-tech text-gray-300 uppercase tracking-wider">Is Active</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-retro-cyan hover:bg-opacity-85 text-black font-bold font-tech uppercase tracking-wider rounded-lg transition shadow-[0_0_15px_rgba(0,243,255,0.4)]">
                        Save Sub-Platform
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('admin.screenscraper.systems') }}')
                .then(response => response.json())
                .then(systems => {
                    const select = document.getElementById('screenscraper_id');
                    const loading = document.getElementById('ss-loading');
                    
                    // Sort systems alphabetically
                    systems.sort((a, b) => a.nomsysteme.localeCompare(b.nomsysteme));
                    
                    systems.forEach(sys => {
                        const option = document.createElement('option');
                        option.value = sys.id;
                        option.textContent = sys.nomsysteme + ' (ID: ' + sys.id + ')';
                        select.appendChild(option);
                    });
                    
                    // Re-select old value if it exists
                    const oldVal = "{{ old('screenscraper_id') }}";
                    if (oldVal) select.value = oldVal;
                    
                    loading.style.display = 'none';
                })
                .catch(err => {
                    console.error('Error fetching systems:', err);
                    document.getElementById('ss-loading').textContent = '(Error loading systems)';
                });
        });
    </script>
</body>
</html>
