<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        // Ambil data komunitas, hitung jumlah komen & reaksi, lalu urutkan yang di-pin paling atas
        $communities = Community::with('user')
            ->withCount(['comments', 'reactions'])
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(15);

        return view('admin.communities.index', compact('communities'));
    }

    // Fungsi khusus untuk mengubah status Pin
    public function togglePin(Community $community)
    {
        $community->update([
            'is_pinned' => !$community->is_pinned
        ]);

        $status = $community->is_pinned ? 'di-Pin ke atas' : 'dilepas dari Pin';
        return back()->with('success', "Postingan berhasil {$status}!");
    }

    // Fungsi untuk menghapus postingan pelanggar
    public function destroy(Community $community)
    {
        $community->delete();
        return back()->with('success', 'Postingan komunitas berhasil dihapus permanen!');
    }

    
}