<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Kategori - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-zinc-900 tracking-tight mb-3">Semua Kategori</h1>
            <div class="w-16 h-1 bg-yellow-400 mx-auto rounded-full"></div>
        </div>

        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-zinc-200 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center justify-center py-4 px-6 rounded-xl border border-zinc-200 text-sm font-bold text-zinc-700 uppercase tracking-wider hover:border-red-500 hover:text-red-600 hover:bg-red-50 transition-all duration-300 text-center shadow-sm hover:shadow-md hover:-translate-y-1">
                        {{ $category->name }}
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-zinc-500 font-medium">
                        Belum ada kategori yang dibuat.
                    </div>
                @endforelse
            </div>
        </div>
    </main>
    
    <x-footer/>
    <x-active-user/>
</body>
</html>