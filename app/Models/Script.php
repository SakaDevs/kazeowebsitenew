<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $guarded =['id'];
    protected $fillable = [
        'category_id', 'title', 'slug', 'image', 'short_story', 
        'links', 'views', 'published_at', 'user_id'
    ];

    // Karena links berbentuk object/JSON, kita beri tahu Laravel untuk otomatis mengubahnya jadi Array/Object saat dipanggil
    protected $casts = [
        'links' => 'array', 
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Polymorphic relasi ke komentar
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