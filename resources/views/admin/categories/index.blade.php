<x-admin-layout>
    <x-slot name="title">Manage Categories</x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">All Categories</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">Kelola kategori untuk konten webmu.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <span>➕</span> Add New Category
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 text-xs uppercase tracking-wider text-zinc-500 border-b border-zinc-200">
                        <th class="px-6 py-4 font-bold w-16">No</th>
                        <th class="px-6 py-4 font-bold">Category Name</th>
                        <th class="px-6 py-4 font-bold">Slug URL</th>
                        <th class="px-6 py-4 font-bold text-center">Total Script</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-zinc-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm font-bold text-zinc-400">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <p class="font-bold text-zinc-900">{{ $category->name }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-zinc-100 text-zinc-500 font-mono border border-zinc-200">
                                    {{ $category->slug }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ $category->scripts()->count() }} Item
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-zinc-200 rounded-lg text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua konten di dalamnya mungkin akan terdampak.');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 font-medium">
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>