<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage CHDs - RetroGaming Admin</title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="dashboard-body font-sans antialiased text-gray-300">

    <div class="h-1 w-full bg-retro-magenta"></div>

    <div class="w-full max-w-none px-4 sm:px-8 lg:px-12 py-6">
        
        <x-header />

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
                        <span class="font-bold">Errors Occurred:</span>
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

        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.master-platform', 'arcade') }}" class="text-retro-cyan hover:text-white font-tech text-sm mb-2 inline-block"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Arcade Platforms</a>
                <h1 class="font-arcade text-3xl font-bold text-white flex items-center space-x-3 mt-1">
                    <i class="fa-solid fa-compact-disc text-retro-magenta"></i>
                    <span>Manage CHDs</span>
                </h1>
            </div>
            
            <div class="flex space-x-4 items-start">
                <button onclick="document.getElementById('add-chd-modal').classList.remove('hidden')" class="px-4 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-2 font-bold mt-1">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add CHD</span>
                </button>
                <a href="{{ route('admin.chds.csv-template') }}" class="px-4 py-2 bg-retro-card hover:bg-opacity-80 text-retro-cyan border border-retro-cyan font-tech uppercase tracking-wider rounded-lg transition flex items-center space-x-2 text-sm shadow-[0_0_10px_rgba(0,255,255,0.2)] mt-1">
                    <i class="fa-solid fa-download"></i>
                    <span>Template</span>
                </a>
                <form action="{{ route('admin.chds.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-2 bg-retro-card p-3 rounded-xl border border-retro-border" data-loading-message="Importing CHDs...">
                    @csrf
                    <div class="flex items-center space-x-3">
                        <div class="flex flex-col">
                            <label class="text-xs font-tech text-gray-400 mb-1">Import CSV</label>
                            <input type="file" name="csv_file" accept=".csv" required class="text-xs text-gray-300 font-tech file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:bg-retro-purple file:text-white hover:file:bg-opacity-80 transition cursor-pointer">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-retro-magenta hover:bg-opacity-85 text-white font-tech uppercase tracking-wider rounded-lg transition shadow-[0_0_15px_rgba(255,0,160,0.4)]">
                            <i class="fa-solid fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <form action="{{ route('admin.chds.index') }}" method="GET" class="w-full sm:w-1/2 relative flex items-center">
                <!-- Keep existing sort if any -->
                @if(request()->has('sort_by'))
                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                @endif
                
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search ROM..." 
                       class="w-full pl-10 pr-4 py-2 bg-retro-bg rounded-l-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm placeholder-gray-500 font-sans transition">
                <button type="submit" class="px-4 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech font-bold uppercase tracking-wider rounded-r-lg border border-retro-cyan transition">
                    Search
                </button>
            </form>
            
            <div class="flex items-center space-x-2">
                @if(request()->has('search') || request()->has('sort_by'))
                    <a href="{{ route('admin.chds.index') }}" class="px-4 py-2 bg-retro-card hover:bg-opacity-80 text-gray-400 hover:text-white font-tech text-xs uppercase tracking-wider rounded-lg border border-retro-border transition flex items-center space-x-2">
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
            <div class="glass-card rounded-2xl border border-retro-border border-opacity-50 overflow-hidden shadow-2xl p-8 w-full max-w-md relative">
                <button type="button" onclick="document.getElementById('filter-modal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <h2 class="font-arcade text-xl font-bold text-white mb-6 border-b border-retro-border pb-4 flex items-center space-x-2">
                    <i class="fa-solid fa-filter text-retro-purple"></i>
                    <span>Advanced Filters</span>
                </h2>
                
                <div class="text-gray-400 font-tech text-sm mb-6 text-center italic">
                    Filter options will be added here in future updates.
                </div>
                
                <div class="flex justify-end pt-4 border-t border-retro-border">
                    <button type="button" onclick="document.getElementById('filter-modal').classList.add('hidden')" class="px-6 py-2 bg-retro-card hover:bg-opacity-80 text-white font-tech uppercase tracking-wider rounded-lg border border-retro-border transition">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Dynamic Game Table -->
        <main class="glass-card rounded-xl border border-retro-border border-opacity-60 shadow-2xl overflow-hidden mb-6" x-data="chdEditor()">
            <!-- Bulk Actions Header -->
            <div x-show="selectedChds.length > 0" x-cloak class="bg-retro-magenta bg-opacity-20 border-b border-retro-magenta px-6 py-3 flex justify-between items-center" style="display: none;">
                <span class="text-white font-tech text-sm"><span x-text="selectedChds.length" class="font-bold text-retro-cyan"></span> CHDs selected</span>
                <button type="button" @click="bulkDelete()" class="px-4 py-2 bg-retro-red hover:bg-opacity-80 text-white font-tech uppercase tracking-wider rounded-lg border border-retro-red transition flex items-center space-x-2 text-xs shadow-md">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete Selected</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        @php
                            $currentSort = request('sort_by', 'rom');
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
                            <th class="py-4 px-4 w-10 text-center">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll" class="rounded border-retro-border bg-retro-bg text-retro-magenta focus:ring-retro-magenta cursor-pointer w-4 h-4">
                            </th>
                            <th class="py-4 px-6 hover:text-white transition cursor-pointer">
                                <a href="{{ $buildSortUrl('rom') }}" class="flex items-center">ROM {!! $sortIcon('rom') !!}</a>
                            </th>
                            <th class="py-4 px-4 text-center hover:text-white transition cursor-pointer">
                                <a href="{{ $buildSortUrl('size_bytes') }}" class="flex items-center justify-center">Size (MB) {!! $sortIcon('size_bytes') !!}</a>
                            </th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-retro-border divide-opacity-30 text-sm">
                        @forelse($chds as $chd)
                            <tr class="hover:bg-retro-card hover:bg-opacity-40 transition group" :class="{'bg-retro-card bg-opacity-30': selectedChds.includes({{ $chd->id }})}">
                                <td class="py-4 px-4 text-center">
                                    <input type="checkbox" :value="{{ $chd->id }}" x-model="selectedChds" class="chd-checkbox rounded border-retro-border bg-retro-bg text-retro-magenta focus:ring-retro-magenta cursor-pointer w-4 h-4">
                                </td>
                                <td class="py-4 px-6 font-sans font-bold text-white">
                                    <span class="group-hover:text-retro-cyan transition-colors">{{ $chd->rom }}</span>
                                </td>
                                <td class="py-4 px-4 text-center font-tech text-gray-400">
                                    {{ formatSizeFromBytes($chd->size_bytes) }}
                                </td>
                                <td class="py-4 px-6 text-right space-x-2 flex items-center justify-end">
                                    <button @click="openEditModal({{ json_encode($chd) }})" 
                                            class="text-retro-cyan hover:text-white transition opacity-70 hover:opacity-100 p-1 mr-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.chds.destroy', $chd->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this CHD?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-retro-red hover:text-white transition opacity-70 hover:opacity-100 p-1">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-gray-500">
                                    <i class="fa-solid fa-compact-disc text-4xl mb-3 opacity-30"></i>
                                    <p class="font-tech text-lg">No CHDs found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($chds->hasPages())
                <div class="p-4 border-t border-retro-border border-opacity-50 bg-retro-card">
                    {{ $chds->links() }}
                </div>
            @endif

            <!-- AlpineJS Edit Modal -->
            <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-retro-card border border-retro-border rounded-2xl shadow-[0_0_40px_rgba(0,243,255,0.15)] w-full max-w-xl overflow-hidden" @click.away="isEditModalOpen = false">
                    <div class="bg-retro-bg px-6 py-4 border-b border-retro-border flex justify-between items-center">
                        <div>
                            <span class="text-xs font-tech text-retro-magenta uppercase tracking-widest">Edit CHD</span>
                            <h3 class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider" x-text="editData.rom"></h3>
                        </div>
                        <button type="button" @click="isEditModalOpen = false" class="text-gray-400 hover:text-white transition">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">ROM Name</label>
                                <input type="text" x-model="editData.rom" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Size (Bytes)</label>
                                <input type="number" step="1" x-model="editData.size_bytes" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta focus:outline-none text-white text-sm font-tech">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-white text-white font-tech text-sm uppercase tracking-wider rounded-lg transition">
                                Cancel
                            </button>
                            <button type="button" @click="saveEdit()" class="px-6 py-2 bg-retro-magenta hover:bg-opacity-85 text-white font-tech text-sm uppercase tracking-wider rounded-lg transition shadow-[0_0_15px_rgba(255,0,160,0.4)] flex items-center space-x-2">
                                <i class="fa-solid fa-floppy-disk" x-show="!isSaving"></i>
                                <i class="fa-solid fa-circle-notch fa-spin" x-show="isSaving"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Add CHD Modal -->
        <div id="add-chd-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
            <div class="bg-retro-card border border-retro-border rounded-2xl shadow-[0_0_40px_rgba(0,243,255,0.15)] w-full max-w-xl overflow-hidden">
                <div class="bg-retro-bg px-6 py-4 border-b border-retro-border flex justify-between items-center">
                    <div>
                        <span class="text-xs font-tech text-retro-cyan uppercase tracking-widest">New Entry</span>
                        <h3 class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider">Add CHD</h3>
                    </div>
                    <button type="button" onclick="document.getElementById('add-chd-modal').classList.add('hidden')" class="text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('admin.chds.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">ROM Name</label>
                            <input type="text" name="rom" required class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Size (Bytes)</label>
                            <input type="number" step="1" name="size_bytes" class="w-full px-3 py-2 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-cyan focus:outline-none text-white text-sm font-tech">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('add-chd-modal').classList.add('hidden')" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-white text-white font-tech text-sm uppercase tracking-wider rounded-lg transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-retro-cyan hover:bg-opacity-85 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition font-bold shadow-[0_0_15px_rgba(0,243,255,0.4)] flex items-center space-x-2">
                            <i class="fa-solid fa-plus"></i>
                            <span>Create CHD</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('chdEditor', () => ({
                    isEditModalOpen: false,
                    isSaving: false,
                    editData: {
                        id: null,
                        rom: '',
                        size_bytes: ''
                    },
                    selectedChds: [],
                    selectAll: false,

                    toggleAll() {
                        if (this.selectAll) {
                            const checkboxes = document.querySelectorAll('.chd-checkbox');
                            this.selectedChds = Array.from(checkboxes).map(cb => parseInt(cb.value));
                        } else {
                            this.selectedChds = [];
                        }
                    },

                    async bulkDelete() {
                        if (!confirm(`Are you sure you want to delete ${this.selectedChds.length} CHDs? This cannot be undone.`)) return;
                        
                        window.showRetroLoading('Deleting CHDs...');
                        try {
                            const response = await fetch(`{{ route('admin.chds.bulk-destroy') }}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids: this.selectedChds })
                            });
                            
                            if (response.ok) {
                                window.location.reload();
                            } else {
                                alert('Failed to delete selected CHDs.');
                            }
                        } catch (e) {
                            console.error(e);
                            alert('An error occurred while deleting.');
                        } finally {
                            window.hideRetroLoading();
                        }
                    },

                    openEditModal(chd) {
                        this.editData = { ...chd };
                        this.isEditModalOpen = true;
                    },

                    async saveEdit() {
                        this.isSaving = true;
                        try {
                            const response = await fetch(`/admin/chds/${this.editData.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    rom: this.editData.rom,
                                    size_bytes: this.editData.size_bytes
                                })
                            });
                            
                            const result = await response.json();
                            if (result.success) {
                                window.location.reload();
                            } else {
                                alert('Failed to save changes.');
                            }
                        } catch (e) {
                            console.error(e);
                            alert('An error occurred while saving.');
                        } finally {
                            this.isSaving = false;
                        }
                    }
                }));
            });
        </script>
    </div>
    
    @include('components.loading-modal')
</body>
</html>
