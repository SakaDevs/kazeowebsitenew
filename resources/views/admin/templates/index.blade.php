<x-admin-layout>
    <x-slot name="title">Manage Templates</x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">Script Templates</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">Kelola template otomatis untuk deskripsi upload script.</p>
            </div>
            <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <span>➕</span> Add New Template
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 text-xs uppercase tracking-wider text-zinc-500 border-b border-zinc-200">
                        <th class="px-6 py-4 font-bold w-16">No</th>
                        <th class="px-6 py-4 font-bold w-1/4">Template Name</th>
                        <th class="px-6 py-4 font-bold">Content Preview</th>
                        <th class="px-6 py-4 font-bold text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($templates as $index => $template)
                        <tr class="hover:bg-zinc-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm font-bold text-zinc-400">
                                {{ $templates->firstItem() + $index }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <p class="font-bold text-zinc-900">{{ $template->name }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-sm text-zinc-600 line-clamp-2">
                                    {{ Str::limit($template->content, 100) }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.templates.edit', $template->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-zinc-200 rounded-lg text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.templates.destroy', $template->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus template ini?');">
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
                                Belum ada template deskripsi yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($templates->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>