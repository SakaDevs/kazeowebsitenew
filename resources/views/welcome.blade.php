<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .swiper-button-next, .swiper-button-prev {
            background-color: white;
            width: 44px !important;
            height: 44px !important;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            color: #18181b !important;
            border: 1px solid #e4e4e7;
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 18px !important;
            font-weight: bold;
        }
        .swiper-pagination-bullet-active {
            background-color: #b91c1c !important;
        }
    </style>
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16 mb-16">
        
        <section class="relative">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-zinc-900 tracking-tight">Script Terbaru</h2>
                <a href="{{ route('categories.index') }}" class="text-xs sm:text-sm font-bold text-red-700 hover:text-red-800 transition-colors">Lihat Semua &rarr;</a>
            </div>

            <div class="swiper scriptSwiper px-2 sm:px-4 py-4 -mx-2 sm:-mx-4">
                <div class="swiper-wrapper pb-10">
                    @forelse($latestScripts as $script)
                        <div class="swiper-slide h-auto">
                            <a href="{{ route('script.show', $script->slug) }}" class="h-full block bg-white rounded-xl sm:rounded-2xl border border-zinc-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group flex flex-col hover:-translate-y-1 select-none">
                                
                                <div class="w-full h-32 sm:h-40 overflow-hidden bg-zinc-100 relative shrink-0">
                                    @if($script->image)
                                        <img src="{{ Storage::url($script->image) }}" alt="{{ $script->title }}" draggable="false" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out select-none">
                                    @else
                                        <img src="https://via.placeholder.com/600x400" alt="No Image" draggable="false" class="w-full h-full object-cover select-none">
                                    @endif
                                </div>

                                <div class="p-4 sm:p-5 text-center flex flex-col flex-grow">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-red-700 uppercase tracking-wider mb-1 sm:mb-1.5 line-clamp-1">
                                        {{ $script->category->name ?? 'Uncategorized' }}
                                    </p>
                                    
                                    <h3 class="text-sm sm:text-lg font-extrabold text-zinc-900 line-clamp-2 sm:line-clamp-1 group-hover:text-red-700 transition-colors" title="{{ $script->title }}">
                                        {{ $script->title }}
                                    </h3>
                                    
                                    <div class="w-8 sm:w-12 h-1 bg-yellow-400 mx-auto mt-2 sm:mt-3 mb-auto rounded-full"></div>

                                    <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between text-[10px] sm:text-xs mt-4 sm:mt-6 gap-2 sm:gap-0">
                                        <div class="flex items-center gap-1.5 text-zinc-700 font-bold hover:text-red-700 transition-colors">
                                            @php
                                                $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($script->user->name ?? 'User').'&background=f4f4f5&color=18181b';
                                                $userAvatar = ($script->user && $script->user->avatar) ? asset('storage/' . $script->user->avatar) : $defaultAvatar;
                                            @endphp
                                            <img src="{{ $userAvatar }}" class="w-5 h-5 rounded-full object-cover border border-zinc-200 shrink-0">
                                            <span class="line-clamp-1">{{ $script->user->name ?? 'Unknown' }}</span>
                                        </div>

                                        <div class="text-center sm:text-right text-zinc-400 font-medium space-y-0.5">
                                            <p>{{ $script->updated_at->format('d M Y') }}</p>
                                            <p>{{ number_format($script->views) }}x dilihat</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="w-full text-center py-12 text-zinc-500 text-sm font-medium bg-white rounded-2xl border border-zinc-200">
                            Belum ada script yang diupload.
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination !bottom-0"></div>
                <div class="swiper-button-next hidden md:flex"></div>
                <div class="swiper-button-prev hidden md:flex"></div>
            </div>
        </section>

        <section class="relative">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-zinc-900 tracking-tight flex items-center gap-2">
                    Popular Script
                </h2>
            </div>

            <div class="swiper scriptSwiper px-2 sm:px-4 py-4 -mx-2 sm:-mx-4">
                <div class="swiper-wrapper pb-10">
                    @foreach($popularScripts as $script)
                        <div class="swiper-slide h-auto">
                            <a href="{{ route('script.show', $script->slug) }}" class="h-full block bg-white rounded-xl sm:rounded-2xl border border-zinc-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group flex flex-col hover:-translate-y-1 select-none">
                                
                                <div class="w-full h-32 sm:h-40 overflow-hidden bg-zinc-100 relative shrink-0">
                                    @if($script->image)
                                        <img src="{{ Storage::url($script->image) }}" alt="{{ $script->title }}" draggable="false" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out select-none">
                                    @else
                                        <img src="https://via.placeholder.com/600x400" alt="No Image" draggable="false" class="w-full h-full object-cover select-none">
                                    @endif
                                </div>

                                <div class="p-4 sm:p-5 text-center flex flex-col flex-grow">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-red-700 uppercase tracking-wider mb-1 sm:mb-1.5 line-clamp-1">
                                        {{ $script->category->name ?? 'Uncategorized' }}
                                    </p>
                                    
                                    <h3 class="text-sm sm:text-lg font-extrabold text-zinc-900 line-clamp-2 sm:line-clamp-1 group-hover:text-red-700 transition-colors" title="{{ $script->title }}">
                                        {{ $script->title }}
                                    </h3>
                                    
                                    <div class="w-8 sm:w-12 h-1 bg-yellow-400 mx-auto mt-2 sm:mt-3 mb-auto rounded-full"></div>

                                    <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between text-[10px] sm:text-xs mt-4 sm:mt-6 gap-2 sm:gap-0">
                                        <div class="flex items-center gap-1.5 text-zinc-700 font-bold hover:text-red-700 transition-colors">
                                            @php
                                                $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($script->user->name ?? 'User').'&background=f4f4f5&color=18181b';
                                                $userAvatar = ($script->user && $script->user->avatar) ? asset('storage/' . $script->user->avatar) : $defaultAvatar;
                                            @endphp
                                            <img src="{{ $userAvatar }}" class="w-5 h-5 rounded-full object-cover border border-zinc-200 shrink-0">
                                            <span class="line-clamp-1">{{ $script->user->name ?? 'Unknown' }}</span>
                                        </div>

                                        <div class="text-center sm:text-right text-zinc-400 font-medium space-y-0.5">
                                            <p>{{ $script->updated_at->format('d M Y') }}</p>
                                            <p class=" text-zinc-400 font-bold">{{ number_format($script->views) }}x dilihat</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination !bottom-0"></div>
                <div class="swiper-button-next hidden md:flex"></div>
                <div class="swiper-button-prev hidden md:flex"></div>
            </div>
        </section>

    </div>
    <x-footer/>

    <x-active-user/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var swipers = document.querySelectorAll('.scriptSwiper');
            
            swipers.forEach(function(swiperContainer) {
                new Swiper(swiperContainer, {
                    slidesPerView: 2, 
                    spaceBetween: 12, 
                    grabCursor: true, 
                    simulateTouch: true,
                    touchRatio: 1.5,
                    resistanceRatio: 0.8,
                    pagination: {
                        el: swiperContainer.querySelector('.swiper-pagination'),
                        clickable: true,
                        dynamicBullets: true,
                    },
                    navigation: {
                        nextEl: swiperContainer.querySelector('.swiper-button-next'),
                        prevEl: swiperContainer.querySelector('.swiper-button-prev'),
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 24,
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>