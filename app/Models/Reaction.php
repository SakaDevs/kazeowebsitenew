<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['user_id', 'is_like'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fungsi ini membuat Reaction bisa menempel ke Community
    public function reactable()
    {
        return $this->morphTo();
    }
}