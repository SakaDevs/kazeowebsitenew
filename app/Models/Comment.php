<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fungsi ini membuat Comment bisa menempel ke Script ATAU Community
    public function commentable()
    {
        return $this->morphTo();
    }
}