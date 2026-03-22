<x-admin-layout>
    <x-slot name="title">Edit Replace Template</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <div class="mb-6">
        <a href="{{ route('admin.replace_templates.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors">
            <span>⬅️</span> Back to Templates
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden max-w-5xl">
        <form action="{{ route('admin.replace_templates.update', $replaceTemplate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6 border-b border-zinc-200 bg-zinc-50/50">
                <label class="block text-sm font-bold text-zinc-700 mb-1.5">Nama Grup / Hero</label>
                <input type="text" name="name" value="{{ old('name', $replaceTemplate->name) }}" required class="block w-full max-w-md px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="p-6">
                <h3 class="text-lg font-bold text-zinc-900 mb-4 border-b pb-2">Varian Replace & Gambar (Drag ⇅ untuk mengurutkan)</h3>
                
                <div class="border border-zinc-200 rounded-xl overflow-hidden bg-white shadow-sm overflow-x-auto">
                    <table class="w-full min-w-[800px] text-sm text-left whitespace-nowrap">
                        <thead class="bg-zinc-50 border-b border-zinc-200 text-xs uppercase text-zinc-500 font-bold">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">⇅</th>
                                <th class="px-4 py-3 w-[40%]">Replace Text</th>
                                <th class="px-4 py-3 w-[45%]">Gambar Varian</th>
                                <th class="px-4 py-3 w-[15%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-container" class="divide-y divide-zinc-100">
                            @foreach($replaceTemplate->items as $index => $item)
                                <tr class="item-row bg-white hover:bg-zinc-50 transition-colors">
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    
                                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="text" name="items[{{ $index }}][replace_text]" value="{{ $item->replace_text }}" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" required>
                                    </td>
                                    <td class="px-4 py-3 align-top min-w-[250px]">
                                        <div class="flex flex-col gap-2 w-full">
                                            <select name="items[{{ $index }}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm image-type-select py-2 px-3 bg-zinc-50 focus:ring-zinc-900 focus:border-zinc-900" onchange="toggleImageType(this, {{ $index }})">
                                                <option value="none" {{ $item->image_type == 'none' ? 'selected' : '' }}>Kosong / Default 📷</option>
                                                <option value="file" {{ $item->image_type == 'file' ? 'selected' : '' }}>Upload File dari Komputer</option>
                                                <option value="url" {{ $item->image_type == 'url' ? 'selected' : '' }}>Pakai Link URL</option>
                                            </select>
                                            
                                            @if($item->image_type == 'file' && $item->image)
                                                <div class="mt-1 flex items-center gap-2">
                                                    <img src="{{ Storage::url($item->image) }}" class="h-8 w-12 object-cover rounded border border-zinc-200">
                                                    <span class="text-xs text-zinc-500">Gambar Terupload</span>
                                                </div>
                                            @endif

                                            <input type="file" name="items[{{ $index }}][image_file]" class="w-full form-control text-sm image-input-file {{ $item->image_type == 'file' ? '' : 'hidden' }}" accept="image/*">
                                            <input type="url" name="items[{{ $index }}][image_url]" value="{{ $item->image_type == 'url' ? $item->image : '' }}" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url {{ $item->image_type == 'url' ? '' : 'hidden' }} py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" placeholder="https://...">
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center align-top pt-4">
                                        <button type="button" onclick="removeItem(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors mx-auto flex justify-center items-center" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="addItem()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-zinc-100 border border-zinc-300 text-zinc-800 text-sm font-bold rounded-xl hover:bg-zinc-200 transition-colors shadow-sm active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Baris Varian
                    </button>
                </div>
            </div>

            <div class="p-6 border-t border-zinc-200 bg-zinc-50/50">
                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center py-3.5 px-8 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-zinc-900 hover:bg-zinc-800 transition-all duration-300 active:scale-95">
                    Update Template
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = {{ $replaceTemplate->items->count() }};

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('items-container');
            new Sortable(container, {
                handle: '.drag-handle', animation: 150, ghostClass: 'bg-zinc-100', 
                onEnd: function () { reindexItems(); }
            });
        });

        function addItem() {
            const container = document.getElementById('items-container');
            const html = `
                <tr class="item-row bg-white hover:bg-zinc-50 transition-colors">
                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input type="text" name="items[${itemIndex}][replace_text]" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" placeholder="Ex: Basic" required>
                    </td>
                    <td class="px-4 py-3 align-top min-w-[250px]">
                        <div class="flex flex-col gap-2 w-full">
                            <select name="items[${itemIndex}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm image-type-select py-2 px-3 bg-zinc-50 focus:ring-zinc-900 focus:border-zinc-900" onchange="toggleImageType(this, ${itemIndex})">
                                <option value="none" selected>Kosong / Default 📷</option>
                                <option value="file">Upload File dari Komputer</option>
                                <option value="url">Pakai Link URL</option>
                            </select>
                            <input type="file" name="items[${itemIndex}][image_file]" class="w-full form-control text-sm image-input-file hidden" accept="image/*">
                            <input type="url" name="items[${itemIndex}][image_url]" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url hidden py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" placeholder="https://...">
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center align-top pt-4">
                        <button type="button" onclick="removeItem(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors mx-auto flex justify-center items-center" title="Hapus Baris">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>`;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
            reindexItems();
        }

        function toggleImageType(selectElement, index) {
            const row = selectElement.closest('.item-row');
            const fileInput = row.querySelector('.image-input-file');
            const urlInput = row.querySelector('.image-input-url');
            if (selectElement.value === 'file') {
                fileInput.classList.remove('hidden'); urlInput.classList.add('hidden'); urlInput.value = ''; 
            } else if (selectElement.value === 'url') {
                urlInput.classList.remove('hidden'); fileInput.classList.add('hidden'); fileInput.value = ''; 
            } else {
                fileInput.classList.add('hidden'); urlInput.classList.add('hidden'); fileInput.value = ''; urlInput.value = '';
            }
        }

        function removeItem(button) {
            const tbody = document.getElementById('items-container');
            if (tbody.children.length === 1) { alert("Minimal harus ada 1 varian!"); return; }
            if(confirm('Hapus baris varian ini?')) {
                button.closest('.item-row').remove();
                reindexItems(); 
            }
        }

        function reindexItems() {
            document.querySelectorAll('#items-container .item-row').forEach((row, newIndex) => {
                row.querySelectorAll('input, select').forEach(input => {
                    if (input.name) input.name = input.name.replace(/items\[\d+\]/, `items[${newIndex}]`);
                });
                const select = row.querySelector('.image-type-select');
                if(select) select.setAttribute('onchange', `toggleImageType(this, ${newIndex})`);
            });
        }
    </script>
</x-admin-layout>