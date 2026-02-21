<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptComment extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function script()
    {
        return $this->belongsTo(Script::class);
    }
}