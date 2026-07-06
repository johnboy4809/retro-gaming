<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Drives - Order Manager Backend</title>
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
                        sans: ['Orbitron', 'sans-serif'],
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
        <header class="flex flex-col md:flex-row justify-between items-center mb-8 pb-6 border-b border-retro-border border-opacity-40">
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <div class="p-3 bg-retro-card rounded-lg border border-retro-magenta">
                    <i class="fa-solid fa-truck-ramp-box text-2xl text-retro-magenta"></i>
                </div>
                <div>
                    <h1 class="font-arcade text-3xl font-extrabold uppercase tracking-wider text-retro-cyan">
                        Retro Drives
                    </h1>
                    <p class="font-tech text-xs text-retro-cyan tracking-widest uppercase">Order Processing Backend</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Valet Indicator -->
                <div class="flex items-center space-x-2 bg-retro-card px-4 py-2 rounded-full border border-retro-border border-opacity-60 text-xs font-tech">
                    <span class="h-2 w-2 rounded-full bg-retro-green animate-ping"></span>
                    <span class="text-retro-green uppercase">Valet Active</span>
                    <span class="text-gray-500">|</span>
                    <span class="text-gray-400">retro-gaming.test</span>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-retro-card border border-retro-border hover:border-retro-magenta text-gray-400 hover:text-white text-xs font-tech uppercase tracking-wider rounded-full transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Exit Portal</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Top Level Device Groups -->
        <div class="flex items-center space-x-2 mb-6 border-b border-retro-border border-opacity-20 pb-4">
            <a href="{{ url('/admin?group=arcade&system=mame') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-gamepad mr-1"></i> Arcade
            </a>
            <a href="{{ route('admin.console') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-tv mr-1"></i> Console
            </a>
            <a href="{{ url('/admin?group=home_computer') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-card text-gray-400 hover:text-white border border-retro-border">
                <i class="fa-solid fa-computer mr-1"></i> Home Computer
            </a>
            <a href="{{ route('admin.orders') }}" class="px-5 py-2.5 rounded-lg font-tech text-xs uppercase tracking-wider transition-all bg-retro-cyan text-black">
                <i class="fa-solid fa-truck-ramp-box mr-1"></i> Customer Orders
            </a>
        </div>

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

        <!-- Orders Table -->
        <main class="glass-card rounded-xl border border-retro-border border-opacity-60 shadow-2xl overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-retro-border bg-retro-card bg-opacity-70 text-xs font-tech text-retro-cyan uppercase tracking-wider">
                            <th class="py-4 px-6">Order ID</th>
                            <th class="py-4 px-4">Recipient</th>
                            <th class="py-4 px-4">Media Format</th>
                            <th class="py-4 px-4 text-center">ROMs Selected</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4">Date Placed</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-retro-border divide-opacity-30 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-retro-card hover:bg-opacity-40 transition group">
                                <td class="py-4 px-6 font-tech font-bold text-white tracking-wide">
                                    #{{ sprintf('%05d', $order->id) }}
                                </td>
                                <td class="py-4 px-4 font-sans text-gray-200">
                                    <div class="font-semibold">{{ $order->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-tech">{{ $order->user?->email ?? 'Guest Account' }}</div>
                                </td>
                                <td class="py-4 px-4 font-tech text-gray-300">
                                    {{ $order->drive_type }}
                                </td>
                                <td class="py-4 px-4 text-center font-tech text-retro-cyan font-bold">
                                    {{ $order->items->count() }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($order->status === 'Pending')
                                        <span class="px-2 py-0.5 rounded bg-retro-yellow bg-opacity-10 border border-retro-yellow border-opacity-45 text-[10px] text-retro-yellow font-tech uppercase">Pending</span>
                                    @elseif($order->status === 'Processing')
                                        <span class="px-2 py-0.5 rounded bg-retro-purple bg-opacity-10 border border-retro-purple border-opacity-45 text-[10px] text-retro-purple font-tech uppercase">Processing</span>
                                    @elseif($order->status === 'Shipped')
                                        <span class="px-2 py-0.5 rounded bg-retro-green bg-opacity-10 border border-retro-green border-opacity-45 text-[10px] text-retro-green font-tech uppercase">Shipped</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-retro-red bg-opacity-10 border border-retro-red border-opacity-45 text-[10px] text-retro-red font-tech uppercase">Cancelled</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-gray-400 font-tech text-xs">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <button onclick="showOrderDetails({{ json_encode($order) }})" 
                                            class="px-2 py-1 bg-retro-card border border-retro-border hover:border-retro-cyan text-xs font-tech uppercase tracking-wider text-gray-300 hover:text-retro-cyan rounded transition">
                                        Inspect
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500 font-tech">
                                    <i class="fa-solid fa-inbox text-3xl mb-3 block text-retro-border"></i>
                                    No customer orders placed yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 bg-retro-card bg-opacity-40 border-t border-retro-border flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-xs text-gray-400 font-tech">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} Orders
                    </div>
                    <div class="flex items-center space-x-1">
                        @if ($orders->onFirstPage())
                            <span class="px-3 py-1.5 bg-retro-bg border border-retro-border border-opacity-40 text-gray-600 rounded text-xs font-tech uppercase tracking-wider cursor-not-allowed">Prev</span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded text-xs font-tech uppercase tracking-wider transition">Prev</a>
                        @endif

                        <span class="px-4 py-1.5 bg-retro-bg border border-retro-border text-retro-cyan text-xs font-tech rounded">
                            Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                        </span>

                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 bg-retro-card border border-retro-border hover:border-retro-cyan text-white hover:text-retro-cyan rounded text-xs font-tech uppercase tracking-wider transition">Next</a>
                        @else
                            <span class="px-3 py-1.5 bg-retro-bg border border-retro-border border-opacity-40 text-gray-600 rounded text-xs font-tech uppercase tracking-wider cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </main>
        
        <!-- Footer -->
        <footer class="text-center py-6 text-gray-600 text-xs font-tech border-t border-retro-border border-opacity-20 mt-12">
            Retro Drives &copy; {{ date('Y') }} • Games you want to play
        </footer>
    </div>

    <!-- Order Inspector Modal -->
    <div id="order-modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="glass-card max-w-2xl w-full rounded-2xl border border-retro-cyan overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-retro-card border-b border-retro-border flex justify-between items-center">
                <div>
                    <span class="text-xs font-tech text-retro-cyan uppercase tracking-widest">Order Inspector</span>
                    <h3 id="inspect-order-title" class="font-arcade text-xl font-extrabold text-white uppercase tracking-wider"></h3>
                </div>
                <button onclick="closeOrderModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                
                <!-- Shipping & Media Grid -->
                <div class="grid grid-cols-2 gap-6 pb-4 border-b border-retro-border border-opacity-40">
                    <div>
                        <h4 class="text-xs font-tech text-retro-magenta uppercase tracking-wider mb-1.5">Selected Media format</h4>
                        <p id="inspect-order-media" class="text-white text-sm font-semibold"></p>
                    </div>
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Placed By</h4>
                        <p id="inspect-order-user" class="text-white text-sm font-semibold"></p>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1.5">Shipping Address</h4>
                    <div class="bg-retro-bg p-4 rounded-lg border border-retro-border font-sans text-gray-200 text-sm leading-relaxed space-y-0.5">
                        <p id="inspect-order-name" class="font-bold text-white"></p>
                        <p id="inspect-order-address"></p>
                        <p id="inspect-order-location"></p>
                    </div>
                </div>

                <!-- Selected ROMs List -->
                <div>
                    <h4 class="text-xs font-tech text-retro-cyan uppercase tracking-wider mb-3">Selected Cabinet ROMs</h4>
                    <div id="inspect-order-roms" class="max-h-[25vh] overflow-y-auto space-y-2 pr-2">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Update Status Form -->
                <div class="border-t border-retro-border border-opacity-40 pt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h4 class="text-xs font-tech text-gray-400 uppercase tracking-wider mb-1">Process Order status</h4>
                        <p class="text-[10px] text-gray-500">Update status to notify customer.</p>
                    </div>
                    
                    <form id="status-form" action="" method="POST" class="flex items-center space-x-2 w-full sm:w-auto">
                        @csrf
                        <select name="status" id="inspect-order-status" required class="px-3 py-1.5 bg-retro-bg rounded-lg border border-retro-border focus:border-retro-magenta text-white text-xs">
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit" class="px-4 py-1.5 bg-retro-cyan hover:bg-opacity-85 text-black font-tech text-xs uppercase tracking-wider rounded-lg transition">
                            Update
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-retro-card border-t border-retro-border flex justify-end">
                <button onclick="closeOrderModal()" class="px-5 py-2 bg-retro-cyan hover:bg-opacity-80 text-black font-tech text-sm uppercase tracking-wider rounded-lg transition">
                    Close Inspector
                </button>
            </div>
        </div>
    </div>

    <!-- Inspector Scripts -->
    <script>
        const modal = document.getElementById('order-modal');
        const statusForm = document.getElementById('status-form');

        function showOrderDetails(order) {
            statusForm.action = `/admin/orders/${order.id}/status`;
            
            document.getElementById('inspect-order-title').innerText = `Order #${String(order.id).padStart(5, '0')}`;
            document.getElementById('inspect-order-media').innerText = order.drive_type;
            document.getElementById('inspect-order-user').innerText = order.user ? `${order.user.name} (${order.user.email})` : 'Guest Account';
            
            // Shipping details
            document.getElementById('inspect-order-name').innerText = order.name;
            document.getElementById('inspect-order-address').innerText = order.address;
            document.getElementById('inspect-order-location').innerText = `${order.city}, ${order.postal_code} - ${order.country}`;
            
            // Current Status
            document.getElementById('inspect-order-status').value = order.status;

            // Load ROMs list
            const romsContainer = document.getElementById('inspect-order-roms');
            romsContainer.innerHTML = '';

            if (order.items && order.items.length > 0) {
                order.items.forEach(item => {
                    const row = document.createElement('div');
                    row.className = "flex justify-between items-center p-2.5 bg-retro-bg rounded-lg border border-retro-border border-opacity-40 text-xs font-tech text-gray-300";
                    row.innerHTML = `
                        <span class="font-bold text-white uppercase">${item.mame ? item.mame.rom : 'Unknown'}</span>
                        <span class="text-gray-500 line-clamp-1 max-w-[300px] text-right font-sans">${item.mame ? item.mame.full_name : 'No ROM description'}</span>
                    `;
                    romsContainer.appendChild(row);
                });
            } else {
                romsContainer.innerHTML = `<p class="text-xs text-gray-500 font-tech text-center py-4">No ROMs selected in this order.</p>`;
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeOrderModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeOrderModal();
            }
        });

        // Close on click outside modal
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeOrderModal();
            }
        });
    </script>

</body>
</html>
