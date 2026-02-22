<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // Menampilkan semua postingan di halaman komunitas
    public function index(Request $request)
    {
        // 1. Siapkan kerangka query
        $query = Community::with(['user', 'comments.user', 'reactions'])
            ->orderByDesc('is_pinned')
            ->latest();

        // 2. Lakukan Filter jika diklik
        if ($request->has('category') && $request->category !== 'Terbaru') {
            $query->where('category', $request->category);
        }

        // 3. Eksekusi! (Pastikan tidak ada kode $posts = ... lagi selain baris ini)
        $posts = $query->get();
            
        return view('community', compact('posts'));
    }

    // Menampilkan halaman form buat postingan baru
    public function create()
    {
        return view('community-create');
    }

    // Menyimpan postingan baru ke database
    public function store(Request $request)
{
    // 1. Validasi input dari form
    $request->validate([
        'title' => 'required|string|max:255',
        'body' => 'required|string',
        'category' => 'required|string',
    ]);

    // 2. Simpan ke database (Pastikan title dan body dipanggil di sini!)
    Community::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'body' => $request->body,
        'category' => $request->category,
        'is_pinned' => false, // Default tidak di-pin
    ]);

    return redirect()->route('community.index')->with('success', 'Postingan berhasil dibuat!');
}

    // Fungsi Like / Unlike
    public function toggleLike(Community $community)
    {
        $existingLike = $community->reactions()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            $existingLike->delete(); // Jika sudah like, maka di-unlike
        } else {
            // JALAN PINTAS: Bikin objek reaksi manual agar is_like terisi
            $reaction = new \App\Models\Reaction(); 
            $reaction->user_id = auth()->id();
            $reaction->is_like = true; // Kita isi true (1) karena ini tombol Like (❤️)

            // Simpan reaksi ke postingan ini
            $community->reactions()->save($reaction);
        }

        return back();
    }

    // Fungsi Kirim Komentar
    public function storeComment(Request $request, Community $community)
    {
        $request->validate(['content' => 'required|string|max:500']);

        // JALAN PINTAS: Bikin objek komentar baru secara manual biar gak diblokir!
        $comment = new \App\Models\Comment(); 
        $comment->user_id = auth()->id();
        $comment->body = $request->content;

        // Simpan komentar yang sudah diisi penuh ke postingan ini
        $community->comments()->save($comment);

        return back()->with('success', 'Komentar terkirim!');
    }
    // Fungsi khusus Admin untuk Pin / Unpin postingan
    public function togglePin(Community $community)
    {
        // Pagar keamanan: Pastikan pakai ->role (bukan ->usertype) dan pakai trim()
        $userRole = trim(auth()->user()->role ?? '');
        
        if (!in_array($userRole, ['admin', 'super_admin', 'superadmin'])) {
            abort(403, 'AKSES DITOLAK! HANYA ADMIN YANG BISA MELAKUKAN PIN.');
        }

        // Ubah status
        $community->update([
            'is_pinned' => !$community->is_pinned
        ]);

        $status = $community->is_pinned ? 'disematkan ke atas' : 'dilepas dari sematan';
        return back()->with('success', "Postingan berhasil {$status}!");
    }
    public function destroy(Community $community)
    {
        $userRole = trim(auth()->user()->role ?? '');
        
        // Cek Keamanan: Pastikan hanya admin yang bisa mengeksekusi
        if (!in_array($userRole, ['admin', 'super_admin', 'superadmin'])) {
            abort(403, 'AKSES DITOLAK! HANYA ADMIN YANG BISA MENGHAPUS POSTINGAN.');
        }

        // Hapus postingan beserta seluruh reaksi & komentar yang terhubung ke postingan ini
        $community->delete();

        return back()->with('success', 'Postingan berhasil dihapus secara permanen!');
    }
}