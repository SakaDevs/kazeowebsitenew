<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptLink extends Model
{
    protected $guarded = ['id'];

    // Fitur sakti untuk meng-update 'updated_at' di tabel script setiap kali link diubah
    protected $touches = ['script'];
    protected $fillable = ['script_id', 'replace_name', 'url', 'image'];

    public function script()
    {
        return $this->belongsTo(Script::class);
    }
}