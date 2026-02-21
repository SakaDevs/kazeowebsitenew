<x-admin-layout>
    <x-slot name="title">Manage Scripts</x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">All Published Scripts</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">Kelola semua mod, skin, dan file script di sini.</p>
            </div>
            <a href="{{ route('admin.scripts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <span>➕</span> Upload Script
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 text-xs uppercase tracking-wider text-zinc-500 border-b border-zinc-200">
                        <th class="px-6 py-4 font-bold">Script Info</th>
                        <th class="px-6 py-4 font-bold">Category</th>
                        <th class="px-6 py-4 font-bold">Uploader</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($scripts as $script)
                        <tr class="hover:bg-zinc-50 transition-colors duration-200">
                            <td class="px-6 py-4 flex items-center gap-4">
                                @if($script->image)
                                    <img src="{{ Storage::url($script->image) }}" alt="Thumbnail" class="w-16 h-12 object-cover rounded-lg shadow-sm border border-zinc-200">
                                @else
                                    <div class="w-16 h-12 bg-zinc-100 rounded-lg border border-zinc-200 flex items-center justify-center text-xl shadow-sm">
                                        📦
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-zinc-900 line-clamp-1">{{ $script->title }}</p>
                                    <p class="text-xs text-zinc-500 font-medium mt-0.5">Last Updated {{ $script->updated_at->format('d M Y') }}</p>
                                    <p class="text-xs text-zinc-500 font-medium mt-0.5">{{ $script->created_at->format('d M Y') }}</p>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-zinc-100 text-zinc-700 border border-zinc-200">
                                    {{ $script->category->name ?? 'No Category' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-zinc-700">{{ $script->user->name ?? 'Unknown' }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.scripts.edit', $script->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-zinc-200 rounded-lg text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.scripts.destroy', $script->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus script ini?');">
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
                                Belum ada script yang di-upload.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($scripts->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                {{ $scripts->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>