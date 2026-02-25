<x-admin-layout>
    <x-slot name="title">Edit Script & Links</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

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

            <div class="mt-8">
                <h3 class="text-lg font-bold text-zinc-900 mb-4 border-b pb-2">Manage Download Links (Drag Icon ⇅ untuk mengurutkan)</h3>
                
                <div class="border border-zinc-200 rounded-xl overflow-hidden bg-white shadow-sm overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="bg-zinc-50 border-b border-zinc-200 text-xs uppercase text-zinc-500 font-bold">
                            <tr>
                                <th class="px-4 py-3 w-10 text-center">⇅</th>
                                <th class="px-4 py-3 min-w-[200px]">Replace Text</th>
                                <th class="px-4 py-3 min-w-[200px]">URL Link</th>
                                <th class="px-4 py-3 min-w-[250px]">Variant Image</th>
                                <th class="px-4 py-3 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="links-container" class="divide-y divide-zinc-100">
                            
                            @forelse($script->links()->get() as $index => $link)
                                @php
                                    // Deteksi tipe gambar saat ini
                                    $imageType = 'none';
                                    $imageUrlPreview = null;
                                    
                                    if ($link->image) {
                                        if (Str::startsWith($link->image, ['http://', 'https://'])) {
                                            $imageType = 'url';
                                            $imageUrlPreview = $link->image;
                                        } else {
                                            $imageType = 'file';
                                            $imageUrlPreview = Storage::url($link->image);
                                        }
                                    }
                                @endphp
                                <tr class="link-item bg-white hover:bg-zinc-50 transition-colors">
                                    <input type="hidden" name="links[{{ $index }}][id]" value="{{ $link->id }}">
                                    
                                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="text" name="links[{{ $index }}][replace_name]" value="{{ $link->replace_name }}" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3" required>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="url" name="links[{{ $index }}][url]" value="{{ $link->url }}" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3" required>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        @if($imageUrlPreview)
                                            <div class="mb-2">
                                                <img src="{{ $imageUrlPreview }}" referrerpolicy="no-referrer" class="h-10 w-auto rounded border border-zinc-200 object-cover">
                                            </div>
                                        @endif
                                        <select name="links[{{ $index }}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm mb-2 image-type-select py-2 px-3 bg-zinc-50" onchange="toggleImageType(this, {{ $index }})">
                                            <option value="none" {{ $imageType == 'none' ? 'selected' : '' }}>Kosong / Default 📷</option>
                                            <option value="file" {{ $imageType == 'file' ? 'selected' : '' }}>Ganti File dari Komputer</option>
                                            <option value="url" {{ $imageType == 'url' ? 'selected' : '' }}>Ganti Link (Wikia/Fandom)</option>
                                        </select>
                                        
                                        <input type="file" name="links[{{ $index }}][image_file]" class="w-full form-control text-sm image-input-file {{ $imageType == 'file' ? '' : 'hidden' }}" accept="image/*">
                                        
                                        <input type="url" name="links[{{ $index }}][image_url]" value="{{ $imageType == 'url' ? $link->image : '' }}" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url {{ $imageType == 'url' ? '' : 'hidden' }} py-2 px-3" placeholder="https://static.wikia...">
                                    </td>
                                    <td class="px-4 py-3 text-center align-top pt-4">
                                        <button type="button" onclick="removeLink(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                @endforelse
                            
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="addLink()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-zinc-100 border border-zinc-300 text-zinc-800 text-sm font-bold rounded-xl hover:bg-zinc-200 transition-colors shadow-sm active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Baris Varian
                    </button>
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-200">
                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center py-3.5 px-8 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 transition-all duration-300 active:scale-95">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        // Mulai index dari total row saat ini supaya tidak bentrok
        let linkIndex = {{ $script->links()->count() > 0 ? $script->links()->count() : 1 }};

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('links-container');
            new Sortable(container, {
                handle: '.drag-handle', 
                animation: 150,
                ghostClass: 'bg-zinc-100', 
                onEnd: function () { reindexLinks(); }
            });
        });

        function addLink() {
            const container = document.getElementById('links-container');
            const html = `
                <tr class="link-item bg-white hover:bg-zinc-50 transition-colors">
                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input type="text" name="links[${linkIndex}][replace_name]" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3" placeholder="Ex: Gusion Venom" required>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input type="url" name="links[${linkIndex}][url]" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3" placeholder="https://..." required>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <select name="links[${linkIndex}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm mb-2 image-type-select py-2 px-3 bg-zinc-50" onchange="toggleImageType(this, ${linkIndex})">
                            <option value="none" selected>Kosong / Default 📷</option>
                            <option value="file">Upload File dari Komputer</option>
                            <option value="url">Pakai Link (Wikia/Fandom)</option>
                        </select>
                        <input type="file" name="links[${linkIndex}][image_file]" class="w-full form-control text-sm image-input-file hidden" accept="image/*">
                        <input type="url" name="links[${linkIndex}][image_url]" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url hidden py-2 px-3" placeholder="https://static.wikia...">
                    </td>
                    <td class="px-4 py-3 text-center align-top pt-4">
                        <button type="button" onclick="removeLink(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors" title="Hapus Baris">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
            container.insertAdjacentHTML('beforeend', html);
            linkIndex++;
            reindexLinks();
        }

        function toggleImageType(selectElement, index) {
            const row = selectElement.closest('.link-item');
            const fileInput = row.querySelector('.image-input-file');
            const urlInput = row.querySelector('.image-input-url');

            if (selectElement.value === 'file') {
                fileInput.classList.remove('hidden');
                urlInput.classList.add('hidden');
            } else if (selectElement.value === 'url') {
                urlInput.classList.remove('hidden');
                fileInput.classList.add('hidden');
            } else {
                fileInput.classList.add('hidden');
                urlInput.classList.add('hidden');
            }
        }

        function removeLink(button) {
            const tbody = document.getElementById('links-container');
            if (tbody.children.length === 1) {
                alert("Minimal harus ada 1 link download!");
                return;
            }
            if(confirm('Hapus baris link ini?')) {
                button.closest('.link-item').remove();
                reindexLinks(); 
            }
        }

        function reindexLinks() {
            document.querySelectorAll('#links-container .link-item').forEach((row, newIndex) => {
                row.querySelectorAll('input, select').forEach(input => {
                    if (input.name) {
                        // Secara pintar mengganti [0], [1] menjadi urutan yang benar
                        input.name = input.name.replace(/links\[\d+\]/, `links[${newIndex}]`);
                    }
                });
                const select = row.querySelector('.image-type-select');
                if(select) {
                    select.setAttribute('onchange', `toggleImageType(this, ${newIndex})`);
                }
            });
        }
    </script>
</x-admin-layout>