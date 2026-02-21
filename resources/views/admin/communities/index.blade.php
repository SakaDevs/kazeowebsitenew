<x-admin-layout>
    <x-slot name="title">Community Moderation</x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">User Discussions</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">Pantau, pin, atau hapus postingan komunitas dari user.</p>
            </div>
            </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 text-xs uppercase tracking-wider text-zinc-500 border-b border-zinc-200">
                        <th class="px-6 py-4 font-bold">Post Info</th>
                        <th class="px-6 py-4 font-bold text-center">Stats</th>
                        <th class="px-6 py-4 font-bold text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($communities as $post)
                        <tr class="hover:bg-zinc-50 transition-colors duration-200 {{ $post->is_pinned ? 'bg-amber-50/30' : '' }}">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'User') }}&background=f4f4f5&color=18181b&rounded=true" class="w-8 h-8 rounded-full border border-zinc-200 mt-1">
                                    <div>
                                        <p class="font-bold text-zinc-900 line-clamp-2">{{ Str::limit($post->body, 80) }}</p>
                                        <p class="text-xs text-zinc-500 font-medium mt-0.5">By {{ $post->user->name ?? 'Unknown' }} • {{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-4 text-sm font-bold text-zinc-600">
                                    <span class="flex items-center gap-1" title="Reactions">🔥 {{ $post->reactions_count }}</span>
                                    <span class="flex items-center gap-1" title="Comments">💬 {{ $post->comments_count }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($post->is_pinned)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        📌 Pinned
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-zinc-100 text-zinc-500 border border-zinc-200">
                                        Standard
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <form action="{{ route('admin.communities.pin', $post->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-zinc-200 rounded-lg text-sm font-bold {{ $post->is_pinned ? 'text-amber-600 hover:bg-amber-50' : 'text-zinc-700 hover:bg-zinc-50' }} transition-colors shadow-sm">
                                        {{ $post->is_pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.communities.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus postingan ini secara permanen?');">
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
                                Belum ada postingan komunitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($communities->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                {{ $communities->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>