<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Smart School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A5F',
                        secondary: '#2E86AB',
                        accent: '#F4A261',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 10px;
            color: #bfdbfe;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        .sidebar-section {
            padding: 16px 14px 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #60a5fa;
            text-transform: uppercase;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
<div class="flex h-screen overflow-hidden">

    <!-- ===== SIDEBAR ===== -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-0 -translate-x-full lg:w-16 lg:translate-x-0'"
           class="bg-primary transition-all duration-300 overflow-y-auto overflow-x-hidden flex-shrink-0 flex flex-col z-50 scrollbar-hide">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10 flex-shrink-0">
            <div class="w-9 h-9 bg-accent rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                <i class="fa-solid fa-graduation-cap text-white text-sm"></i>
            </div>
            <div x-show="sidebarOpen" x-transition>
                <p class="text-white font-bold text-base leading-tight">Smart School</p>
                <p class="text-blue-300 text-xs">Sistem Informasi</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-3 space-y-0.5">
            @yield('sidebar')
        </nav>

        <!-- User Footer -->
        <div class="p-2 border-t border-white/10 flex-shrink-0">
            <div class="flex items-center gap-2.5 px-2 py-2 rounded-xl">
                <div class="w-8 h-8 rounded-lg bg-secondary flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div x-show="sidebarOpen" class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-blue-300 text-xs capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-red-300 hover:bg-red-900/30 hover:text-red-100 rounded-xl transition text-xs font-medium mt-1">
                    <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- ===== MAIN ===== -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Topbar -->
        <header class="bg-white border-b border-gray-100 px-5 py-3.5 flex items-center justify-between flex-shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-500">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                <div>
                    <h1 class="text-base font-bold text-gray-800 leading-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400">@yield('subtitle', '')</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Notifikasi -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="relative p-2 rounded-lg hover:bg-gray-100 transition text-gray-500">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if(auth()->user()->notifikasiTidakDibaca()->count() > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="font-semibold text-gray-800 text-sm">Notifikasi</h3>
                            <span class="text-xs bg-secondary text-white px-2 py-0.5 rounded-full">{{ auth()->user()->notifikasiTidakDibaca()->count() }} baru</span>
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @forelse(auth()->user()->notifikasi()->latest()->take(6)->get() as $notif)
                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50 {{ !$notif->dibaca ? 'bg-blue-50' : '' }}">
                                    <div class="flex items-start gap-2">
                                        <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0
                                            {{ $notif->tipe === 'success' ? 'bg-green-400' : ($notif->tipe === 'warning' ? 'bg-yellow-400' : ($notif->tipe === 'danger' ? 'bg-red-400' : 'bg-blue-400')) }}">
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-800">{{ $notif->judul }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-gray-400 text-sm">
                                    <i class="fa-regular fa-bell text-2xl mb-2 block"></i>
                                    Tidak ada notifikasi
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Avatar with Dropdown -->
                <div x-data="{ openProfile: false }" class="relative pl-2 border-l border-gray-100">
                    <button @click="openProfile = !openProfile" class="flex items-center gap-2 hover:bg-gray-50 rounded-xl px-1.5 py-1 transition">
                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-gray-700 leading-tight">{{ Str::limit(auth()->user()->name, 20) }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400 text-xs hidden sm:block"></i>
                    </button>
                    <div x-show="openProfile" @click.away="openProfile = false" x-cloak
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-3 hover:bg-gray-50 transition text-sm text-gray-700">
                            <i class="fa-solid fa-user-gear text-gray-400 w-4"></i> Profil Saya
                        </a>
                        <a href="{{ route('landing') }}" target="_blank" class="flex items-center gap-2.5 px-4 py-3 hover:bg-gray-50 transition text-sm text-gray-700 border-t border-gray-50">
                            <i class="fa-solid fa-globe text-gray-400 w-4"></i> Lihat Website
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-50">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-3 hover:bg-red-50 transition text-sm text-red-600">
                                <i class="fa-solid fa-right-from-bracket w-4"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-5 pt-4">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition
                     class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 mb-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 mb-3 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto px-5 py-4 scrollbar-hide">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
