<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mod {{ $category->name }} - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-12 mb-16">
        
        <div>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-4">
                <span>&larr;</span> Kembali ke Semua Kategori
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight">Category Script: <span class="text-red-600">{{ $category->name }}</span></h1>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @forelse($scripts as $script)
                <a href="{{ route('script.show', $script->slug) }}" class="block bg-white rounded-xl sm:rounded-2xl border border-zinc-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group flex flex-col">
                    
                    <div class="w-full h-28 sm:h-40 overflow-hidden bg-zinc-100 relative shrink-0">
                        @if($script->image)
                            <img src="{{ Storage::url($script->image) }}" alt="{{ $script->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
                        @else
                            <img src="https://via.placeholder.com/600x400" alt="No Image" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <div class="p-3 sm:p-5 text-center flex flex-col flex-grow">
                        <p class="text-[9px] sm:text-[10px] font-bold text-red-700 uppercase tracking-wider mb-1 sm:mb-1.5 line-clamp-1">
                            {{ $script->category->name ?? 'Uncategorized' }}
                        </p>
                        
                        <h3 class="text-sm sm:text-lg font-extrabold text-zinc-900 line-clamp-2 sm:line-clamp-1 group-hover:text-red-700 transition-colors" title="{{ $script->title }}">
                            {{ $script->title }}
                        </h3>
                        
                        <div class="w-8 sm:w-12 h-1 bg-yellow-400 mx-auto mt-2 sm:mt-3 mb-auto rounded-full"></div>

                        <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between text-[10px] sm:text-xs mt-3 sm:mt-6 gap-1.5 sm:gap-0">
                            <div class="text-zinc-500 font-medium">
                                By <span class="text-zinc-700 underline decoration-zinc-300 underline-offset-4 hover:decoration-red-700 hover:text-red-700 transition-colors line-clamp-1">{{ $script->user->name ?? 'Unknown' }}</span>
                            </div>
                            <div class="text-center sm:text-right text-zinc-400 font-medium space-y-0.5">
                                <p>{{ $script->updated_at->format('d M Y') }}</p>
                                <p>{{ number_format($script->views) }}x dilihat</p>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 px-4 bg-white rounded-2xl border border-zinc-200 shadow-sm">
                    <span class="text-4xl mb-3 block">📂</span>
                    <h3 class="text-lg font-bold text-zinc-900 mb-1">Kategori Masih Kosong</h3>
                    <p class="text-zinc-500 font-medium text-sm">Belum ada mod atau script yang diupload untuk kategori ini.</p>
                </div>
            @endforelse
        </div>

        @if($scripts->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $scripts->links() }}
            </div>
        @endif
        
    </main>
    
    <x-footer/>
</body>
</html>