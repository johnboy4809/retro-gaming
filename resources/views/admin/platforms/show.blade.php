<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage {{ $platform->name }} - Admin Backend</title>
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

    <!-- Top Neon Bar -->
    <div class="h-1 w-full bg-{{ $platform->color }}"></div>

    <div class="w-full max-w-none px-4 sm:px-8 lg:px-12 py-6">
        
        <!-- Header -->
        <x-header />

        <!-- Flash Messages -->
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

        <div class="flex justify-between items-center mb-8">
            <h1 class="font-arcade text-3xl font-bold text-white flex items-center space-x-3">
                <i class="icon-svg {{ $platform->icon }} text-{{ $platform->color }}"></i>
                <span>{{ $platform->name }} DB</span>
            </h1>
            <div class="flex space-x-3">
                <button type="button" onclick="openCreateModal()" class="px-6 py-2.5 bg-retro-green hover:bg-opacity-85 text-black font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-2 shadow-[0_0_15px_rgba(57,255,20,0.4)]">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Sub-Platform</span>
                </button>
            </div>
        </div>

        <div class="glass-card rounded-2xl border border-retro-border border-opacity-50 overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm font-tech text-gray-300">
                    <thead class="text-xs uppercase bg-retro-card text-retro-cyan border-b border-retro-border border-opacity-50">
                        <tr>
                            <th scope="col" class="px-6 py-4">Order</th>
                            <th scope="col" class="px-6 py-4">Sub-Platform</th>
                            <th scope="col" class="px-6 py-4">Slug</th>
                            <th scope="col" class="px-6 py-4">ROMs</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($platform->subPlatforms as $subPlatform)
                            <tr class="border-b border-retro-border border-opacity-30 hover:bg-retro-card hover:bg-opacity-50 transition">
                                <td class="px-6 py-4">{{ $subPlatform->order_index }}</td>
                                <td class="px-6 py-4 font-bold text-white">{{ $subPlatform->name }}</td>
                                <td class="px-6 py-4">{{ $subPlatform->slug }}</td>
                                <td class="px-6 py-4 font-tech text-retro-cyan font-bold">{{ $subPlatform->rom_count }}</td>
                                <td class="px-6 py-4">
                                    @if($subPlatform->is_active)
                                        <span class="px-2 py-1 bg-retro-green bg-opacity-20 text-retro-green rounded text-xs border border-retro-green border-opacity-30">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-retro-red bg-opacity-20 text-retro-red rounded text-xs border border-retro-red border-opacity-30">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    @php
                                        $routePrefix = null;
                                        if ($platform->slug === 'arcade') $routePrefix = 'arcade';
                                        elseif ($platform->slug === 'console') $routePrefix = 'console';
                                        elseif ($platform->slug === 'handhelds') $routePrefix = 'handheld';
                                        elseif ($platform->slug === 'home_computer') $routePrefix = 'computer';
                                    @endphp
                                    @if($routePrefix)
                                        <a href="{{ route($routePrefix . '.games.index', $subPlatform->id) }}" class="text-retro-magenta hover:text-white transition opacity-70 hover:opacity-100">
                                            <i class="fa-solid fa-gamepad"></i> Manage Games
                                        </a>
                                    @endif
                                    <button type="button" onclick="openEditModal({{ $subPlatform->id }}, '{{ addslashes($subPlatform->name) }}', '{{ addslashes($subPlatform->slug) }}', '{{ $subPlatform->screenscraper_id }}', {{ $subPlatform->order_index }}, {{ $subPlatform->is_active ? 'true' : 'false' }})" class="text-retro-cyan hover:text-white transition opacity-70 hover:opacity-100">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <form action="{{ route('sub-platforms.destroy', $subPlatform->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this sub-platform?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-retro-red hover:text-white transition opacity-70 hover:opacity-100">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($platform->subPlatforms->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        <i class="fa-solid fa-gamepad text-4xl mb-3 opacity-30"></i>
                        <p>No sub-platforms found for {{ $platform->name }}.</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- Create Modal -->
        <div id="create-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center {{ $errors->any() && old('_method') !== 'PUT' ? '' : 'hidden' }} p-4 transition-opacity">
            <div class="glass-card rounded-2xl border border-retro-border border-opacity-50 overflow-hidden shadow-2xl p-8 w-full max-w-2xl relative">
                <button type="button" onclick="closeCreateModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <h2 class="font-arcade text-2xl font-bold text-white mb-6 border-b border-retro-border pb-4">Add New Sub-Platform</h2>
                
                @if($errors->any() && old('_method') !== 'PUT')
                    <div class="mb-6 p-4 bg-retro-red bg-opacity-15 border border-retro-red rounded-xl text-retro-red font-tech text-sm">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sub-platforms.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="platform_id" value="{{ $platform->id }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Sub-Platform Name</label>
                            <input type="text" name="name" value="{{ old('_method') !== 'PUT' ? old('name') : '' }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm" placeholder="e.g. MAME">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Slug (URL Parameter)</label>
                            <input type="text" name="slug" value="{{ old('_method') !== 'PUT' ? old('slug') : '' }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm" placeholder="e.g. mame">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">ScreenScraper System <span class="text-xs text-retro-magenta ml-2 ss-loading">(Loading...)</span></label>
                            <select name="screenscraper_id" class="screenscraper-select w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm" data-old="{{ old('_method') !== 'PUT' ? old('screenscraper_id') : '' }}">
                                <option value="">None / Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Display Order</label>
                            <input type="number" name="order_index" value="{{ old('_method') !== 'PUT' ? old('order_index', 0) : 0 }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                        </div>
                        <div class="flex items-center h-full pt-6 md:col-span-2">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ (old('_method') !== 'PUT' && old('is_active', true)) ? 'checked' : '' }} class="w-5 h-5 text-retro-cyan bg-retro-bg border-retro-border rounded focus:ring-retro-cyan focus:ring-2">
                                <span class="text-sm font-tech text-gray-300 uppercase tracking-wider">Is Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateModal()" class="px-6 py-3 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-retro-cyan font-bold font-tech uppercase tracking-wider rounded-lg transition">Cancel</button>
                        <button type="submit" class="px-8 py-3 bg-retro-cyan hover:bg-opacity-85 text-black font-bold font-tech uppercase tracking-wider rounded-lg transition shadow-[0_0_15px_rgba(0,243,255,0.4)]">
                            Save Sub-Platform
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center {{ $errors->any() && old('_method') === 'PUT' ? '' : 'hidden' }} p-4 transition-opacity">
            <div class="glass-card rounded-2xl border border-retro-border border-opacity-50 overflow-hidden shadow-2xl p-8 w-full max-w-2xl relative">
                <button type="button" onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <h2 class="font-arcade text-2xl font-bold text-white mb-6 border-b border-retro-border pb-4">Edit Sub-Platform</h2>
                
                @if($errors->any() && old('_method') === 'PUT')
                    <div class="mb-6 p-4 bg-retro-red bg-opacity-15 border border-retro-red rounded-xl text-retro-red font-tech text-sm">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="edit-form" action="{{ old('_method') === 'PUT' && old('sub_platform_id') ? route('sub-platforms.update', old('sub_platform_id')) : '#' }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="platform_id" value="{{ $platform->id }}">
                    <input type="hidden" name="sub_platform_id" id="edit-id" value="{{ old('_method') === 'PUT' ? old('sub_platform_id') : '' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Sub-Platform Name</label>
                            <input type="text" name="name" id="edit-name" value="{{ old('_method') === 'PUT' ? old('name') : '' }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Slug (URL Parameter)</label>
                            <input type="text" name="slug" id="edit-slug" value="{{ old('_method') === 'PUT' ? old('slug') : '' }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">ScreenScraper System <span class="text-xs text-retro-magenta ml-2 ss-loading">(Loading...)</span></label>
                            <select name="screenscraper_id" id="edit-screenscraper" class="screenscraper-select w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm" data-old="{{ old('_method') === 'PUT' ? old('screenscraper_id') : '' }}">
                                <option value="">None / Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-2">Display Order</label>
                            <input type="number" name="order_index" id="edit-order" value="{{ old('_method') === 'PUT' ? old('order_index') : '' }}" required class="w-full px-4 py-3 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan text-white text-sm">
                        </div>
                        <div class="flex items-center h-full pt-6 md:col-span-2">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_active" id="edit-active" value="1" {{ (old('_method') === 'PUT' && old('is_active')) ? 'checked' : '' }} class="w-5 h-5 text-retro-cyan bg-retro-bg border-retro-border rounded focus:ring-retro-cyan focus:ring-2">
                                <span class="text-sm font-tech text-gray-300 uppercase tracking-wider">Is Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-retro-card border border-retro-border hover:border-retro-cyan text-gray-300 hover:text-retro-cyan font-bold font-tech uppercase tracking-wider rounded-lg transition">Cancel</button>
                        <button type="submit" class="px-8 py-3 bg-retro-cyan hover:bg-opacity-85 text-black font-bold font-tech uppercase tracking-wider rounded-lg transition shadow-[0_0_15px_rgba(0,243,255,0.4)]">
                            Update Sub-Platform
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Modal Logic
        function openCreateModal() {
            document.getElementById('create-modal').classList.remove('hidden');
        }
        function closeCreateModal() {
            document.getElementById('create-modal').classList.add('hidden');
        }
        function openEditModal(id, name, slug, screenscraperId, order, isActive) {
            document.getElementById('edit-form').action = '/admin/sub-platforms/' + id;
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-slug').value = slug;
            document.getElementById('edit-order').value = order;
            document.getElementById('edit-active').checked = isActive;
            
            // Set screenscraper value if systems are already loaded
            const select = document.getElementById('edit-screenscraper');
            if (select.options.length > 1) { // more than just "None"
                select.value = screenscraperId;
            } else {
                // systems haven't loaded yet, store it in data-old so the fetch block will pick it up
                select.setAttribute('data-old', screenscraperId);
            }
            
            document.getElementById('edit-modal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        // Fetch ScreenScraper Systems
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('admin.screenscraper.systems') }}')
                .then(response => response.json())
                .then(systems => {
                    const selects = document.querySelectorAll('.screenscraper-select');
                    const loadings = document.querySelectorAll('.ss-loading');
                    
                    systems.sort((a, b) => a.nomsysteme.localeCompare(b.nomsysteme));
                    
                    selects.forEach(select => {
                        systems.forEach(sys => {
                            const option = document.createElement('option');
                            option.value = sys.id;
                            option.textContent = sys.nomsysteme + ' (ID: ' + sys.id + ')';
                            select.appendChild(option);
                        });
                        
                        // Select old value if it exists
                        const oldVal = select.getAttribute('data-old');
                        if (oldVal) {
                            select.value = oldVal;
                        }
                    });
                    
                    loadings.forEach(l => l.style.display = 'none');
                })
                .catch(err => {
                    console.error('Error fetching systems:', err);
                    document.querySelectorAll('.ss-loading').forEach(l => l.textContent = '(Error loading systems)');
                });
        });
    </script>
</body>
</html>
