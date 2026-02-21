<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Script;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ScriptController extends Controller
{
    public function index()
    {
        $scripts = Script::with(['category', 'user'])->latest()->paginate(10);
        return view('admin.scripts.index', compact('scripts'));
    }

    public function create()
    {
        // Ambil semua kategori untuk dimasukkan ke dropdown
        $categories = Category::all();
        return view('admin.scripts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Script Utama & Array Link
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            // Validasi Array Links
            'links' => 'required|array|min:1',
            'links.*.replace_name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
            'links.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Upload Gambar Thumbnail Utama (jika ada)
        $mainImagePath = null;
        if ($request->hasFile('image')) {
            $mainImagePath = $request->file('image')->store('scripts', 'public');
        }

        // 3. Simpan Tabel Utama (Script)
        $script = Script::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'description' => $request->description,
            // Tambahkan baris ini untuk memotong deskripsi secara otomatis:
            'short_story' => $request->description,
            'image' => $mainImagePath,
        ]);

        // 4. Proses Loop Array Links dan Gambar-gambarnya
        if ($request->has('links')) {
            foreach ($request->links as $index => $linkData) {
                
                $linkImagePath = null;
                // Cek apakah di baris index ini ada file gambar yang diupload
                if ($request->hasFile("links.{$index}.image")) {
                    $linkImagePath = $request->file("links.{$index}.image")->store('script_links', 'public');
                }

                // Masukkan ke tabel script_links
                $script->links()->create([
                    'replace_name' => $linkData['replace_name'],
                    'url' => $linkData['url'],
                    'image' => $linkImagePath,
                ]);
            }
        }

        return redirect()->route('admin.scripts.index')->with('success', 'Script dengan multiple links berhasil di-upload!');
    }

    public function edit(Script $script)
    {
        $categories = Category::all();
        // Wajib me-load relasi links agar bisa ditampilkan di form edit
        $script->load('links'); 
        return view('admin.scripts.edit', compact('script', 'categories'));
    }

    public function update(Request $request, Script $script)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'links' => 'required|array|min:1',
            'links.*.id' => 'nullable|exists:script_links,id', // ID link (kalau ada)
            'links.*.replace_name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
            'links.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            // 'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'short_story' => $request->description,
            'image' => $mainImagePath,
        ]);

        // 4. Proses Link Dinamis (Tambah, Edit, Hapus)
        $submittedLinkIds = []; // Untuk melacak link apa saja yang dikirim dari form

        if ($request->has('links')) {
            foreach ($request->links as $index => $linkData) {
                $linkId = $linkData['id'] ?? null;
                $existingLink = $linkId ? \App\Models\ScriptLink::find($linkId) : null;
                $linkImagePath = $existingLink ? $existingLink->image : null;

                // Jika baris ini upload gambar varian baru
                if ($request->hasFile("links.{$index}.image")) {
                    if ($linkImagePath && Storage::disk('public')->exists($linkImagePath)) {
                        Storage::disk('public')->delete($linkImagePath);
                    }
                    $linkImagePath = $request->file("links.{$index}.image")->store('script_links', 'public');
                }

                if ($existingLink) {
                    // Jika link sudah ada, update datanya
                    $existingLink->update([
                        'replace_name' => $linkData['replace_name'],
                        'url' => $linkData['url'],
                        'image' => $linkImagePath,
                    ]);
                    $submittedLinkIds[] = $existingLink->id;
                } else {
                    // Jika ini link baru (di-klik + dari form edit)
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
            // Bersihkan file gambarnya dari VPS sebelum dihapus dari database
            if ($linkToDelete->image && Storage::disk('public')->exists($linkToDelete->image)) {
                Storage::disk('public')->delete($linkToDelete->image);
            }
            $linkToDelete->delete();
        }
        $script->touch();
        return redirect()->route('admin.scripts.index')->with('success', 'Script dan seluruh variasi linknya berhasil diperbarui!');
    }

    public function destroy(Script $script)
    {
        if ($script->image && Storage::disk('public')->exists($script->image)) {
            Storage::disk('public')->delete($script->image);
        }
        $script->delete();

        return redirect()->route('admin.scripts.index')->with('success', 'Script berhasil dihapus!');
    }
}