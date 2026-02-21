@php
    // --- LOGIKA BACKEND (TIDAK BERUBAH) ---
    // Melacak user aktif menggunakan Cache Laravel (berlaku 5 menit)
    $activeUsers = \Illuminate\Support\Facades\Cache::get('active_users', []);
    $sessionId = session()->getId();
    
    if($sessionId) {
        $activeUsers[$sessionId] = now()->timestamp;
    }
    
    // Hapus sesi user yang sudah tidak aktif lebih dari 5 menit (300 detik)
    foreach ($activeUsers as $id => $lastActivity) {
        if (now()->timestamp - $lastActivity > 300) {
            unset($activeUsers[$id]);
        }
    }
    
    // Simpan kembali data terbaru ke Cache
    \Illuminate\Support\Facades\Cache::put('active_users', $activeUsers, now()->addMinutes(10));
    $activeUsersCount = count($activeUsers);
@endphp

<div x-data="{ open: false }" @click.outside="open = false" class="fixed bottom-6 left-6 z-[90] font-sans">
    
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="absolute bottom-full left-0 mb-5 w-56 bg-white text-zinc-900 rounded-2xl shadow-2xl border border-zinc-200/80"
         style="display: none;"> <div class="p-5">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="relative flex h-3 w-3 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="font-bold text-sm tracking-wide">Status Real-time</span>
            </div>
            <div class="border-t border-zinc-100 pt-4 flex justify-between items-center">
                <span class="text-sm font-medium text-zinc-500">Pengunjung Aktif:</span>
                <span class="text-2xl font-black text-zinc-900">{{ $activeUsersCount }}</span>
            </div>
        </div>
        <div class="absolute -bottom-2 left-6 w-4 h-4 bg-white rotate-45 border-r border-b border-zinc-200/80"></div>
    </div>

    <button @click="open = !open" class="relative w-14 h-14 bg-zinc-900 rounded-full flex items-center justify-center shadow-lg shadow-zinc-900/20 hover:bg-zinc-800 hover:scale-105 transition-all duration-300 focus:outline-none ring-4 ring-white border border-zinc-200">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        
        <span class="absolute -top-1 -right-1 w-6 h-6 bg-white text-zinc-900 text-xs font-black rounded-full flex items-center justify-center border-2 border-zinc-100 shadow-sm">
            {{ $activeUsersCount }}
        </span>
    </button>
</div>