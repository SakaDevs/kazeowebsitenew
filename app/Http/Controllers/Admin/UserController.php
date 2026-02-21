<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 1. Menampilkan daftar semua user
    public function index()
    {
        // Ambil semua user, urutkan dari yang terbaru, dan buat pagination (10 data per halaman)
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. Menampilkan form edit role
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 3. Menyimpan perubahan role
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin,super_admin',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diperbarui!');
    }

    // 4. Menghapus user
    public function destroy(User $user)
    {
        // Cegah super admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}