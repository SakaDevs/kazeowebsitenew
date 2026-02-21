<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptTemplate extends Model
{
    protected $guarded = ['id'];

    public function create()
    {
        $categories = Category::all();
        $templates = ScriptTemplate::all(); // Panggil semua template
        return view('admin.scripts.create', compact('categories', 'templates'));
    }

    public function edit(Script $script)
    {
        $categories = Category::all();
        $templates = ScriptTemplate::all(); // Panggil semua template
        $script->load('links'); 
        return view('admin.scripts.edit', compact('script', 'categories', 'templates'));
    }
}