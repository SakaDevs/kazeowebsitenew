<x-admin-layout>
    <x-slot name="title">Edit Script & Links</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.scripts.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors">
            <span>⬅️</span> Back to Scripts
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden max-w-5xl">
        <div class="p-6 border-b border-zinc-200 bg-zinc-50/50">
            <h3 class="text-lg font-bold text-zinc-900">Update Script: {{ $script->title }}</h3>
        </div>

        <form action="{{ route('admin.scripts.update', $script->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-zinc-100">
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-sm font-bold text-zinc-700">Script Title</label>
                    <input type="text" name="title" value="{{ old('title', $script->title) }}" required class="block w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all">
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-zinc-700">Category</label>
                    <select name="category_id" required class="block w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all cursor-pointer">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $script->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-zinc-700">Update Main Thumbnail <span class="text-zinc-400 font-normal">(Opsional)</span></label>
                    @if($script->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($script->image) }}" class="w-16 h-12 object-cover rounded-lg border border-zinc-200 shadow-sm">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-sm">
                </div>

                <div class="space-y-1.5 md:col-span-2 border-b border-zinc-100 pb-6" x-data="{
                    templates: {{ isset($templates) ? $templates->toJson() : '[]' }},
                    selectedTemplate: '',
                    descText: `{{ old('description', $script->description ?? '') }}`,
                    fillTemplate() {
                        if(this.selectedTemplate !== '') {
                            const tmpl = this.templates.find(t => t.id == this.selectedTemplate);
                            if(tmpl) {
                                // Timpa isi textarea dengan content dari template
                                this.descText = tmpl.content; 
                            }
                        }
                    }
                }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <label class="block text-sm font-bold text-zinc-700">Description</label>
                        
                        <select x-model="selectedTemplate" @change="fillTemplate()" class="text-sm border-zinc-200 rounded-lg bg-zinc-100 py-1.5 pl-3 pr-8 focus:ring-zinc-900 focus:border-zinc-900 font-medium text-zinc-700 cursor-pointer hover:bg-zinc-200 transition-colors shadow-sm">
                            <option value="">💡 Gunakan Template Deskripsi...</option>
                            <template x-for="t in templates" :key="t.id">
                                <option :value="t.id" x-text="t.name"></option>
                            </template>
                        </select>
                    </div>
    
                    <textarea name="description" x-model="descText" rows="6" required placeholder="Pilih template di atas, atau ketik manual deskripsimu di sini..."
                        class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300 resize-y"></textarea>
                    
                    @error('description') 
                        <p class="mt-2 text-red-600 font-medium text-sm">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            @php
                // Kita tambahkan ()->get() di sini untuk memaksa Laravel memanggil tabel relasi
                $existingLinks = $script->links()->get()->map(function($link) {
                    return [
                        'id' => $link->id,
                        'replace_name' => $link->replace_name,
                        'url' => $link->url,
                        'image_url' => $link->image ? Storage::url($link->image) : null,
                        'is_existing' => true
                    ];
                });
                
                // Beri 1 baris kosong kalau ternyata tidak ada link sama sekali
                if($existingLinks->isEmpty()) {
                    $existingLinks = [['id' => 'new_1', 'replace_name' => '', 'url' => '', 'image_url' => null, 'is_existing' => false]];
                }
            @endphp

            <div x-data="{ links: {{ json_encode($existingLinks) }} }" class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="block text-lg font-black text-zinc-900 tracking-tight">Manage Download Links</label>
                    <button type="button" @click="links.push({ id: 'new_' + Date.now(), replace_name: '', url: '', image_url: null, is_existing: false })" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-sm font-bold rounded-lg transition-colors">
                        <span>➕</span> Add Link
                    </button>
                </div>

                <template x-for="(link, index) in links" :key="link.id">
                    <div class="flex flex-col sm:flex-row gap-4 items-end p-5 rounded-xl border border-zinc-200 bg-zinc-50 transition-all shadow-sm relative group">
                        
                        <template x-if="link.is_existing">
                            <input type="hidden" :name="'links['+index+'][id]'" :value="link.id">
                        </template>

                        <div class="w-full sm:w-1/3 space-y-1.5">
                            <label class="block text-xs font-bold text-zinc-500 uppercase">Replace Text</label>
                            <input type="text" :name="'links['+index+'][replace_name]'" x-model="link.replace_name" placeholder="Contoh: painted_replace_skinid Lancelot Zodiac" required class="block w-full px-4 py-2.5 rounded-lg border border-zinc-300 bg-white text-zinc-900 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all text-sm">
                        </div>
                        
                        <div class="w-full sm:w-1/3 space-y-1.5">
                            <label class="block text-xs font-bold text-zinc-500 uppercase">URL Link</label>
                            <input type="url" :name="'links['+index+'][url]'" x-model="link.url" placeholder="https://..." required class="block w-full px-4 py-2.5 rounded-lg border border-zinc-300 bg-white text-zinc-900 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all text-sm">
                        </div>

                        <div class="w-full sm:w-1/3 space-y-1.5">
                            <div class="flex justify-between items-end">
                                <label class="block text-xs font-bold text-zinc-500 uppercase">Variant Image</label>
                                <template x-if="link.image_url">
                                    <img :src="link.image_url" class="w-8 h-6 object-cover rounded shadow-sm border border-zinc-200 mb-1">
                                </template>
                            </div>
                            <input type="file" :name="'links['+index+'][image]'" accept="image/*" class="block w-full px-3 py-2 rounded-lg border border-zinc-300 bg-white text-xs">
                        </div>

                        <button type="button" @click="links.splice(index, 1)" class="absolute -top-3 -right-3 w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-full shadow-md hover:bg-red-200 hover:scale-110 transition-all" title="Hapus baris">
                            ✖
                        </button>
                    </div>
                </template>
            </div>

            <div class="pt-6 border-t border-zinc-200">
                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center py-3.5 px-8 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 transition-all duration-300 active:scale-95">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>