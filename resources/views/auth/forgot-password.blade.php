<x-navbar/>
<x-guest-layout>
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
         x-show="show"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="w-full bg-white/80 backdrop-blur-xl shadow-2xl shadow-zinc-200/60 rounded-3xl overflow-hidden border border-zinc-100 p-8 sm:p-10"
         style="display: none;">

        <div class="mb-6 text-center">
            <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Reset Password</h2>
            <p class="text-sm text-zinc-500 mt-3 font-medium leading-relaxed">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>
        </div>

        <x-auth-session-status class="mb-6 p-4 rounded-xl bg-green-50 text-green-600 font-medium text-sm border border-green-100 text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-bold text-zinc-700">Email Address</label>
                <input id="email" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-zinc-900/20 text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 hover:shadow-xl hover:shadow-zinc-900/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-all duration-300 active:scale-95">
                    Email Password Reset Link
                </button>
            </div>

            <div class="text-center mt-6 pt-2">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors duration-300 group">
                    <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
<x-footer/>