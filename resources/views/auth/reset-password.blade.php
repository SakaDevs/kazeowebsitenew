<x-navbar/>
<x-guest-layout>
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
         x-show="show"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="w-full bg-white/80 backdrop-blur-xl shadow-2xl shadow-zinc-200/60 rounded-3xl overflow-hidden border border-zinc-100 p-8 sm:p-10"
         style="display: none;">

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Create New Password</h2>
            <p class="text-sm text-zinc-500 mt-2 font-medium">Please enter your new password below.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-bold text-zinc-700">Email Address</label>
                <input id="email" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" readonly />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-sm font-bold text-zinc-700">New Password</label>
                <input id="password" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-sm font-bold text-zinc-700">Confirm New Password</label>
                <input id="password_confirmation" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 font-medium text-sm" />
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-zinc-900/20 text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 hover:shadow-xl hover:shadow-zinc-900/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-all duration-300 active:scale-95">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
    <x-active-user/>
</x-guest-layout>
<x-footer/>