<x-admin-layout>
    <x-slot name="title">Add Replace Template</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <div class="mb-6">
        <a href="{{ route('admin.replace_templates.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors">
            <span>⬅️</span> Back to Templates
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden max-w-5xl">
        <form action="{{ route('admin.replace_templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 border-b border-zinc-200 bg-zinc-50/50">
                <label class="block text-sm font-bold text-zinc-700 mb-1.5">Nama Grup / Hero</label>
                <input type="text" name="name" required placeholder="Contoh: Chou, Frieren, dll..." class="block w-full max-w-md px-4 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="p-6">
                <h3 class="text-lg font-bold text-zinc-900 mb-4 border-b pb-2">Varian Replace & Gambar (Drag ⇅ untuk mengurutkan)</h3>
                
                <div class="border border-zinc-200 rounded-xl overflow-hidden bg-white shadow-sm overflow-x-auto">
                    <table class="w-full min-w-[800px] text-sm text-left whitespace-nowrap">
                        <thead class="bg-zinc-50 border-b border-zinc-200 text-xs uppercase text-zinc-500 font-bold">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">⇅</th>
                                <th class="px-4 py-3 w-[35%]">Replace Text</th>
                                <th class="px-4 py-3 w-[50%]">Gambar Varian</th>
                                <th class="px-4 py-3 w-[15%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-container" class="divide-y divide-zinc-100">
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
                    Simpan Template
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = 0;

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('items-container');
            new Sortable(container, {
                handle: '.drag-handle', 
                animation: 150,
                ghostClass: 'bg-zinc-100', 
                onEnd: function () { reindexItems(); }
            });
            addItem(); // Tambah baris pertama otomatis
        });

        // FUNGSI PREVIEW IMAGE (Realtime)
        function previewItemImage(input, type) {
            // Cari elemen row <tr> terdekat
            const row = input.closest('.item-row');
            // Cari container preview di dalam row tersebut
            const previewContainer = row.querySelector('.item-preview-container');
            const previewImg = row.querySelector('.item-preview-img');

            if (type === 'file') {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden'); // Munculkan preview
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    previewContainer.classList.add('hidden'); // Sembunyikan jika batal
                }
            } else if (type === 'url') {
                const url = input.value;
                if (url) {
                    previewImg.src = url;
                    previewContainer.classList.remove('hidden'); // Munculkan preview
                    // Handle jika URL gambar rusak/tidak valid
                    previewImg.onerror = function() {
                        previewContainer.classList.add('hidden');
                    };
                } else {
                    previewContainer.classList.add('hidden'); // Sembunyikan jika kosong
                }
            }
        }

        // Modifikasi toggleImageType untuk mereset preview saat tipe diganti
        function toggleImageType(selectElement, index) {
            const row = selectElement.closest('.item-row');
            const fileInput = row.querySelector('.image-input-file');
            const urlInput = row.querySelector('.image-input-url');
            const previewContainer = row.querySelector('.item-preview-container');
            
            // Sembunyikan preview setiap ganti tipe
            if(previewContainer) previewContainer.classList.add('hidden');

            if (selectElement.value === 'file') {
                fileInput.classList.remove('hidden'); urlInput.classList.add('hidden'); urlInput.value = ''; 
            } else if (selectElement.value === 'url') {
                urlInput.classList.remove('hidden'); fileInput.classList.add('hidden'); fileInput.value = ''; 
            } else {
                fileInput.classList.add('hidden'); urlInput.classList.add('hidden'); fileInput.value = ''; urlInput.value = '';
            }
        }

        function addItem() {
            const container = document.getElementById('items-container');
            // Menambahkan elemen preview di dalam HTML baris
            const html = `
                <tr class="item-row bg-white hover:bg-zinc-50 transition-colors">
                    <td class="px-4 py-3 text-center align-top cursor-move drag-handle text-zinc-400 hover:text-zinc-900 pt-5">
                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </td>
                    <td class="px-4 py-3 align-top">
                        <input type="text" name="items[${itemIndex}][replace_text]" class="w-full form-control rounded-lg border-zinc-300 text-sm py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" placeholder="Ex: Default" required>
                    </td>
                    <td class="px-4 py-3 align-top min-w-[300px]">
                        <div class="flex flex-col gap-2 w-full">
                            <select name="items[${itemIndex}][image_type]" class="w-full form-select rounded-lg border-zinc-300 text-sm image-type-select py-2 px-3 bg-zinc-50 focus:ring-zinc-900 focus:border-zinc-900" onchange="toggleImageType(this, ${itemIndex})">
                                <option value="none" selected>Kosong / Default 📷</option>
                                <option value="file">Upload File dari Komputer</option>
                                <option value="url">Pakai Link URL</option>
                            </select>
                            
                            <input type="file" name="items[${itemIndex}][image_file]" class="w-full form-control text-sm image-input-file hidden" accept="image/*" onchange="previewItemImage(this, 'file')">
                            
                            <input type="url" name="items[${itemIndex}][image_url]" class="w-full form-control rounded-lg border-zinc-300 text-sm image-input-url hidden py-2 px-3 focus:ring-zinc-900 focus:border-zinc-900" placeholder="https://..." oninput="previewItemImage(this, 'url')">
                            
                            <div class="item-preview-container hidden mt-1 flex items-center gap-2 bg-zinc-50 p-1.5 rounded-md border border-zinc-100">
                                <img src="" class="item-preview-img h-12 w-20 object-cover rounded border border-zinc-200 shadow-sm bg-white">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Preview</span>
                                    <span class="text-[9px] text-zinc-400 font-medium">Lokal / URL</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center align-top pt-4">
                        <button type="button" onclick="removeItem(this)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors mx-auto flex justify-center items-center" title="Hapus Baris">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
            reindexItems();
        }

        function removeItem(button) {
            const tbody = document.getElementById('items-container');
            if (tbody.children.length === 1) {
                alert("Minimal harus ada 1 varian!");
                return;
            }
            if(confirm('Hapus baris varian ini?')) {
                button.closest('.item-row').remove();
                reindexItems(); 
            }
        }

        function reindexItems() {
            document.querySelectorAll('#items-container .item-row').forEach((row, newIndex) => {
                row.querySelectorAll('input, select').forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/items\[\d+\]/, `items[${newIndex}]`);
                    }
                });
                const select = row.querySelector('.image-type-select');
                if(select) select.setAttribute('onchange', `toggleImageType(this, ${newIndex})`);
            });
        }
    </script>
</x-admin-layout>