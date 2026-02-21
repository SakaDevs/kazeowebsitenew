<x-admin-layout>
    <x-slot name="title">Edit Script Template</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors group">
            <svg class="w-4 h-4 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Templates
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden max-w-3xl">
        <div class="p-6 border-b border-zinc-200 bg-zinc-50/50">
            <h3 class="text-lg font-bold text-zinc-900">Update Template</h3>
            <p class="text-sm text-zinc-500 font-medium mt-1">Ubah nama atau isi dari template deskripsi ini.</p>
        </div>

        <form action="{{ route('admin.templates.update', $template->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT') <div class="space-y-1.5">
                <label for="name" class="block text-sm font-bold text-zinc-700">Template Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $template->name) }}" required
                    class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300">
                
                @error('name')
                    <p class="mt-2 text-red-600 font-medium text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="content" class="block text-sm font-bold text-zinc-700">Template Content</label>
                <textarea id="content" name="content" rows="8" required
                    class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300 resize-y">{{ old('content', $template->content) }}</textarea>
                
                @error('content')
                    <p class="mt-2 text-red-600 font-medium text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="inline-flex justify-center items-center py-3.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-zinc-900 hover:bg-zinc-800 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                    Save Changes
                </button>
                <a href="{{ route('admin.templates.index') }}" class="inline-flex justify-center items-center py-3.5 px-6 border border-zinc-200 rounded-xl shadow-sm text-sm font-bold text-zinc-700 bg-white hover:bg-zinc-50 hover:shadow-md transition-all duration-300 active:scale-95">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>