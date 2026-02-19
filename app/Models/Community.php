<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = ['user_id', 'body', 'is_pinned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relasi ke komentar
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Polymorphic relasi ke like/dislike
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}