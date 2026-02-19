<nav x-data="{ openMobileMenu: false }" class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-zinc-200 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 relative">
            
            <div class="flex items-center gap-3 shrink-0">
                <a href="/" class="text-2xl tracking-tight transition-transform duration-300 hover:scale-105 flex items-center">
                    <span class="font-black text-zinc-900">Kazeo</span><span class="font-light text-zinc-500 ml-1">Official</span>
                </a>
            </div>

            <div class="hidden md:flex space-x-8 items-center absolute left-1/2 transform -translate-x-1/2 w-max">
                <a href="#" class="relative text-zinc-600 font-medium text-base group py-2 transition-colors duration-300 hover:text-zinc-900">
                    Home
                    <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-zinc-900 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#" class="relative text-zinc-600 font-medium text-base group py-2 transition-colors duration-300 hover:text-zinc-900">
                    Category
                    <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-zinc-900 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#" class="relative text-zinc-600 font-medium text-base group py-2 transition-colors duration-300 hover:text-zinc-900">
                    Community
                    <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-zinc-900 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#" class="relative text-zinc-600 font-medium text-base group py-2 transition-colors duration-300 hover:text-zinc-900">
                    Social
                    <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-zinc-900 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="relative text-zinc-600 font-medium text-base group py-2 transition-colors duration-300 hover:text-zinc-900">
                        Dashboard
                        <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-zinc-900 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-zinc-600 font-medium text-base hover:text-zinc-900 transition-colors duration-300">
                        Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-zinc-900 text-white font-medium text-base px-7 py-2.5 rounded-full shadow-lg shadow-zinc-900/20 hover:bg-zinc-800 hover:shadow-xl hover:shadow-zinc-900/30 hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                            Register
                        </a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center md:hidden">
                <button @click="openMobileMenu = !openMobileMenu" class="text-zinc-800 hover:text-zinc-600 focus:outline-none transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="openMobileMenu" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200 delay-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden bg-white/95 backdrop-blur-2xl border-t border-zinc-100 absolute w-full shadow-2xl overflow-hidden" 
         style="display: none;">
        
        <div class="px-4 pt-2 pb-6 space-y-1">
            <a href="#" 
               class="block px-4 py-3 text-base font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-all duration-500 transform"
               :class="openMobileMenu ? 'opacity-100 translate-y-0 delay-[100ms]' : 'opacity-0 -translate-y-4 delay-0'">Home</a>
            
            <a href="#" 
               class="block px-4 py-3 text-base font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-all duration-500 transform"
               :class="openMobileMenu ? 'opacity-100 translate-y-0 delay-[150ms]' : 'opacity-0 -translate-y-4 delay-0'">Category</a>
            
            <a href="#" 
               class="block px-4 py-3 text-base font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-all duration-500 transform"
               :class="openMobileMenu ? 'opacity-100 translate-y-0 delay-[200ms]' : 'opacity-0 -translate-y-4 delay-0'">Community</a>
            
            <a href="#" 
               class="block px-4 py-3 text-base font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-all duration-500 transform"
               :class="openMobileMenu ? 'opacity-100 translate-y-0 delay-[250ms]' : 'opacity-0 -translate-y-4 delay-0'">Social</a>
            
            <div class="border-t border-zinc-100 mt-4 pt-4 px-2 transition-all duration-500 transform"
                 :class="openMobileMenu ? 'opacity-100 translate-y-0 delay-[300ms]' : 'opacity-0 -translate-y-4 delay-0'">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-4 py-3 text-base font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 rounded-xl transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="block mt-3 text-center bg-zinc-900 text-white font-medium text-base px-6 py-3 rounded-xl shadow-md hover:bg-zinc-800 transition-colors">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>