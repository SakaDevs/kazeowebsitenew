<?php

use App\Models\Script; 
use App\Models\Category; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ScriptCommentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ScriptController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\CommunityController as AdminCommunityController;
use App\Http\Controllers\Admin\ReplaceTemplateController;

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


// --- PERBAIKAN DI SINI ---
// Pisahkan proses load relasi sebelum dikirim ke view
Route::get('/script/{script:slug}', function (Script $script) {
    $script->timestamps = false;
    $script->increment('views'); 
    
    // Load relasi 
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
Route::post('/script/{script}/comment', [ScriptCommentController::class, 'store'])
    ->name('script.comment.store')
    ->middleware('auth');

Route::delete('/comment/{comment}', [ScriptCommentController::class, 'destroy'])
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


// --- GRUP ADMIN ---
Route::prefix('admin')->middleware(['auth', 'super_admin'])->name('admin.')->group(function () {
    
    Route::get('/', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');
    
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('scripts', ScriptController::class)->except(['show']);
    Route::resource('templates', TemplateController::class)->except(['show']);
    
    Route::get('communities', [AdminCommunityController::class, 'index'])->name('communities.index');
    Route::patch('communities/{community}/pin', [AdminCommunityController::class, 'togglePin'])->name('communities.pin');
    Route::delete('communities/{community}', [AdminCommunityController::class, 'destroy'])->name('communities.destroy');
    
    // Ini rute yang error tadi. Ditulis dengan cara yang lebih bersih.
    Route::resource('replace_templates', ReplaceTemplateController::class)->except(['show']);
  
});


// --- KATEGORI ---
Route::get('/categories', function () {
    $categories = Category::orderBy('name', 'asc')->get();
    return view('categories', compact('categories'));
})->name('categories.index');

Route::get('/category/{category:slug}', function (Category $category) {
    $scripts = $category->scripts()->with(['category', 'user'])->latest()->paginate(12);
    return view('category-scripts', compact('category', 'scripts'));
})->name('categories.show');


// --- PENCARIAN ---
Route::get('/search', function () {
    $query = request('q');

    if (empty($query)) {
        return redirect('/');
    }

    $scripts = Script::with(['category', 'user'])
        ->where('title', 'LIKE', "%{$query}%")
        ->orWhere('slug', 'LIKE', "%{$query}%")
        ->latest()
        ->paginate(12);

    $scripts->appends(['q' => $query]);

    return view('search', compact('scripts', 'query'));
})->name('search');


// --- KOMUNITAS ---
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

Route::middleware('auth')->group(function () {
    Route::get('/community/create', [CommunityController::class, 'create'])->name('community.create');
    Route::post('/community', [CommunityController::class, 'store'])->name('community.store');
    Route::post('/community/{community}/like', [CommunityController::class, 'toggleLike'])->name('community.like');
    Route::post('/community/{community}/comment', [CommunityController::class, 'storeComment'])->name('community.comment');
    Route::post('/community/{community}/pin', [CommunityController::class, 'togglePin'])->name('community.pin');
    Route::delete('/community/{community}', [CommunityController::class, 'destroy'])->name('community.destroy');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        $myScripts = Script::with('category')->where('user_id', $user->id)->latest()->get();
        $myPosts = \App\Models\Community::where('user_id', $user->id)->latest()->get();
        
        return view('dashboard', compact('user', 'myScripts', 'myPosts'));
    })->name('user.dashboard'); // Ganti nama route agar tidak bentrok dengan admin.dashboard
});


Route::get('/social', function () {
    return view('social');
})->name('social');


// --- GLOBAL DOWNLOAD LOGGER ---
Route::post('/log-download', function (Request $request) {
    $user = auth()->check() ? auth()->user()->name : 'Seseorang';
    
    $data = [
        'user' => $user,
        'script' => $request->script,
        'id' => uniqid(), 
    ];
    
    Cache::put('latest_global_download', $data, now()->addMinutes(1));
    return response()->json(['status' => 'ok']);
})->name('log.download');

Route::get('/check-download', function () {
    return response()->json(Cache::get('latest_global_download'));
})->name('check.download');