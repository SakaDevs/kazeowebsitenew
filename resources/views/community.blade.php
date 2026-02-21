<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Custom Scrollbar untuk kolom komentar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d4d4d8; }
    </style>
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 mb-16">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-zinc-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50 rounded-full blur-3xl opacity-50"></div>
            
            <div class="relative z-10">
                <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight flex items-center gap-2">
                    <span>💬</span> Ruang Komunitas
                </h1>
                <p class="mt-2 text-sm text-zinc-500 font-medium">Tempat nongkrong, diskusi, request mod, dan tanya jawab bareng member lain.</p>
            </div>
            
            <a href="{{ route('community.create') }}" class="relative z-10 inline-flex justify-center items-center px-6 py-3.5 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors shadow-sm active:scale-95 shrink-0 w-full sm:w-auto">
                + Buat Postingan
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <div class="lg:col-span-2 space-y-4">
                
                <div class="flex items-center gap-6 border-b border-zinc-200 pb-px mb-6 overflow-x-auto hide-scroll">
                    @php
                        $currentCategory = request('category', 'Terbaru');
                        $tabs = [
                            'Terbaru' => '🌟 Terbaru',
                            'Tanya Jawab' => '💡 Tanya Jawab',
                            'Request Script' => '📝 Request',
                            'Lapor Bug' => '🐛 Bug',
                            'Tutorial' => '📚 Tutorial',
                            'Info & Update' => '📢 Info Admin'
                        ];
                    @endphp

                    @foreach($tabs as $value => $label)
                        <a href="{{ route('community.index', ['category' => $value]) }}" 
                           class="pb-3 border-b-2 text-sm whitespace-nowrap transition-colors 
                           {{ $currentCategory === $value 
                                ? 'border-zinc-900 font-bold text-zinc-900' 
                                : 'border-transparent font-medium text-zinc-500 hover:text-zinc-700 hover:border-zinc-300' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif

                @forelse($posts as $post)
                    <div x-data="{ showComments: false }" class="bg-white p-5 sm:p-6 rounded-2xl border {{ $post->is_pinned ? 'border-red-300 shadow-md bg-red-50/20' : 'border-zinc-200 shadow-sm' }} hover:shadow-md transition-all relative">
                        
                        @if($post->is_pinned)
                            <div class="absolute -top-3 -right-3 w-8 h-8 bg-red-600 rounded-full flex items-center justify-center shadow-md border-2 border-white z-10" title="Postingan Disematkan">
                                <span class="text-white text-sm">📌</span>
                            </div>
                        @endif

                        <div class="flex items-start gap-3 sm:gap-4">
                            @php
                                $isAdmin = in_array($post->user->role ?? '', ['admin', 'super_admin', 'superadmin']);
                                $bgAvatar = $isAdmin ? '18181b' : 'f4f4f5';
                                $colorAvatar = $isAdmin ? 'fff' : '18181b';
                                
                                // LOGIKA AVATAR POSTINGAN
                                $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'User').'&background='.$bgAvatar.'&color='.$colorAvatar;
                                $postAvatar = ($post->user && $post->user->avatar) ? asset('storage/' . $post->user->avatar) : $defaultAvatar;
                            @endphp
                            <img src="{{ $postAvatar }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full shrink-0 border border-zinc-200 object-cover">
                            
                            <div class="flex-grow w-full">
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-1.5">
                                    <h4 class="font-bold text-zinc-900 text-sm flex items-center gap-1">
                                        {{ $post->user->name ?? 'Unknown User' }}
                                        @if($isAdmin)
                                            <span title="Admin" class="text-blue-500 text-xs">☑️</span>
                                        @endif
                                    </h4>
                                    <span class="text-[11px] text-zinc-400 font-medium" title="{{ $post->created_at->format('d M Y, H:i') }}">
                                        • {{ $post->created_at->diffForHumans() }}
                                    </span>
                                    
                                    @php
                                        $badgeClass = 'bg-zinc-100 text-zinc-700 border-zinc-200';
                                        if($post->category == 'Info & Update') $badgeClass = 'bg-red-50 text-red-700 border-red-100';
                                        elseif($post->category == 'Lapor Bug') $badgeClass = 'bg-orange-50 text-orange-700 border-orange-100';
                                        elseif($post->category == 'Request Script') $badgeClass = 'bg-blue-50 text-blue-700 border-blue-100';
                                    @endphp
                                    <span class="ml-auto {{ $badgeClass }} border text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">
                                        {{ $post->category }}
                                    </span>
                                </div>
                                
                                <h3 class="text-base sm:text-lg font-bold text-zinc-900 mb-2">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-zinc-600 text-sm line-clamp-4 mb-4 leading-relaxed">
                                    {!! nl2br(e($post->content)) !!}
                                </p>
                                
                                <div class="flex items-center gap-4 sm:gap-6 text-zinc-500 text-sm font-medium pt-3 border-t border-zinc-100">
                                    
                                    @php 
                                        $isLiked = auth()->check() ? $post->reactions->where('user_id', auth()->id())->isNotEmpty() : false; 
                                    @endphp
                                    <form action="{{ route('community.like', $post->id) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-1.5 {{ $isLiked ? 'text-red-600' : 'hover:text-red-600' }} transition-colors">
                                            <span class="text-lg leading-none">{{ $isLiked ? '❤️' : '🤍' }}</span>
                                            <span>{{ $post->reactions->count() }} Suka</span>
                                        </button>
                                    </form>

                                    <button type="button" @click="showComments = !showComments" class="flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                                        <span class="text-lg leading-none">💬</span>
                                        <span>{{ $post->comments->count() }} Komentar</span>
                                    </button>
                                    
                                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin', 'superadmin']))
                                        <div class="flex items-center gap-4 ml-auto">
                                            <form action="{{ route('community.destroy', $post->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Yakin ingin menghapus postingan ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center gap-1.5 text-zinc-500 hover:text-red-600 transition-colors" title="Hapus Postingan">
                                                    <span class="text-lg leading-none">🗑️</span>
                                                    <span class="hidden sm:inline font-bold">Hapus</span>
                                                </button>
                                            </form>

                                            <form action="{{ route('community.pin', $post->id) }}" method="POST" class="m-0 p-0">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-1.5 {{ $post->is_pinned ? 'text-red-600 hover:text-zinc-500' : 'text-zinc-500 hover:text-red-600' }} transition-colors" title="{{ $post->is_pinned ? 'Lepas Sematan' : 'Sematkan Postingan' }}">
                                                    <span class="text-lg leading-none">📌</span>
                                                    <span class="hidden sm:inline font-bold">{{ $post->is_pinned ? 'Unpin' : 'Pin' }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                </div>

                                <div x-show="showComments" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="mt-4 pt-4 border-t border-zinc-100" style="display: none;">
                                    
                                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                        @forelse($post->comments as $comment)
                                            <div class="flex gap-2.5">
                                                @php
                                                    // LOGIKA AVATAR KOMENTAR
                                                    $commentAvatarDefault = 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name ?? 'User').'&background=f4f4f5&color=18181b';
                                                    $commentAvatar = ($comment->user && $comment->user->avatar) ? asset('storage/' . $comment->user->avatar) : $commentAvatarDefault;
                                                @endphp
                                                <img src="{{ $commentAvatar }}" class="w-8 h-8 rounded-full border border-zinc-200 shrink-0 object-cover">
                                                
                                                <div class="bg-zinc-50 p-3 rounded-xl rounded-tl-none border border-zinc-100 flex-grow">
                                                    <div class="flex items-baseline justify-between mb-1">
                                                        <h5 class="text-xs font-bold text-zinc-900">{{ $comment->user->name ?? 'Unknown' }}</h5>
                                                        <span class="text-[10px] text-zinc-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-zinc-700 leading-relaxed">{{ $comment->body }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-zinc-400 text-center py-2">Belum ada komentar. Jadilah yang pertama!</p>
                                        @endforelse
                                    </div>

                                    @auth
                                        <form action="{{ route('community.comment', $post->id) }}" method="POST" class="flex gap-2 relative">
                                            @csrf
                                            <input type="text" name="content" required placeholder="Tulis komentar..." 
                                                class="w-full text-xs pl-3 pr-16 py-2.5 bg-white border border-zinc-200 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 rounded-xl transition-all">
                                            <button type="submit" class="absolute right-1 top-1 bottom-1 px-4 bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-bold rounded-lg transition-colors">
                                                Kirim
                                            </button>
                                        </form>
                                    @else
                                        <div class="text-center py-3 bg-zinc-50 rounded-xl border border-zinc-200">
                                            <p class="text-xs text-zinc-500 font-medium">Silakan <a href="{{ route('login') }}" class="text-zinc-900 font-bold hover:underline">Login</a> untuk berkomentar.</p>
                                        </div>
                                    @endauth
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-2xl border border-zinc-200 shadow-sm">
                        <span class="text-5xl mb-4 block">👻</span>
                        <h3 class="text-lg font-bold text-zinc-900 mb-1">Masih Sepi Nih!</h3>
                        <p class="text-zinc-500 font-medium text-sm mb-4">Belum ada postingan di komunitas. Jadilah yang pertama!</p>
                        <a href="{{ route('community.create') }}" class="inline-flex justify-center items-center px-6 py-2.5 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors shadow-sm">
                            Buat Postingan
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="space-y-6">
                <div class="bg-zinc-900 text-white p-6 sm:p-8 rounded-2xl shadow-sm relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <h3 class="font-black text-lg mb-4 flex items-center gap-2">
                        <span>📜</span> Aturan Komunitas
                    </h3>
                    <ul class="space-y-3.5 text-sm text-zinc-300 font-medium">
                        <li class="flex items-start gap-2.5"><span class="text-red-500 font-bold mt-0.5">1.</span> Sopan dan saling menghargai.</li>
                        <li class="flex items-start gap-2.5"><span class="text-red-500 font-bold mt-0.5">2.</span> Dilarang share link phising/jualan.</li>
                        <li class="flex items-start gap-2.5"><span class="text-red-500 font-bold mt-0.5">3.</span> Dilarang spam atau toxic.</li>
                        <li class="flex items-start gap-2.5"><span class="text-red-500 font-bold mt-0.5">4.</span> Laporkan jika ada link error.</li>
                    </ul>
                </div>
            </div>

        </div>
    </main>
    
    <x-footer/>
</body>
</html>