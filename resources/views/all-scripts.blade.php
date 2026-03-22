<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Script - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 pt-24 pb-12 min-h-screen flex flex-col">
    <x-navbar/>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-grow">
        
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight">Semua Script Kazeo</h1>
            <p class="text-zinc-500 font-medium mt-2">Jelajahi seluruh koleksi mod, skin, dan script terbaru kami.</p>
        </div>

        <div id="script-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @include('partials.script-cards')
        </div>

        <div id="loading-spinner" class="w-full flex justify-center py-12 {{ !$scripts->hasMorePages() ? 'hidden' : '' }}">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-zinc-200 border-t-red-600"></div>
        </div>

        <div id="no-more-data" class="w-full text-center py-12 text-zinc-500 font-medium {{ $scripts->hasMorePages() ? 'hidden' : '' }}">
            Kamu telah melihat semua script!
        </div>

    </div>

    <x-footer/>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let page = 1;
            let isLoading = false;
            let hasMore = {{ $scripts->hasMorePages() ? 'true' : 'false' }};
            
            const spinner = document.getElementById('loading-spinner');
            const noMoreData = document.getElementById('no-more-data');
            const container = document.getElementById('script-container');

            // Gunakan IntersectionObserver untuk mendeteksi kapan elemen spinner masuk layar
            const observer = new IntersectionObserver((entries) => {
                // Jika spinner masuk ke layar, tidak sedang loading, dan masih ada data
                if (entries[0].isIntersecting && !isLoading && hasMore) {
                    loadMoreScripts();
                }
            }, {
                rootMargin: '200px' // Fetch data 200px sebelum user benar-benar mencapai paling bawah
            });

            if(spinner) observer.observe(spinner);

            function loadMoreScripts() {
                isLoading = true;
                page++;

                // Lakukan fetch ke URL yang sama namun tambah parameter ?page=X
                fetch(`{{ route('scripts.all') }}?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Masukkan HTML baru ke dalam container
                    container.insertAdjacentHTML('beforeend', data.html);
                    
                    hasMore = data.hasMore;
                    isLoading = false;

                    // Jika tidak ada data lagi, sembunyikan spinner dan munculkan pesan selesai
                    if (!hasMore) {
                        spinner.classList.add('hidden');
                        noMoreData.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    isLoading = false;
                });
            }
        });
    </script>
</body>
</html>