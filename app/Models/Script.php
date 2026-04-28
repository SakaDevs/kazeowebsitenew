<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    use HasFactory;

    // Hapus $guarded, kita gunakan $fillable saja agar lebih aman dan jelas
    protected $fillable = [
        'category_id', 
        'user_id',
        'title', 
        'slug', 
        'image', 
        'short_story', 
        'views', 
        'status', 
        'published_at'
    ];

    // 🔴 FIX: Beritahu Laravel bahwa published_at adalah waktu (datetime)
    protected $casts = [
        'published_at' => 'datetime', 
    ];

    // --- RELASI DATABASE ---

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(ScriptComment::class);
    }

    public function links()
    {
        return $this->hasMany(ScriptLink::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}