<x-admin-layout>
    <x-slot name="title">Overview Dashboard</x-slot>

    @php
        // Mengambil data angka asli langsung dari database Kazeo
        $totalUsers = \App\Models\User::count();
        $totalScripts = \App\Models\Script::count();
        $totalCategories = \App\Models\Category::count();
        $totalCommunities = \App\Models\Community::count();
        
        // Mengambil 5 script terbaru untuk tabel pantauan
        $recentScripts = \App\Models\Script::with('user', 'category')->latest()->take(5)->get();
    @endphp

    <div class="mb-8">
        <h2 class="text-2xl font-black text-zinc-900 tracking-tight">Welcome back, Admin! 🚀</h2>
        <p class="text-sm text-zinc-500 font-medium mt-1">Berikut adalah ringkasan data website Kazeo Official saat ini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-3xl border border-zinc-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="w-14 h-14 rounded-2xl bg-zinc-900 text-white flex items-center justify-center text-2xl shadow-lg shadow-zinc-900/20 shrink-0">👥</div>
            <div>
                <p class="text-sm font-bold text-zinc-500 mb-0.5">Total Users</p>
                <h3 class="text-3xl font-black text-zinc-900">{{ number_format($totalUsers) }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-zinc-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl border border-blue-100 shrink-0">📦</div>
            <div>
                <p class="text-sm font-bold text-zinc-500 mb-0.5">Total Scripts</p>
                <h3 class="text-3xl font-black text-zinc-900">{{ number_format($totalScripts) }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-zinc-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl border border-orange-100 shrink-0">📂</div>
            <div>
                <p class="text-sm font-bold text-zinc-500 mb-0.5">Kategori</p>
                <h3 class="text-3xl font-black text-zinc-900">{{ number_format($totalCategories) }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-zinc-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl border border-red-100 shrink-0">💬</div>
            <div>
                <p class="text-sm font-bold text-zinc-500 mb-0.5">Post Komunitas</p>
                <h3 class="text-3xl font-black text-zinc-900">{{ number_format($totalCommunities) }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-zinc-200 flex justify-between items-center bg-zinc-50/50">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">Script Terbaru</h3>
                <p class="text-sm text-zinc-500 font-medium mt-1">5 script terakhir yang diupload ke website.</p>
            </div>
            <a href="{{ route('admin.scripts.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                Kelola Semua &rarr;
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 text-xs uppercase tracking-wider text-zinc-500 border-b border-zinc-200">
                        <th class="px-6 py-4 font-bold">Judul Script</th>
                        <th class="px-6 py-4 font-bold">Kategori</th>
                        <th class="px-6 py-4 font-bold">Uploader</th>
                        <th class="px-6 py-4 font-bold text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($recentScripts as $script)
                        <tr class="hover:bg-zinc-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <a href="{{ route('script.show', $script->slug) }}" target="_blank" class="font-bold text-zinc-900 hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $script->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-zinc-100 text-zinc-600 uppercase tracking-wider">
                                    {{ $script->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @php
                                        $avatar = ($script->user && $script->user->avatar) 
                                            ? asset('storage/' . $script->user->avatar) 
                                            : 'https://ui-avatars.com/api/?name='.urlencode($script->user->name ?? 'U').'&background=f4f4f5&color=18181b';
                                    @endphp
                                    <img src="{{ $avatar }}" class="w-6 h-6 rounded-full border border-zinc-200 object-cover">
                                    <span class="text-sm font-bold text-zinc-700">{{ $script->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-medium text-zinc-400">
                                {{ $script->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-500 font-medium">
                                Belum ada script yang diupload di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>