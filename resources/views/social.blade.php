<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Hub - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h1 class="text-3xl sm:text-4xl font-black text-zinc-900 tracking-tight flex items-center justify-center gap-3 mb-4">
                <span class="text-4xl">🌐</span> Connect with Kazeo
            </h1>
            <p class="text-zinc-500 text-lg font-medium leading-relaxed">
                Jangan sampai ketinggalan update terbaru. Bergabunglah dengan komunitas kami di berbagai platform favoritmu!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

            <div class="relative overflow-hidden bg-white border-2 border-zinc-200 rounded-[2.5rem] p-8 group hover:border-red-500 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500 ease-out hover:-translate-y-2">
                <div class="absolute -right-12 -bottom-12 text-[10rem] text-red-50 opacity-50 group-hover:rotate-12 transition-transform duration-700 select-none pointer-events-none">
                    ▶️
                </div>
                
                <div class="relative z-10 flex flex-col h-full">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-zinc-900 mb-2">YouTube Channel</h3>
                    <p class="text-zinc-500 font-medium mb-8 leading-relaxed">
                        Rumah utama kami. Temukan tutorial, showcase script terbaru, dan konten eksklusif Kazeo Official.
                    </p>
                    <a href="https://www.youtube.com/@KazeoOfficialRealz" target="_blank" class="mt-auto inline-flex justify-center items-center w-full px-6 py-4 bg-zinc-900 text-white font-bold rounded-xl group-hover:bg-red-600 transition-colors shadow-md active:scale-95">
                        Subscribe Now &rarr;
                    </a>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white border-2 border-zinc-200 rounded-[2.5rem] p-8 group hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-400/10 transition-all duration-500 ease-out hover:-translate-y-2">
                <div class="absolute -right-12 -bottom-12 text-[10rem] text-blue-50 opacity-50 group-hover:-rotate-12 transition-transform duration-700 select-none pointer-events-none">
                    ✈️
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 11.944 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-zinc-900 mb-2">Telegram Channel</h3>
                    <p class="text-zinc-500 font-medium mb-8 leading-relaxed">
                        Dapatkan notifikasi script terbaru, file langsung, dan pengumuman penting di Kazeo Opsional Universe.
                    </p>
                    <a href="https://t.me/kazeoopsional" target="_blank" class="mt-auto inline-flex justify-center items-center w-full px-6 py-4 bg-zinc-900 text-white font-bold rounded-xl group-hover:bg-blue-500 transition-colors shadow-md active:scale-95">
                        Join Channel &rarr;
                    </a>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white border-2 border-zinc-200 rounded-[2.5rem] p-8 group hover:border-green-500 hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 ease-out hover:-translate-y-2">
                <div class="absolute -right-12 -bottom-12 text-[10rem] text-green-50 opacity-50 group-hover:rotate-12 transition-transform duration-700 select-none pointer-events-none">
                    💬
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-zinc-900 mb-2">WhatsApp Community</h3>
                    <p class="text-zinc-500 font-medium mb-8 leading-relaxed">
                        Gabung di grup WhatsApp Kazeo Opsional Universe untuk diskusi lebih dekat dengan sesama member.
                    </p>
                    <a href="https://chat.whatsapp.com/LkAC9Rqua995wYz19kvZxl" target="_blank" class="mt-auto inline-flex justify-center items-center w-full px-6 py-4 bg-zinc-900 text-white font-bold rounded-xl group-hover:bg-green-600 transition-colors shadow-md active:scale-95">
                        Join Group &rarr;
                    </a>
                </div>
            </div>

        </div>
    </main>
    
    <x-footer/>
</body>
</html>