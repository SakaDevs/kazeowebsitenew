<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Script;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\ScriptTemplate;
use App\Models\ReplaceTemplate;
use Carbon\Carbon; // Pastikan Carbon di-import untuk mengatur waktu

class ScriptController extends Controller
{
    public function index(Request $request)
    {
        $query = Script::with(['category', 'user'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', '%' . $search . '%');
        }
        $scripts = $query->paginate(10)->withQueryString(); 
        
        return view('admin.scripts.index', compact('scripts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $templates = ScriptTemplate::orderBy('name', 'asc')->get(); 
        $replaceTemplates = ReplaceTemplate::with('items')->orderBy('name', 'asc')->get(); 
        
        return view('admin.scripts.create', compact('categories', 'templates', 'replaceTemplates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:published,draft,scheduled',
            'published_at' => 'nullable|date',
            'links' => 'required|array|min:1',
            'links.*.replace_name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
            'links.*.image_type' => 'required|in:file,url,none', 
            'links.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'links.*.image_url' => 'nullable|url',
        ]);

        $mainImagePath = $request->hasFile('image') ? $request->file('image')->store('scripts', 'public') : null;

        // Logika Pengaturan Tanggal Tayang
        $publishedAt = null;
        if ($request->status === 'published') {
            $publishedAt = now(); 
        } elseif ($request->status === 'scheduled') {
            $publishedAt = Carbon::parse($request->published_at); 
        }

        // Simpan data dasar dulu
        $script = Script::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'description' => $request->description,
            'short_story' => $request->description,
            'image' => $mainImagePath,
        ]);

        // 🔴 TRIK BYPASS: Paksa simpan status dan tanggal secara manual!
        $script->status = $request->status;
        $script->published_at = $publishedAt;
        $script->save();

        if ($request->has('links')) {
            foreach ($request->links as $index => $linkData) {
                $imageType = $linkData['image_type'] ?? 'none';
                $linkImagePath = null;

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
        $categories = Category::orderBy('name', 'asc')->get();
        $templates = ScriptTemplate::orderBy('name', 'asc')->get(); 
        $replaceTemplates = ReplaceTemplate::with('items')->orderBy('name', 'asc')->get(); 
        
        $script->load('links'); 
        
        return view('admin.scripts.edit', compact('script', 'categories', 'templates', 'replaceTemplates'));
    }

    public function update(Request $request, Script $script)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:published,draft,scheduled',
            'published_at' => 'nullable|date',
            'links' => 'required|array|min:1',
            'links.*.id' => 'nullable|exists:script_links,id', 
            'links.*.replace_name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
            'links.*.image_type' => 'required|in:file,url,none',
            'links.*.image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'links.*.image_url' => 'nullable|url',
        ]);

        $mainImagePath = $script->image; 
        if ($request->hasFile('image')) {
            if ($mainImagePath && Storage::disk('public')->exists($mainImagePath)) {
                Storage::disk('public')->delete($mainImagePath);
            }
            $mainImagePath = $request->file('image')->store('scripts', 'public');
        }

        // Logika Update Pengaturan Tanggal Tayang
        $publishedAt = $script->published_at; 
        if ($request->status === 'published' && $script->status !== 'published') {
            $publishedAt = now(); 
        } elseif ($request->status === 'scheduled') {
            $publishedAt = Carbon::parse($request->published_at); 
        } elseif ($request->status === 'draft') {
            $publishedAt = null; 
        }

        // Update Tabel Utama
        $script->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'short_story' => $request->description,
            'image' => $mainImagePath,
        ]);

        // 🔴 TRIK BYPASS: Paksa update status dan tanggal secara manual!
        $script->status = $request->status;
        $script->published_at = $publishedAt;
        $script->save();

        $submittedLinkIds = []; 

        if ($request->has('links')) {
            foreach ($request->links as $index => $linkData) {
                $linkId = $linkData['id'] ?? null;
                $existingLink = $linkId ? \App\Models\ScriptLink::find($linkId) : null;
                
                $imageType = $linkData['image_type'] ?? 'none';
                $linkImagePath = $existingLink ? $existingLink->image : null;

                if ($imageType === 'file' && $request->hasFile("links.{$index}.image_file")) {
                    if ($linkImagePath && !Str::startsWith($linkImagePath, ['http://', 'https://']) && Storage::disk('public')->exists($linkImagePath)) {
                        Storage::disk('public')->delete($linkImagePath);
                    }
                    $linkImagePath = $request->file("links.{$index}.image_file")->store('script_links', 'public');
                } 
                elseif ($imageType === 'url') {
                    if ($linkImagePath && !Str::startsWith($linkImagePath, ['http://', 'https://']) && Storage::disk('public')->exists($linkImagePath)) {
                        Storage::disk('public')->delete($linkImagePath);
                    }
                    $linkImagePath = $linkData['image_url'] ?? null;
                } 
                elseif ($imageType === 'none') {
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

        $linksToDelete = $script->links()->whereNotIn('id', $submittedLinkIds)->get();
        foreach ($linksToDelete as $linkToDelete) {
            if ($linkToDelete->image && !Str::startsWith($linkToDelete->image, ['http://', 'https://'])) {
                if (Storage::disk('public')->exists($linkToDelete->image)) {
                    Storage::disk('public')->delete($linkToDelete->image);
                }
            }
            $linkToDelete->delete();
        }
        
        $script->touch();
        return redirect()->route('admin.scripts.index')->with('success', 'Script berhasil diperbarui!');
    }

    public function destroy(Script $script)
    {
        if ($script->image && Storage::disk('public')->exists($script->image)) {
            Storage::disk('public')->delete($script->image);
        }
        
        $links = $script->links()->get(); 
        
        foreach ($links as $link) {
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