<x-admin-layout>
    <x-slot name="title">Manage Users</x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 font-medium text-sm border border-red-100 flex items-center gap-2">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">All Registered Users</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">Atur role atau hapus akun pengguna di sini.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 text-xs uppercase tracking-wider text-zinc-500 border-b border-zinc-200">
                        <th class="px-6 py-4 font-bold">User Info</th>
                        <th class="px-6 py-4 font-bold">Role</th>
                        <th class="px-6 py-4 font-bold">Joined Date</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=18181b&color=fff&rounded=true" class="w-10 h-10 rounded-xl shadow-sm">
                                    <div>
                                        <p class="font-bold text-zinc-900">{{ $user->name }}</p>
                                        <p class="text-xs font-medium text-zinc-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($user->role === 'super_admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-900 text-white">Super Admin</span>
                                @elseif($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-200 text-zinc-800">Admin</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-100 text-zinc-500">User</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-zinc-600">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-zinc-200 rounded-lg text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                                    Edit Role
                                </a>
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-100 rounded-lg text-sm font-bold text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors shadow-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-500 font-medium">
                                Belum ada user yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>