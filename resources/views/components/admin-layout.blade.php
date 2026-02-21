<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin - Kazeo Official</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-zinc-50 text-zinc-900 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-zinc-900/80 backdrop-blur-sm md:hidden" 
         @click="sidebarOpen = false" 
         style="display: none;"></div>

    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-900 text-white flex flex-col transition-transform duration-300 transform md:relative md:translate-x-0"
           :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        
        <div class="h-20 flex items-center justify-between px-6 border-b border-zinc-800 shrink-0">
            <span class="text-2xl font-black tracking-tight text-white">Kazeo<span class="text-zinc-400 font-light ml-1">Admin</span></span>
            
            <button @click="sidebarOpen = false" class="md:hidden p-2 -mr-2 text-zinc-400 hover:text-white focus:outline-none transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/50' }} font-medium transition-colors">
                <span>📊</span> Dashboard
            </a>
            
            <p class="px-4 pt-4 pb-2 text-xs font-bold text-zinc-500 uppercase tracking-wider">Database</p>
            
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/50' }} font-medium transition-colors">
                <span>👥</span> Users & Roles
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/50' }} font-medium transition-colors">
                <span>📂</span> Categories
            </a>
            
            <a href="{{ route('admin.templates.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.templates.*') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/50' }} font-medium transition-colors">
                <span>📋</span> Script Templates
            </a>

            <a href="{{ route('admin.scripts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-400 hover:text-white hover:bg-zinc-800/50 font-medium transition-colors">
                <span>📝</span> Scripts
            </a>
            
            <a href="{{ route('admin.communities.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-400 hover:text-white hover:bg-zinc-800/50 font-medium transition-colors">
                <span>🌐</span> Communities
            </a>
            
            <a href="/" class="flex items-center gap-3 px-4 py-3 mt-8 rounded-xl text-red-400 hover:text-white hover:bg-red-900/50 font-medium transition-colors border border-red-900/30">
                <span>⬅️</span> Back to Web
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
        
        <header class="h-20 bg-white border-b border-zinc-200 flex items-center justify-between px-4 sm:px-6 lg:px-10 shrink-0 relative z-30">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 text-zinc-600 hover:text-zinc-900 focus:outline-none rounded-lg hover:bg-zinc-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-zinc-800 truncate max-w-[150px] sm:max-w-none">{{ $title ?? 'Dashboard' }}</h2>
            </div>

            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-zinc-900">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                    <p class="text-xs font-medium text-zinc-500 uppercase">{{ Auth::user()->role ?? 'Admin' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=18181b&color=fff&rounded=true" class="w-10 h-10 rounded-xl shadow-sm">
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-50 p-4 sm:p-6 lg:p-10 relative z-0">
            {{ $slot }}
        </main>
    </div>

</body>
</html>