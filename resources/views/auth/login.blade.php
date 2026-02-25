<x-guest-layout>
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
         x-show="show"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="w-full sm:max-w-md bg-white border border-zinc-200 shadow-sm overflow-hidden rounded-[2rem] px-6 sm:px-8 py-8 sm:py-10 z-10"
         style="display: none;">

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Welcome Back</h2>
            <p class="text-sm text-zinc-500 mt-2 font-medium">Log in to your Kazeo Official account.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-bold text-zinc-700">Email Address</label>
                <input id="email" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-bold text-zinc-700">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors duration-300">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <input id="password" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            <div class="block pt-1">
                <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-zinc-300 text-zinc-900 shadow-sm focus:ring-zinc-900/20 cursor-pointer transition-colors" name="remember">
                    <span class="ml-2 text-sm font-medium text-zinc-600 group-hover:text-zinc-900 transition-colors duration-300">Remember me</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-zinc-900/20 text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 hover:shadow-xl hover:shadow-zinc-900/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-all duration-300 active:scale-95">
                    Log in
                </button>
            </div>
        </form>

        <div class="mt-8 relative flex items-center justify-center">
            <div class="border-t border-zinc-200 w-full"></div>
            <div class="absolute bg-white px-4 text-sm font-medium text-zinc-400">Or continue with</div>
        </div>

        <div class="mt-6 grid grid-cols-3 gap-4">
            <a href="{{ route('socialite.redirect', 'google') }}" class="flex justify-center items-center py-3 px-4 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 hover:border-zinc-300 hover:shadow-sm transition-all duration-300 active:scale-95">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
            </a>
            <a href="{{ route('socialite.redirect', 'facebook') }}" class="flex justify-center items-center py-3 px-4 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 hover:border-zinc-300 hover:shadow-sm transition-all duration-300 active:scale-95">
                <svg class="w-6 h-6 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="{{ route('socialite.redirect', 'github') }}" class="flex justify-center items-center py-3 px-4 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 hover:border-zinc-300 hover:shadow-sm transition-all duration-300 active:scale-95">
                <svg class="w-5 h-5 text-zinc-900" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <div class="text-center mt-8">
            <p class="text-sm text-zinc-500 font-medium">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-bold text-zinc-900 hover:text-zinc-500 hover:underline transition-colors duration-300">
                    Register here
                </a>
            </p>
        </div>
    </div>
    <x-active-user/>
</x-guest-layout>