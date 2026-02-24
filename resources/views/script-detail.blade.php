<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $script->title }} - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
</head>
<body class="bg-zinc-50 pt-24 pb-20">
    <x-navbar/>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors">
                <span>&larr;</span> Kembali ke Beranda
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden mb-16 md:mb-24">
            
            <div class="w-full h-[300px] md:h-[450px] bg-zinc-100 relative">
                @if($script->image)
                    <img src="{{ Storage::url($script->image) }}" alt="{{ $script->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-zinc-400 font-medium">No Image Available</div>
                @endif
            </div>

            <div class="p-6 md:p-10">
                
                <div class="mb-8 border-b border-zinc-100 pb-8">
                    <p class="text-sm font-bold text-red-700 uppercase tracking-wider mb-3">
                        {{ $script->category->name ?? 'Uncategorized' }}
                    </p>
                    <h1 class="text-3xl md:text-4xl font-black text-zinc-900 leading-tight mb-4 tracking-tight">
                        {{ $script->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium text-zinc-500">
                        <div class="flex items-center gap-2">
                            Oleh <span class="text-zinc-900 font-bold">{{ $script->user->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5" title="Diperbarui pada {{ \Carbon\Carbon::parse($script->updated_at)->format('d M Y, H:i') }}">
                            <span>🕒</span> Diperbarui {{ \Carbon\Carbon::parse($script->updated_at)->diffForHumans() }}
                        </div>
                        <div class="flex items-center gap-1.5" title="Dilihat">
                            <span>👁️</span> {{ number_format($script->views) }}x dilihat
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-xl font-black text-zinc-900 mb-4 tracking-tight">
                        Short Story
                    </h2>
                    
                    <div x-data="{ expanded: false }" class="bg-zinc-50 p-6 rounded-2xl border border-zinc-100 shadow-sm relative">
                        
                        <div :class="expanded ? '' : 'line-clamp-3'" class="prose max-w-none text-zinc-600 text-sm font-medium leading-relaxed transition-all duration-300">
                            {!! nl2br(e($script->short_story)) !!}
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-zinc-200/60">
                            <button @click="expanded = !expanded" class="inline-flex items-center gap-1.5 text-zinc-900 font-bold text-sm hover:text-blue-600 transition-colors group focus:outline-none">
                                <span x-text="expanded ? 'Tutup Cerita' : 'Baca Selengkapnya'"></span>
                                
                                <svg :class="expanded ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mb-12">
                    <a href="#area-download" class="inline-flex justify-center items-center gap-2 px-8 py-3.5 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-all shadow-sm active:scale-95 w-full sm:w-auto">
                        <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        Langsung Download
                    </a>
                </div>

                <div class="mb-12">
                    <h2 class="text-2xl font-black text-zinc-900 mb-6 border-b border-zinc-100 pb-4">
                        Fitur
                    </h2>
                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 mb-8">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 mb-4">Support Voice</h3>
                            <ul class="list-disc pl-5 space-y-2.5 text-zinc-700 font-medium text-sm marker:text-zinc-400">
                                <li>Indonesia</li>
                                <li>English</li>
                                <li>English PH</li>
                                <li>Japan</li>
                                <li>Arabic</li>
                                <li>Espanol</li>
                                <li>Portuguse</li>
                                <li>Rusia</li>
                                <li>Turkie</li>
                            </ul>
                        </div>
                
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 mb-4">Kelebihan Lainnya</h3>
                            <ul class="list-disc pl-5 space-y-2.5 text-zinc-700 font-medium text-sm marker:text-zinc-400">
                                <li>Support untuk grafik low</li>
                                <li>Head Icon</li>
                                <li>Skill Icon</li>
                                <li>Share Background</li>
                                <li>File sudah dikompres</li>
                                <li>Audio Replay</li>
                                <li>Work Classic & Rank</li>
                                <li>No Bug</li>
                                <li>File dibuat sendiri</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 mb-8">
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-sm flex gap-3">
                        <span class="text-blue-500 font-bold">ℹ️ Info:</span>
                        <p>File dari unity3d dan .bnk dari sini sudah terisi WM (Watermark) dari Kazeo Official.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-red-50 border border-red-100 text-red-800 text-sm flex gap-3">
                        <span class="text-red-500 font-bold">🚨 Lapor Bug:</span>
                        <p>Jika ada kerusakan efek atau bug pada script, silakan lapor ke admin <a href="#" class="font-bold underline hover:text-red-900">disini</a>.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
                    <a href="#" class="flex justify-center items-center py-3.5 px-4 rounded-xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-800 transition-colors shadow-sm">
                        📖 Cara Pasang Script
                    </a>
                    <a href="#" class="flex justify-center items-center py-3.5 px-4 rounded-xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-800 transition-colors shadow-sm">
                        💾 Cara Backup Script
                    </a>
                    <a href="#" class="flex justify-center items-center py-3.5 px-4 rounded-xl bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition-colors shadow-sm">
                        🔓 Fix Akses Ditolak
                    </a>
                </div>

                <div id="area-download" class="scroll-mt-28">
                    <h2 class="text-xl font-bold text-zinc-900 mb-6 tracking-tight">Unduh Script</h2>
                    
                    <div class="border border-zinc-200 rounded-2xl shadow-sm bg-white overflow-hidden">
                        <div class="overflow-x-auto w-full">
                            <table class="w-full border-collapse whitespace-nowrap text-sm">
                                <thead>
                                    <tr class="bg-zinc-50/50 text-[10px] md:text-[11px] font-bold uppercase tracking-widest text-zinc-500 border-b border-zinc-200">
                                        <th class="px-4 md:px-6 py-4 text-center">Replace Variant</th>
                                        <th class="px-4 md:px-6 py-4 text-center">Image</th>
                                        <th class="px-4 md:px-6 py-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100">
                                    @forelse($script->links()->get() as $link)
                                        <tr class="hover:bg-zinc-50/50 transition-colors duration-200">
                                            
                                            <td class="px-4 md:px-6 py-4 md:py-5 align-middle text-center">
                                                <span class="font-black text-zinc-900">{{ $link->replace_name }}</span>
                                            </td>
                                            
                                            <td class="px-4 md:px-6 py-4 md:py-5 align-middle text-center">
                                                @if($link->image)
                                                    <img src="{{ Storage::url($link->image) }}" alt="{{ $link->replace_name }}" class="w-10 h-10 md:w-11 md:h-11 rounded-full object-cover mx-auto shadow-sm transition-transform hover:scale-110 cursor-pointer">
                                                @else
                                                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-zinc-100 mx-auto flex items-center justify-center border border-zinc-200 text-zinc-400 text-xs shadow-sm">
                                                        <span>📷</span>
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="px-4 md:px-6 py-4 md:py-5 align-middle text-center">
                                                <a href="{{ $link->url }}" target="_blank" 
                                                   onclick="logGlobalDownload('{{ addslashes($script->title) }}')"
                                                   class="inline-flex justify-center items-center px-5 md:px-6 py-2 bg-white border-2 border-zinc-200 text-zinc-900 text-xs md:text-sm font-bold rounded-xl hover:border-zinc-300 hover:bg-zinc-50 transition-all shadow-sm active:scale-95 whitespace-nowrap">
                                                    Download
                                                </a>
                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 md:px-6 py-8 md:py-12 text-center text-zinc-500 font-medium bg-zinc-50/50">
                                                Link download belum tersedia untuk script ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-16 pt-10 border-t border-zinc-200">
                    <h2 class="text-xl font-bold text-zinc-900 mb-6 tracking-tight flex items-center gap-2">
                        💬 Komentar ({{ $script->comments->count() }})
                    </h2>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
                            <span>✅</span> {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-10">
                        @auth
                            <form action="{{ route('script.comment.store', $script->id) }}" method="POST" class="flex gap-3 sm:gap-4">
                                @csrf
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=18181b&color=fff" class="w-10 h-10 rounded-full shrink-0 shadow-sm border border-zinc-200 hidden sm:block">
                                <div class="flex-grow">
                                    <textarea name="content" rows="3" required placeholder="Tulis komentar, laporkan bug, atau berikan reviewmu di sini..." 
                                        class="w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all resize-y text-sm text-zinc-900"></textarea>
                                    
                                    @error('content')
                                        <p class="mt-1.5 text-red-600 font-medium text-xs">{{ $message }}</p>
                                    @enderror

                                    <div class="mt-2.5 flex justify-end">
                                        <button type="submit" class="inline-flex justify-center items-center py-2.5 px-6 rounded-xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-800 transition-colors shadow-sm active:scale-95">
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="p-6 sm:p-8 bg-zinc-50 border border-zinc-200 rounded-2xl text-center flex flex-col items-center justify-center">
                                <span class="text-2xl mb-2">🔒</span>
                                <h3 class="font-bold text-zinc-900 mb-1">Bergabung dalam diskusi!</h3>
                                <p class="text-zinc-500 font-medium text-sm mb-4">Silakan masuk ke akunmu untuk meninggalkan komentar.</p>
                                <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-6 py-2.5 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors shadow-sm">
                                    Login Sekarang
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="space-y-6 sm:space-y-8">
                        @forelse($script->comments as $comment)
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex gap-3 sm:gap-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'User') }}&background=f4f4f5&color=18181b" class="w-10 h-10 rounded-full shrink-0 border border-zinc-200 mt-1">
                                    <div>
                                        <div class="flex items-baseline gap-2 mb-1.5">
                                            <h4 class="font-bold text-zinc-900 text-sm">{{ $comment->user->name ?? 'Unknown User' }}</h4>
                                            <span class="text-[11px] text-zinc-400 font-medium" title="{{ $comment->created_at->format('d M Y, H:i') }}">
                                                • {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-zinc-700 text-sm leading-relaxed">{!! nl2br(e($comment->content)) !!}</p>
                                    </div>
                                </div>

                                @auth
                                    @if(auth()->user()->usertype === 'admin' || auth()->id() === 1)
                                        <form action="{{ route('script.comment.destroy', $comment->id) }}" method="POST" class="shrink-0" onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Komentar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <span class="text-3xl mb-3 block">👻</span>
                                <p class="text-zinc-500 text-sm font-medium">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
    <x-footer/>
    <x-active-user/>
</body>
</html>