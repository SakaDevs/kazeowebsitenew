<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Postingan Komunitas - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 mb-16">
        
        <div>
            <a href="{{ route('community.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-4">
                <span>&larr;</span> Kembali ke Komunitas
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight flex items-center gap-2">
                <span>✍️</span> Buat Postingan Baru
            </h1>
            <p class="mt-2 text-sm text-zinc-500 font-medium">Bagikan masalahmu, request mod, atau diskusi dengan member lainnya.</p>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-zinc-200 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-zinc-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

            <form action="{{ route('community.store') }}" method="POST" class="relative z-10 space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-bold text-zinc-900 mb-2">Judul Postingan <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Bang request script recall tas tas dong..." 
                        class="w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm text-zinc-900">
                    @error('title')
                        <p class="mt-1.5 text-red-600 font-medium text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-bold text-zinc-900 mb-2">Pilih Kategori <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="category" name="category" required 
                            class="w-full pl-4 pr-10 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm text-zinc-900 appearance-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih Topik --</option>
                            <option value="Tanya Jawab" {{ old('category') == 'Tanya Jawab' ? 'selected' : '' }}>💡 Tanya Jawab</option>
                            <option value="Request Script" {{ old('category') == 'Request Script' ? 'selected' : '' }}>📝 Request Script</option>
                            <option value="Lapor Bug" {{ old('category') == 'Lapor Bug' ? 'selected' : '' }}>🐛 Lapor Bug</option>
                            <option value="Tutorial" {{ old('category') == 'Tutorial' ? 'selected' : '' }}>📚 Tutorial & Tips</option>
                            
                            @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin', 'superadmin']))
                                <option value="Info & Update" {{ old('category') == 'Info & Update' ? 'selected' : '' }}>📢 Info & Update (Admin Only)</option>
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('category')
                        <p class="mt-1.5 text-red-600 font-medium text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-bold text-zinc-900 mb-2">Isi Pesan <span class="text-red-500">*</span></label>
                    <textarea id="content" name="content" rows="8" required placeholder="Tulis keluhan, error, atau request kamu secara detail di sini..." 
                        class="w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all resize-y text-sm text-zinc-900 leading-relaxed">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1.5 text-red-600 font-medium text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4 border-t border-zinc-100">
                    <a href="{{ route('community.index') }}" class="w-full sm:w-auto px-6 py-3 text-zinc-500 font-bold text-sm text-center hover:text-zinc-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors shadow-sm active:scale-95">
                        Kirim Postingan
                    </button>
                </div>

            </form>
        </div>
    </main>
    
    <x-footer/>
</body>
</html>