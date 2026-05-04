<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // 1. Menampilkan Halaman Utama Komunitas (DENGAN FILTER & SORTING)
    public function index(Request $request)
    {
        // Gunakan with() untuk menghindari N+1 query (load user, reaksi, dan komentar sekaligus)
        $query = Community::with(['user', 'reactions', 'comments']);

        // A. LOGIKA FILTER KATEGORI
        $currentCategory = $request->get('category', 'Terbaru');
        
        // Jika kategori bukan 'Terbaru', filter berdasarkan kategori tersebut
        if ($currentCategory !== 'Terbaru') {
            $query->where('category', $currentCategory);
        }

        // B. LOGIKA SEARCH (Dikelompokkan agar tidak merusak filter kategori)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('body', 'like', '%' . $search . '%'); // Gunakan 'body' sesuai database
            });
        }

        // C. LOGIKA SORTING: Pinned dulu, baru terbaru
        $posts = $query->orderBy('is_pinned', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->withQueryString(); // Agar pagination tetap membawa filter kategori/search

        return view('community', compact('posts'));
    }

    public function create()
    {
        return view('community-create'); 
    }

    // 2. Menyimpan Data ke Database (FIX: Menggunakan kolom 'body')
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        Community::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'category' => $request->category,
            'body' => $request->content, // 🔴 DISESUAIKAN: Request 'content' masuk ke kolom 'body'
        ]);

        return redirect()->route('community.index')->with('success', 'Postingan komunitasmu berhasil diterbitkan! 🚀');
    }

    public function destroy(Community $community)
    {
        if (auth()->id() === $community->user_id || in_array(auth()->user()->role, ['admin', 'super_admin', 'superadmin'])) {
            $community->delete();
            return redirect()->route('community.index')->with('success', 'Postingan berhasil dihapus!');
        }

        return abort(403, 'Anda tidak memiliki akses untuk menghapus postingan ini.');
    }

    public function toggleLike(Community $community)
    {
        $existingReaction = $community->reactions()->where('user_id', auth()->id())->first();

        if ($existingReaction) {
            $existingReaction->delete();
        } else {
            $community->reactions()->create([
                'user_id' => auth()->id()
            ]);
        }
        
        return back();
    }

    public function storeComment(Request $request, Community $community)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $community->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->content, 
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function togglePin(Community $community)
    {
        if (in_array(auth()->user()->role, ['admin', 'super_admin', 'superadmin'])) {
            $community->update(['is_pinned' => !$community->is_pinned]);
            
            $status = $community->is_pinned ? 'disematkan' : 'lepas sematan';
            return back()->with('success', "Postingan berhasil $status!");
        }
        
        return abort(403);
    }
}