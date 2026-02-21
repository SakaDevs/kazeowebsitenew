@php
    // Memanggil 5 script dengan jumlah views tertinggi secara langsung
    $popularScripts = \App\Models\Script::orderByDesc('views')->take(5)->get();
@endphp

<footer class="bg-white border-t border-zinc-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            
            <div class="space-y-4">
                <div class="flex items-center gap-3 shrink-0">
                    <img src="https://ui-avatars.com/api/?name=Kazeo&background=18181b&color=fff&rounded=true" alt="Kazeo Avatar" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                    <span class="text-xl tracking-tight flex items-center">
                        <span class="font-black text-zinc-900">Kazeo</span><span class="font-light text-zinc-500 ml-1">Official</span>
                    </span>
                </div>
                <p class="text-sm text-zinc-500 font-medium leading-relaxed max-w-xs">
                    Komunitas MLBB Terbaik Sepanjang Masa!
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="https://youtube.com/@KazeoOfficialReallz" target="_blank" class="text-zinc-400 hover:text-red-600 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="{{ route('social') }}" class="text-zinc-400 hover:text-blue-500 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a5.96 5.96 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-zinc-900 tracking-tight">Quick Link</h3>
                <ul class="space-y-3 text-sm font-medium text-zinc-500">
                    <li><a href="/" class="hover:text-zinc-900 hover:translate-x-1 inline-block transition-all duration-300">Home</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-zinc-900 hover:translate-x-1 inline-block transition-all duration-300">Category</a></li>
                    <li><a href="{{ route('community.index') }}" class="hover:text-zinc-900 hover:translate-x-1 inline-block transition-all duration-300">Community</a></li>
                    <li><a href="{{ route('social') }}" class="hover:text-zinc-900 hover:translate-x-1 inline-block transition-all duration-300">Social</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-zinc-900 tracking-tight">Popular</h3>
                <ul class="space-y-3 text-sm font-medium text-zinc-500">
                    @forelse($popularScripts as $script)
                        <li>
                            <a href="{{ route('script.show', $script->slug) }}" class="hover:text-zinc-900 hover:translate-x-1 transition-all duration-300 truncate block w-full" title="{{ $script->title }}">
                                {{ $script->title }}
                            </a>
                        </li>
                    @empty
                        <li><span class="italic text-zinc-400">Belum ada script.</span></li>
                    @endforelse
                </ul>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-zinc-900 tracking-tight">Support</h3>
                <ul class="space-y-4 text-sm font-medium text-zinc-500">
                    <li>
                        <a href="https://saweria.co/kazeoofficial" class="flex items-center gap-2 hover:text-zinc-900 transition-colors duration-300">
                            <span class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-500">🦊</span>
                            <span>Saweria</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://tako.id/kazeoofficial" class="flex items-center gap-2 hover:text-zinc-900 transition-colors duration-300">
                            <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">👻</span>
                            <span>Tako ID</span>
                        </a>
                    </li>
                </ul>
                <p class="text-xs text-zinc-400 pt-2 italic">
                    Kalo ada lebihan, boleh lah.
                </p>
            </div>

        </div>
        
        <div class="mt-16 pt-8 border-t border-zinc-100">
            <p class="text-sm text-zinc-400 font-medium text-center">
                &copy; {{ date('Y') }} Kazeo Official. Moonton All rights reserved.
            </p>
        </div>
    </div>
</footer>