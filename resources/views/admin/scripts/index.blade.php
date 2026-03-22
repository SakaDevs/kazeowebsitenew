<x-admin-layout>
    <x-slot name="title">Manage Scripts</x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 font-medium text-sm border border-green-100 flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        
        <div class="p-6 border-b border-zinc-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">All Published Scripts</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">All Script</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('admin.scripts.index') }}" method="GET" class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-zinc-400 text-sm">🔍</span>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Cari script..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-zinc-200 rounded-xl text-sm font-medium text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-all shadow-sm">
                </form>

                <a href="{{ route('admin.scripts.create') }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95 whitespace-nowrap">
                    <span>➕</span> Upload Script
                </a>
            </div>
        </div>
        
        <div id="table-container">
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
                                    <a href="{{ route('script.show', $script->slug) }}" target="_blank" class="font-bold text-zinc-900 hover:text-blue-600 hover:underline transition-colors line-clamp-1 block">
                                        {{ $script->title }}
                                    </a>
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const tableContainer = document.getElementById('table-container');
            
            // Timer untuk debounce
            let debounceTimer;
    
            searchInput.addEventListener('input', function () {
                // Hapus timer sebelumnya jika user masih mengetik
                clearTimeout(debounceTimer);
                
                // Set timer baru: tunggu 500ms (0.5 detik) setelah user berhenti mengetik baru hit ke server
                debounceTimer = setTimeout(() => {
                    const query = this.value;
                    const url = new URL(window.location.href);
                    
                    // Update URL parameter
                    if (query) {
                        url.searchParams.set('search', query);
                    } else {
                        url.searchParams.delete('search');
                    }
    
                    // Hapus page parameter agar kembali ke halaman 1 saat mencari
                    url.searchParams.delete('page');
    
                    // Tambahkan efek loading tipis-tipis biar user tau sistem lagi mikir
                    tableContainer.style.opacity = '0.5';
    
                    // Fetch data dari server
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Ekstrak bagian tabel dari response HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableContent = doc.getElementById('table-container').innerHTML;
                        
                        // Ganti isi tabel lama dengan yang baru
                        tableContainer.innerHTML = newTableContent;
                        tableContainer.style.opacity = '1';
                        
                        // Update URL di browser tanpa reload halaman
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                        tableContainer.style.opacity = '1';
                    });
    
                }, 500); // Angka 500 ini adalah jeda ketikan (dalam milidetik)
            });
        });
    </script>
</x-admin-layout>