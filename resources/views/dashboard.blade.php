<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profil - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight">
                Halo, {{ explode(' ', trim($user->name))[0] }}! 👋
            </h1>
            <p class="mt-2 text-sm text-zinc-500 font-medium">Selamat datang di markas pribadimu. Pantau semua aktivitasmu di Kazeo dari sini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-zinc-200 shadow-sm relative overflow-hidden group">
                    @php $isAdmin = in_array($user->role, ['admin', 'super_admin', 'superadmin']); @endphp
                    <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full blur-3xl opacity-30 pointer-events-none transition-colors duration-500 {{ $isAdmin ? 'bg-blue-500' : 'bg-zinc-300' }}"></div>

                    <div class="relative z-10 flex flex-col items-center text-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $isAdmin ? '18181b' : 'f4f4f5' }}&color={{ $isAdmin ? 'fff' : '18181b' }}&size=128" 
                             class="w-24 h-24 rounded-full border-4 border-white shadow-md mb-4 group-hover:scale-105 transition-transform">
                        
                        <h2 class="text-xl font-black text-zinc-900 flex items-center gap-1 justify-center">
                            {{ $user->name }}
                            @if($isAdmin) <span title="Verified Admin" class="text-blue-500 text-lg">☑️</span> @endif
                        </h2>
                        
                        <p class="text-sm text-zinc-500 font-medium mb-1">{{ $user->email }}</p>
                        
                        <div class="mt-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider {{ $isAdmin ? 'bg-zinc-900 text-white shadow-md' : 'bg-zinc-100 text-zinc-600 border border-zinc-200' }}">
                                {{ str_replace('_', ' ', $user->role ?? 'Member') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-zinc-100 space-y-3">
                        <a href="{{ route('profile.edit') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-zinc-100 text-zinc-900 text-sm font-bold rounded-xl hover:bg-zinc-200 transition-colors">
                            ⚙️ Pengaturan Akun
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-red-200 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50 transition-colors">
                                🚪 Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>

                @if($isAdmin)
                    <div class="bg-zinc-900 p-6 rounded-3xl shadow-md text-white">
                        <h3 class="font-black text-lg mb-2">🛡️ Akses Admin</h3>
                        <p class="text-zinc-400 text-xs mb-4">Kelola user, kategori, dan validasi script dari ruang kendali utama.</p>
                        <a href="{{ route('admin.dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white text-zinc-900 text-sm font-bold rounded-xl hover:bg-zinc-100 transition-colors shadow-sm">
                            Buka Panel Admin &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-3 space-y-6" x-data="{ tab: 'scripts' }">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4 cursor-pointer hover:border-zinc-300 transition-colors" @click="tab = 'scripts'">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">📦</div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500">Script Diupload</p>
                            <h4 class="text-2xl font-black text-zinc-900">{{ $myScripts->count() }}</h4>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4 cursor-pointer hover:border-zinc-300 transition-colors" @click="tab = 'posts'">
                        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl">💬</div>
                        <div>
                            <p class="text-sm font-medium text-zinc-500">Post Komunitas</p>
                            <h4 class="text-2xl font-black text-zinc-900">{{ $myPosts->count() }}</h4>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden min-h-[400px]">
                    
                    <div class="flex border-b border-zinc-200 bg-zinc-50/50">
                        <button @click="tab = 'scripts'" :class="tab === 'scripts' ? 'border-b-2 border-zinc-900 text-zinc-900 font-bold bg-white' : 'text-zinc-500 font-medium hover:text-zinc-700'" class="flex-1 py-4 text-sm transition-colors">
                            📦 Script Saya
                        </button>
                        <button @click="tab = 'posts'" :class="tab === 'posts' ? 'border-b-2 border-zinc-900 text-zinc-900 font-bold bg-white' : 'text-zinc-500 font-medium hover:text-zinc-700'" class="flex-1 py-4 text-sm transition-colors">
                            💬 Riwayat Komunitas
                        </button>
                    </div>

                    <div x-show="tab === 'scripts'" class="p-6">
                        <div class="space-y-4">
                            @forelse($myScripts as $script)
                                <div class="flex items-center justify-between p-4 rounded-2xl border border-zinc-100 hover:border-zinc-200 hover:shadow-sm transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-zinc-100 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                            📄
                                        </div>
                                        <div>
                                            <a href="{{ route('script.show', $script->slug) }}" class="font-bold text-zinc-900 hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $script->title }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-zinc-500 font-medium">
                                                <span class="bg-zinc-100 px-2 py-0.5 rounded-md">{{ $script->category->name ?? 'Uncategorized' }}</span>
                                                <span>•</span>
                                                <span>👁️ {{ $script->views }}x dilihat</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('script.show', $script->slug) }}" class="hidden sm:inline-flex px-4 py-2 text-xs font-bold text-zinc-600 bg-zinc-100 rounded-lg hover:bg-zinc-200 hover:text-zinc-900 transition-colors">
                                        Lihat
                                    </a>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <span class="text-4xl block mb-3">🕸️</span>
                                    <p class="text-zinc-500 font-medium text-sm">Kamu belum mengupload script apapun.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="tab === 'posts'" class="p-6" style="display: none;">
                        <div class="space-y-4">
                            @forelse($myPosts as $post)
                                <div class="p-4 rounded-2xl border border-zinc-100 hover:border-zinc-200 hover:shadow-sm transition-all">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-zinc-100 text-zinc-600 uppercase tracking-wider">
                                            {{ $post->category }}
                                        </span>
                                        <span class="text-xs text-zinc-400 font-medium">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="font-bold text-zinc-900 text-sm mb-1 line-clamp-1">{{ $post->title }}</h4>
                                    <p class="text-xs text-zinc-500 line-clamp-2 leading-relaxed">{{ $post->content }}</p>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <span class="text-4xl block mb-3">👻</span>
                                    <p class="text-zinc-500 font-medium text-sm">Belum ada jejak postingan di komunitas.</p>
                                    <a href="{{ route('community.index') }}" class="mt-4 inline-block text-sm font-bold text-blue-600 hover:underline">Nongkrong sekarang &rarr;</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
    
    <x-footer/>
    <x-active-user/>
    <x-global-toast/>
</body>
</html>