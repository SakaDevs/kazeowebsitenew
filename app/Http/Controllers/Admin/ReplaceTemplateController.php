<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReplaceTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReplaceTemplateController extends Controller
{
    public function index(Request $request)
    {
        // Load relasi items agar bisa dihitung jumlahnya di tabel
        $query = ReplaceTemplate::with('items')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('items', function($q) use ($search) {
                      $q->where('replace_text', 'like', '%' . $search . '%');
                  });
        }

        $templates = $query->paginate(10)->withQueryString();
        return view('admin.replace_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.replace_templates.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Array
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.replace_text' => 'required|string|max:255',
            'items.*.image_type' => 'required|in:file,url,none',
            'items.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'items.*.image_url' => 'nullable|url',
        ]);

        // 2. Buat Template Utama (Grup/Hero)
        $template = ReplaceTemplate::create([
            'name' => $request->name,
        ]);

        // 3. Looping dan Buat Baris Variannya
        foreach ($request->items as $index => $itemData) {
            $imageType = $itemData['image_type'];
            $imagePath = null;

            if ($imageType === 'file' && $request->hasFile("items.{$index}.image_file")) {
                $imagePath = $request->file("items.{$index}.image_file")->store('replace_templates', 'public');
            } elseif ($imageType === 'url') {
                $imagePath = $itemData['image_url'] ?? null;
            }

            // Simpan ke tabel replace_template_items
            $template->items()->create([
                'replace_text' => $itemData['replace_text'],
                'image_type' => $imageType,
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('admin.replace_templates.index')->with('success', 'Template berhasil ditambahkan!');
    }

    public function edit(ReplaceTemplate $replaceTemplate)
    {
        $replaceTemplate->load('items');
        return view('admin.replace_templates.edit', compact('replaceTemplate'));
    }

    public function update(Request $request, ReplaceTemplate $replaceTemplate)
    {
        // 1. Validasi Update
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:replace_template_items,id',
            'items.*.replace_text' => 'required|string|max:255',
            'items.*.image_type' => 'required|in:file,url,none',
            'items.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'items.*.image_url' => 'nullable|url',
        ]);

        // 2. Update Nama Grup
        $replaceTemplate->update([
            'name' => $request->name,
        ]);

        // 3. Proses Varian (Tambah, Edit, Hapus)
        $submittedItemIds = [];

        foreach ($request->items as $index => $itemData) {
            $itemId = $itemData['id'] ?? null;
            $existingItem = $itemId ? \App\Models\ReplaceTemplateItem::find($itemId) : null;
            
            $imageType = $itemData['image_type'];
            $imagePath = $existingItem ? $existingItem->image : null;

            // Logika upload/ganti/hapus gambar
            if ($imageType === 'file' && $request->hasFile("items.{$index}.image_file")) {
                if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://']) && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file("items.{$index}.image_file")->store('replace_templates', 'public');
            } elseif ($imageType === 'url') {
                if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://']) && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $itemData['image_url'] ?? null;
            } elseif ($imageType === 'none') {
                if ($imagePath && !Str::startsWith($imagePath, ['http://', 'https://']) && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = null;
            }

            if ($existingItem) {
                // Update varian lama
                $existingItem->update([
                    'replace_text' => $itemData['replace_text'],
                    'image_type' => $imageType,
                    'image' => $imagePath,
                ]);
                $submittedItemIds[] = $existingItem->id;
            } else {
                // Tambah varian baru
                $newItem = $replaceTemplate->items()->create([
                    'replace_text' => $itemData['replace_text'],
                    'image_type' => $imageType,
                    'image' => $imagePath,
                ]);
                $submittedItemIds[] = $newItem->id;
            }
        }

        // 4. Hapus varian yang dihilangkan oleh user saat edit
        $itemsToDelete = $replaceTemplate->items()->whereNotIn('id', $submittedItemIds)->get();
        foreach ($itemsToDelete as $itemToDelete) {
            if ($itemToDelete->image && !Str::startsWith($itemToDelete->image, ['http://', 'https://']) && Storage::disk('public')->exists($itemToDelete->image)) {
                Storage::disk('public')->delete($itemToDelete->image);
            }
            $itemToDelete->delete();
        }

        return redirect()->route('admin.replace_templates.index')->with('success', 'Template berhasil diperbarui!');
    }

    public function destroy(ReplaceTemplate $replaceTemplate)
    {
        // Bersihkan gambar fisik dari storage sebelum menghapus data dari DB
        foreach ($replaceTemplate->items as $item) {
            if ($item->image && !Str::startsWith($item->image, ['http://', 'https://']) && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
        }
        
        $replaceTemplate->delete();
        return redirect()->route('admin.replace_templates.index')->with('success', 'Template berhasil dihapus!');
    }
}