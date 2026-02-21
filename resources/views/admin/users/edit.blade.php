<x-admin-layout>
    <x-slot name="title">Edit User Role</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors group">
            <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden max-w-2xl">
        <div class="p-6 border-b border-zinc-200 bg-zinc-50/50">
            <h3 class="text-lg font-bold text-zinc-900">Update Role for {{ $user->name }}</h3>
            <p class="text-sm text-zinc-500 font-medium mt-1">Hati-hati saat memberikan akses Super Admin kepada user lain.</p>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT') <div class="space-y-4 p-5 bg-zinc-50 rounded-xl border border-zinc-100">
                <div>
                    <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Full Name</label>
                    <p class="font-medium text-zinc-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Email Address</label>
                    <p class="font-medium text-zinc-900">{{ $user->email }}</p>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="role" class="block text-sm font-bold text-zinc-700">Select User Role</label>
                <select id="role" name="role" class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300 cursor-pointer">
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User (Standard Access)</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Manage Content)</option>
                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                </select>
                @error('role')
                    <p class="mt-2 text-red-600 font-medium text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="inline-flex justify-center items-center py-3.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-zinc-900 hover:bg-zinc-800 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                    Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex justify-center items-center py-3.5 px-6 border border-zinc-200 rounded-xl shadow-sm text-sm font-bold text-zinc-700 bg-white hover:bg-zinc-50 hover:shadow-md transition-all duration-300 active:scale-95">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>