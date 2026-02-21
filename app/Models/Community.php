<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Community extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk diisi (membuka gembok Mass Assignment)
    protected $guarded = ['id'];

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