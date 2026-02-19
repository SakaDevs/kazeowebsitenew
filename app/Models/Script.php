<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'image', 'short_story', 
        'links', 'views', 'published_at'
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
        return $this->morphMany(Comment::class, 'commentable');
    }
}