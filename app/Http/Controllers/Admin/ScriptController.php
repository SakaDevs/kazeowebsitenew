<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Script;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\ScriptTemplate;

class ScriptController extends Controller
{
    public function index()
    {
        $scripts = Script::with(['category', 'user'])->latest()->paginate(10);
        return view('admin.scripts.index', compact('scripts'));
    }

    public function create()
    {
        // Ambil semua kategori dan template untuk dimasukkan ke form
        $categories = Category::all();
        $templates = ScriptTemplate::all(); 
        
        return view('admin.scripts.create', compact('categories', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            // Validasi Array Links yang baru untuk Drag & Drop dan Tipe Gambar
            'links' => 'required|array|min:1',
            'links.*.replace_name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
            'links.*.image_type' => 'required|in:file,url,none', 
            'links.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'links.*.image_url' => 'nullable|url',
        ]);

        $mainImagePath = $request->hasFile('image') ? $request->file('image')->store('scripts', 'public') : null;

        $script = Script::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'description' => $request->description,
            'short_story' => $request->description,
            'image' => $mainImagePath,
        ]);

        if ($request->has('links')) {
            foreach ($request->links as $index => $linkData) {
                $imageType = $linkData['image_type'] ?? 'none';
                $linkImagePath = null;

                // Logika Cerdas Pemilihan Gambar Varian (Store)
                if ($imageType === 'file' && $request->hasFile("links.{$index}.image_file")) {
                    $linkImagePath = $request->file("links.{$index}.image_file")->store('script_links', 'public');
                } elseif ($imageType === 'url') {
                    $linkImagePath = $linkData['image_url'] ?? null;
                }

                $script->links()->create([
                    'replace_name' => $linkData['replace_name'],
                    'url' => $linkData['url'],
                    'image' => $linkImagePath,
                ]);
            }
        }

        return redirect()->route('admin.scripts.index')->with('success', 'Script berhasil ditambahkan!');
    }

    public function edit(Script $script)
    {
        $categories = Category::all();
        $templates = ScriptTemplate::all(); 
        
        // Wajib me-load relasi links agar bisa ditampilkan di form edit
        $script->load('links'); 
        
        return view('admin.scripts.edit', compact('script', 'categories', 'templates'));
    }

    public function update(Request $request, Script $script)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            // Validasi Array Links yang baru untuk Drag & Drop dan Tipe Gambar
            'links' => 'required|array|min:1',
            'links.*.id' => 'nullable|exists:script_links,id', 
            'links.*.replace_name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
            'links.*.image_type' => 'required|in:file,url,none',
            'links.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'links.*.image_url' => 'nullable|url',
        ]);

        // 2. Update Thumbnail Utama (jika ada yang baru)
        $mainImagePath = $script->image; 
        if ($request->hasFile('image')) {
            if ($mainImagePath && Storage::disk('public')->exists($mainImagePath)) {
                Storage::disk('public')->delete($mainImagePath);
            }
            $mainImagePath = $request->file('image')->store('scripts', 'public');
        }

        // 3. Update Tabel Utama (Script)
        $script->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'short_story' => $request->description,
            'image' => $mainImagePath,
        ]);

        // 4. Proses Link Dinamis (Tambah, Edit, Hapus, Urutan)
        $submittedLinkIds = []; 

        if ($request->has('links')) {
            // Harus pakai $index biar deteksi upload filenya tepat sasaran!
            foreach ($request->links as $index => $linkData) {
                $linkId = $linkData['id'] ?? null;
                $existingLink = $linkId ? \App\Models\ScriptLink::find($linkId) : null;
                
                $imageType = $linkData['image_type'] ?? 'none';
                $linkImagePath = $existingLink ? $existingLink->image : null;

                // Logika Cerdas Pemilihan Gambar Varian (Update)
                if ($imageType === 'file' && $request->hasFile("links.{$index}.image_file")) {
                    // Hapus gambar lama jika bentuknya file lokal
                    if ($linkImagePath && !Str::startsWith($linkImagePath, ['http://', 'https://']) && Storage::disk('public')->exists($linkImagePath)) {
                        Storage::disk('public')->delete($linkImagePath);
                    }
                    $linkImagePath = $request->file("links.{$index}.image_file")->store('script_links', 'public');
                } 
                elseif ($imageType === 'url') {
                    // Hapus gambar lama jika bentuknya file lokal sebelum diganti URL
                    if ($linkImagePath && !Str::startsWith($linkImagePath, ['http://', 'https://']) && Storage::disk('public')->exists($linkImagePath)) {
                        Storage::disk('public')->delete($linkImagePath);
                    }
                    $linkImagePath = $linkData['image_url'] ?? null;
                } 
                elseif ($imageType === 'none') {
                    // Hapus gambar lama dan kosongkan
                    if ($linkImagePath && !Str::startsWith($linkImagePath, ['http://', 'https://']) && Storage::disk('public')->exists($linkImagePath)) {
                        Storage::disk('public')->delete($linkImagePath);
                    }
                    $linkImagePath = null;
                }

                if ($existingLink) {
                    $existingLink->update([
                        'replace_name' => $linkData['replace_name'],
                        'url' => $linkData['url'],
                        'image' => $linkImagePath,
                    ]);
                    $submittedLinkIds[] = $existingLink->id;
                } else {
                    $newLink = $script->links()->create([
                        'replace_name' => $linkData['replace_name'],
                        'url' => $linkData['url'],
                        'image' => $linkImagePath,
                    ]);
                    $submittedLinkIds[] = $newLink->id;
                }
            }
        }

        // 5. Hapus link yang hilang (User mengklik tombol 'X' di form)
        $linksToDelete = $script->links()->whereNotIn('id', $submittedLinkIds)->get();
        foreach ($linksToDelete as $linkToDelete) {
            // Bersihkan file HANYA JIKA itu file lokal (bukan URL http/https)
            if ($linkToDelete->image && !Str::startsWith($linkToDelete->image, ['http://', 'https://'])) {
                if (Storage::disk('public')->exists($linkToDelete->image)) {
                    Storage::disk('public')->delete($linkToDelete->image);
                }
            }
            $linkToDelete->delete();
        }
        
        $script->touch();
        return redirect()->route('admin.scripts.index')->with('success', 'Script dan seluruh variasi linknya berhasil diperbarui!');
    }

    public function destroy(Script $script)
    {
        // Hapus thumbnail utama
        if ($script->image && Storage::disk('public')->exists($script->image)) {
            Storage::disk('public')->delete($script->image);
        }
        
        // Hapus gambar varian JIKA bentuknya masih file lokal
        foreach ($script->links as $link) {
            if ($link->image && !Str::startsWith($link->image, ['http://', 'https://'])) {
                if (Storage::disk('public')->exists($link->image)) {
                    Storage::disk('public')->delete($link->image);
                }
            }
        }

        $script->delete();

        return redirect()->route('admin.scripts.index')->with('success', 'Script berhasil dihapus!');
    }
}