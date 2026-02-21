<?php
use App\Models\Script; 
use App\Models\Category; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
Route::get('/', function () {
    // Ambil 8 script terbaru
    $latestScripts = Script::with(['category', 'user'])
        ->latest()
        ->take(8)
        ->get();

    // Ambil 8 script secara acak (Random)
    $popularScripts = Script::with(['category', 'user'])
    ->orderByDesc('views')
        ->take(8)
        ->get();

    return view('welcome', compact('latestScripts', 'popularScripts'));
})->name('home');


Route::get('/script/{script:slug}', function (\App\Models\Script $script) {
    $script->increment('views'); 
    
    // Load relasi termasuk komentar (diurutkan dari yang terbaru) beserta data user-nya
    $script->load([
        'category', 
        'user', 
        'links', 
        'comments' => fn($query) => $query->latest(), 
        'comments.user'
    ]); 
    
    return view('script-detail', compact('script'));
})->name('script.show');

// Rute untuk Submit Komentar (Hanya bisa diakses kalau user sudah Login)
Route::post('/script/{script}/comment', [\App\Http\Controllers\ScriptCommentController::class, 'store'])
    ->name('script.comment.store')
    ->middleware('auth');

Route::delete('/comment/{comment}', [\App\Http\Controllers\ScriptCommentController::class, 'destroy'])
    ->name('script.comment.destroy')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/{provider}', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

require __DIR__.'/auth.php';

Route::prefix('admin')->middleware(['auth', 'super_admin'])->name('admin.')->group(function () {
    
    Route::get('/', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'show']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('scripts', \App\Http\Controllers\Admin\ScriptController::class)->except(['show']);
    Route::resource('templates', \App\Http\Controllers\Admin\TemplateController::class)->except(['show']);
    Route::get('communities', [\App\Http\Controllers\Admin\CommunityController::class, 'index'])->name('communities.index');
    Route::patch('communities/{community}/pin', [\App\Http\Controllers\Admin\CommunityController::class, 'togglePin'])->name('communities.pin');
    Route::delete('communities/{community}', [\App\Http\Controllers\Admin\CommunityController::class, 'destroy'])->name('communities.destroy');
 
  
});

Route::get('/categories', function () {
    // Ambil semua kategori, urutkan sesuai abjad A-Z
    $categories = Category::orderBy('name', 'asc')->get();
    return view('categories', compact('categories'));
})->name('categories.index');

// Rute untuk menampilkan Daftar Script berdasarkan Kategori yang diklik
Route::get('/category/{category:slug}', function (Category $category) {
    // Ambil script yang berhubungan dengan kategori ini, pakai fitur Pagination (12 card per halaman)
    $scripts = $category->scripts()->with(['category', 'user'])->latest()->paginate(12);
    return view('category-scripts', compact('category', 'scripts'));
})->name('categories.show');

// Rute untuk Fitur Pencarian (Search)
Route::get('/search', function () {
    // Gunakan fungsi helper bawaan request() yang kebal error namespace
    $query = request('q');

    // Jika user iseng menekan enter tanpa mengisi apa-apa, kembalikan ke Home
    if (empty($query)) {
        return redirect('/');
    }

    // Cari script berdasarkan Judul ATAU Slug yang mengandung kata kunci
    $scripts = \App\Models\Script::with(['category', 'user'])
        ->where('title', 'LIKE', "%{$query}%")
        ->orWhere('slug', 'LIKE', "%{$query}%")
        ->latest()
        ->paginate(12);

    // appends('q') sangat penting agar saat user klik "Halaman 2", kata kuncinya tidak hilang
    $scripts->appends(['q' => $query]);

    return view('search', compact('scripts', 'query'));
})->name('search');

// Rute Halaman Komunitas
use App\Http\Controllers\CommunityController;

// Rute Halaman Utama Komunitas
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

// Rute Form Buat Postingan (Hanya bisa diakses kalau sudah Login)
Route::middleware('auth')->group(function () {
    Route::get('/community/create', [CommunityController::class, 'create'])->name('community.create');
    Route::post('/community', [CommunityController::class, 'store'])->name('community.store');
    Route::post('/community/{community}/like', [CommunityController::class, 'toggleLike'])->name('community.like');
    Route::post('/community/{community}/comment', [CommunityController::class, 'storeComment'])->name('community.comment');
    Route::post('/community/{community}/pin', [App\Http\Controllers\CommunityController::class, 'togglePin'])->name('community.pin');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Ambil data milik user yang sedang login
        $myScripts = \App\Models\Script::with('category')->where('user_id', $user->id)->latest()->get();
        $myPosts = \App\Models\Community::where('user_id', $user->id)->latest()->get();
        
        return view('dashboard', compact('user', 'myScripts', 'myPosts'));
    })->name('dashboard');
    Route::delete('/community/{community}', [App\Http\Controllers\CommunityController::class, 'destroy'])->name('community.destroy');
});

Route::get('/social', function () {
    return view('social');
})->name('social');