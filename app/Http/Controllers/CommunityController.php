<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // 1. Menampilkan Halaman Utama Komunitas
    public function index(Request $request)
    {
        $query = Community::with(['user'])->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // 🔴 UBAH NAMANYA JADI $posts DI SINI
        $posts = $query->paginate(15);

        // 🔴 UBAH JUGA DI SINI JADI 'posts'
        return view('community', compact('posts'));
    }

    // 2. Menampilkan Form Create Postingan
    public function create()
    {
        // 🔴 FIX: Sesuaikan dengan nama filemu 'community-create.blade.php'
        return view('community-create'); 
    }

    // 3. Menyimpan Data ke Database
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
            'content' => $request->content,
        ]);

        return redirect()->route('community.index')->with('success', 'Postingan komunitasmu berhasil diterbitkan! 🚀');
    }

    // 4. Fitur Hapus Postingan
    public function destroy(Community $community)
    {
        if (auth()->id() === $community->user_id || in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            $community->delete();
            return redirect()->route('community.index')->with('success', 'Postingan berhasil dihapus!');
        }

        return abort(403, 'Anda tidak memiliki akses untuk menghapus postingan ini.');
    }

    // --- FITUR TAMBAHAN ---

    public function toggleLike(Community $community)
    {
        // Cek apakah user sudah pernah like postingan ini
        $existingReaction = $community->reactions()->where('user_id', auth()->id())->first();

        if ($existingReaction) {
            // Jika sudah ada, berarti user ingin UNLIKE (Hapus data)
            $existingReaction->delete();
        } else {
            // Jika belum ada, berarti user ingin LIKE (Buat data)
            $community->reactions()->create([
                'user_id' => auth()->id()
            ]);
        }
        
        return back();
    }

    // 2. Fitur Komentar Komunitas (FIX BUG 'BODY')
    public function storeComment(Request $request, Community $community)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $community->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->content, // 🔴 INI KUNCINYA: Ubah 'content' jadi 'body'
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
    public function togglePin(Community $community)
    {
        if (in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            $community->update(['is_pinned' => !$community->is_pinned]);
        }
        
        return back();
    }
}