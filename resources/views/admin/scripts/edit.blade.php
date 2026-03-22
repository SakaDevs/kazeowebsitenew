<x-admin-layout>
    <x-slot name="title">Edit Script: {{ $script->title }}</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.scripts.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors">
            <span>⬅️</span> Back to Scripts
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden max-w-5xl">
        <div class="p-6 border-b border-zinc-200 bg-zinc-50/50">
            <h3 class="text-lg font-bold text-zinc-900">Edit Script & Links</h3>
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
                            <option value="{{ $category->id }}" {{ $script->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-zinc-700">Main Thumbnail (Biarkan kosong jika tidak diganti)</label>
                    @if($script->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($script->image) }}" class="h-16 w-24 object-cover rounded-lg border border-zinc-200">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="block w-full px-4 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-sm" onchange="previewMainImage(this)">
                    
                    <div id="mainImagePreviewContainer" class="hidden mt-3">
                        <p class="text-xs font-bold text-zinc-500 mb-1">Preview Baru:</p>
                        <img id="mainImagePreview" src="" class="h-32 w-auto object-cover rounded-xl border border-zinc-200 shadow-sm">
                    </div>
                </div>

                <div class="space-y-1.5 md:col-span-2 border-b border-zinc-100 pb-6" x-data="{
                    templates: {{ isset($templates) ? $templates->toJson() : '[]' }},
                    selectedTemplate: '',
                    descText: {!! json_encode(old('description', $script->description)) !!},
                    fillTemplate() {
                        if(this.selectedTemplate !== '') {
                            const tmpl = this.templates.find(t => t.id == this.selectedTemplate);
                            if(tmpl) this.descText = tmpl.content; 
                        }
                    }
                }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <label class="block text-sm font-bold text-zinc-700">Description</label>
                        <select x-model="selectedTemplate" @change="fillTemplate()" class="text-sm border-zinc-200 rounded-lg bg-zinc-100 py-1.5 pl-3 pr-8 focus:ring-zinc-900 focus:border-zinc-900 font-medium text-zinc-700 cursor-pointer hover:bg-zinc-200 transition-colors shadow-sm">
                            <option value="">Ganti menggunakan Template Deskripsi...</option>
                            <template x-for="t in templates" :key="t.id">
                                <option :value="t.id" x-text="t.name"></option>
                            </template>
                        </select>
                    </div>
                    <textarea name="description" x-model="descText" rows="6" required placeholder="Ketik manual atau pilih template..." class="block w-full px-4 py-3.5 rounded-xl border border-zinc-200 bg-zinc-50/50 text-zinc-900 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all duration-300 resize-y"></textarea>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-bold text-zinc-900 mb-4 border-b pb-2">Download Links (Drag ⇅ untuk mengurutkan)</h3>
                
                <div class="mb-4 flex flex-col sm:flex-row items-end gap-3 bg-zinc-50/80 p-4 rounded-xl border border-zinc-200">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-zinc-500 mb-1.5 uppercase tracking-wider">Tambah Varian Cepat (Insert Template)</label>
                        <select id="replaceTemplateSelect" class="block w-full px-4 py-2.5 rounded-lg border border-zinc-200 bg-white text-sm font-medium focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-all cursor-pointer">
                            <option value="">-- Pilih Hero / Grup Template --</option>
                            @foreach($replaceTemplates as $rt)
                                <option value="{{ $rt->id }}" data-items="{{ $rt->items->toJson() }}">
                                    {{ $rt->name }} ({{ $rt->items->count() }} Varian)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="addFromTemplate()" class="w-full sm:w-auto px-5 py-2.5 bg-zinc-900 text-white text-sm font-bold rounded-lg hover:bg-zinc-800 transition-all active:scale-95 whitespace-nowrap shadow-sm">
                        ➕ Insert Varian
                    </button>
                </div>

                <div class="border border-zinc-200 rounded-xl overflow-hidden bg-white shadow-sm overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-sm text-left whitespace-nowrap">
                        <thead class="bg-zinc-50 border-b border-zinc-200 text-xs uppercase text-zinc-500 font-bold">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">⇅</th>
                                <th class="px-4 py-3 w-[25%]">Replace Text</th>
                                <th class="px-4 py-3 w-[25%]">URL Link</th>
                                <th class="px-4 py-3 w-[35%]">Variant Image</th>
                                <th class="px-4 py-3 w-[15%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="links-container" class="divide-y divide-zinc-100">
                            <tbody id="links-container" class="divide-y divide-zinc-100">
                                @foreach($script->links ?? [] as $index => $link)
                                    <input type="hidden" name="links[{{ $index }}][id]" value="{{ $link->id }}">
                                    
                                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="text" name="links[{{ $index }}][replace_name]" value="{{ $link->replace_name }}" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" required>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="url" name="links[{{ $index }}][url]" value="{{ $link->url }}" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" required>
                                    </td>
                                    <td class="px-4 py-3 align-top min-w-[250px]">
                                        <div class="flex flex-col gap-2 w-full">
                                            @php
                                                $imgType = 'none';
                                                $imgSrc = '';
                                                if($link->image) {
                                                    $isUrl = Str::startsWith($link->image, ['http://', 'https://']);
                                                    $imgType = $isUrl ? 'url' : 'file';
                                                    $imgSrc = $isUrl ? $link->image : Storage::url($link->image);
                                                }
                                            @endphp

                                            <select name="links[{{ $index }}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm image-type-select py-2 px-3 bg-zinc-50" onchange="toggleImageType(this, {{ $index }})">
                                                <option value="none" {{ $imgType == 'none' ? 'selected' : '' }}>Kosong / Default 📷</option>
                                                <option value="file" {{ $imgType == 'file' ? 'selected' : '' }}>Upload File dari Komputer</option>
                                                <option value="url" {{ $imgType == 'url' ? 'selected' : '' }}>Pakai Link URL</option>
                                            </select>

                                            <input type="file" name="links[{{ $index }}][image_file]" class="w-full form-control text-sm image-input-file {{ $imgType == 'file' ? '' : 'hidden' }}" accept="image/*" onchange="previewVariantImage(this, 'file')">
                                            <input type="url" name="links[{{ $index }}][image_url]" value="{{ $imgType == 'url' ? $link->image : '' }}" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url {{ $imgType == 'url' ? '' : 'hidden' }} py-2 px-3 focus:ring-zinc-900" placeholder="https://..." oninput="previewVariantImage(this, 'url')">
                                            
                                            <div class="variant-preview-container {{ $imgType != 'none' ? '' : 'hidden' }} mt-1 flex items-center gap-2">
                                                <img src="{{ $imgSrc }}" referrerpolicy="no-referrer" class="variant-preview-img h-10 w-16 object-cover rounded border border-zinc-200 shadow-sm">
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded uppercase">Preview</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center align-top pt-4">
                                        <button type="button" onclick="removeLink(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors mx-auto flex justify-center items-center" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="addLink()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-zinc-100 border border-zinc-300 text-zinc-800 text-sm font-bold rounded-xl hover:bg-zinc-200 transition-colors shadow-sm active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Baris Kosong
                    </button>
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-200">
                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center py-3.5 px-8 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 transition-all duration-300 active:scale-95">
                    Update Script
                </button>
            </div>
        </form>
    </div>

    <script>
        let linkIndex = {{ $script->links->count() }}; 

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('links-container');
            new Sortable(container, {
                handle: '.drag-handle', animation: 150, ghostClass: 'bg-zinc-100', 
                onEnd: function () { reindexLinks(); }
            });
        });

        // FUNGSI PREVIEW IMAGE (DITAMBAHKAN KEMBALI)
        function previewMainImage(input) {
            const previewContainer = document.getElementById('mainImagePreviewContainer');
            const previewImage = document.getElementById('mainImagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { previewImage.src = e.target.result; previewContainer.classList.remove('hidden'); }
                reader.readAsDataURL(input.files[0]);
            } else { previewContainer.classList.add('hidden'); previewImage.src = ''; }
        }

        function previewVariantImage(input, type) {
            const container = input.closest('td');
            const previewContainer = container.querySelector('.variant-preview-container');
            const previewImg = container.querySelector('.variant-preview-img');

            if (type === 'file') {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) { previewImg.src = e.target.result; previewContainer.classList.remove('hidden'); }
                    reader.readAsDataURL(input.files[0]);
                } else { previewContainer.classList.add('hidden'); }
            } else if (type === 'url') {
                const url = input.value;
                if (url) {
                    previewImg.src = url; previewContainer.classList.remove('hidden');
                    previewImg.onerror = function() { previewContainer.classList.add('hidden'); };
                } else { previewContainer.classList.add('hidden'); }
            }
        }

        // FUNGSI UNTUK RESET PREVIEW JIKA TIPE DIGANTI
        function toggleImageType(selectElement, index) {
            const row = selectElement.closest('.link-item');
            const fileInput = row.querySelector('.image-input-file');
            const urlInput = row.querySelector('.image-input-url');
            const previewContainer = row.querySelector('.variant-preview-container');
            
            if(previewContainer) previewContainer.classList.add('hidden');

            if (selectElement.value === 'file') {
                fileInput.classList.remove('hidden'); urlInput.classList.add('hidden'); urlInput.value = ''; 
            } else if (selectElement.value === 'url') {
                urlInput.classList.remove('hidden'); fileInput.classList.add('hidden'); fileInput.value = ''; 
            } else {
                fileInput.classList.add('hidden'); urlInput.classList.add('hidden'); fileInput.value = ''; urlInput.value = '';
            }
        }

        function addFromTemplate() {
            const select = document.getElementById('replaceTemplateSelect');
            const option = select.options[select.selectedIndex];
            if (!option.value) { alert('Pilih template dari dropdown terlebih dahulu!'); return; }

            const itemsData = option.getAttribute('data-items');
            if (itemsData) {
                const items = JSON.parse(itemsData);
                if (items.length === 0) { alert('Template ini belum memiliki varian!'); return; }
                items.forEach(item => {
                    let imageVal = item.image;
                    let finalType = 'none';
                    if ((item.image_type === 'file' || item.image_type === 'url') && imageVal) {
                        finalType = 'url';
                        if (item.image_type === 'file' && !imageVal.startsWith('http')) {
                            imageVal = '{{ Storage::url("") }}' + imageVal; 
                        }
                    }
                    addLink(item.replace_text, finalType, imageVal);
                });
            }
            select.value = ''; 
        }

        function addLink(defaultText = '', defaultImgType = 'none', defaultImgUrl = '') {
            const container = document.getElementById('links-container');
            const isUrl = defaultImgType === 'url';
            
            const html = `
                <tr class="link-item bg-white hover:bg-zinc-50 transition-colors">
                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input type="text" name="links[${linkIndex}][replace_name]" value="${defaultText}" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900" required>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input type="url" name="links[${linkIndex}][url]" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900" required>
                    </td>
                    <td class="px-4 py-3 align-top min-w-[250px]">
                        <div class="flex flex-col gap-2 w-full">
                            <select name="links[${linkIndex}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm image-type-select py-2 px-3 bg-zinc-50" onchange="toggleImageType(this, ${linkIndex})">
                                <option value="none" ${defaultImgType === 'none' ? 'selected' : ''}>Kosong / Default 📷</option>
                                <option value="file">Upload File dari Komputer</option>
                                <option value="url" ${defaultImgType === 'url' ? 'selected' : ''}>Pakai Link URL</option>
                            </select>
                            <input type="file" name="links[${linkIndex}][image_file]" class="w-full form-control text-sm image-input-file hidden" accept="image/*" onchange="previewVariantImage(this, 'file')">
                            <input type="url" name="links[${linkIndex}][image_url]" value="${defaultImgUrl}" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url ${isUrl ? '' : 'hidden'} py-2 px-3 focus:ring-zinc-900" placeholder="https://..." oninput="previewVariantImage(this, 'url')">
                            
                            <div class="variant-preview-container ${isUrl && defaultImgUrl ? '' : 'hidden'} mt-1 flex items-center gap-2">
                                <img src="${isUrl ? defaultImgUrl : ''}" referrerpolicy="no-referrer" class="variant-preview-img h-10 w-16 object-cover rounded border border-zinc-200 shadow-sm">
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded uppercase">Preview</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center align-top pt-4">
                        <button type="button" onclick="removeLink(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors mx-auto flex justify-center items-center" title="Hapus Baris">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
            container.insertAdjacentHTML('beforeend', html);
            linkIndex++;
            reindexLinks();
        }

        function removeLink(button) {
            const tbody = document.getElementById('links-container');
            if (tbody.children.length === 1) { alert("Minimal harus ada 1 link download!"); return; }
            if(confirm('Hapus baris link ini?')) { button.closest('.link-item').remove(); reindexLinks(); }
        }

        function reindexLinks() {
            document.querySelectorAll('#links-container .link-item').forEach((row, newIndex) => {
                row.querySelectorAll('input, select').forEach(input => {
                    if (input.name) input.name = input.name.replace(/links\[\d+\]/, `links[${newIndex}]`);
                });
                const select = row.querySelector('.image-type-select');
                if(select) select.setAttribute('onchange', `toggleImageType(this, ${newIndex})`);
            });
        }
    </script>
</x-admin-layout>