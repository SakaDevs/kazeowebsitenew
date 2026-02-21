<?php

namespace App\Http\Controllers;

use App\Models\Script;
use Illuminate\Http\Request;

class ScriptCommentController extends Controller
{
    public function store(Request $request, Script $script)
    {
        // Validasi inputan komentar (maksimal 1000 karakter)
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Simpan komentar ke database
        $script->comments()->create([
            'user_id' => auth()->id(), // Ambil ID user yang sedang login
            'content' => $request->content,
        ]);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Komentar berhasil dipublikasikan!');
    }

    public function destroy($id)
    {
        $comment = \App\Models\ScriptComment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}