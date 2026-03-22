<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplaceTemplate extends Model
{
    protected $guarded = [];

    // 1 Template punya Banyak Item
    public function items()
    {
        return $this->hasMany(ReplaceTemplateItem::class);
    }
}
