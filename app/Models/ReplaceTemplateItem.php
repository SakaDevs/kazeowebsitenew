<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplaceTemplateItem extends Model
{
    protected $guarded = [];

    // Item ini milik siapa?
    public function template()
    {
        return $this->belongsTo(ReplaceTemplate::class, 'replace_template_id');
    }
}
