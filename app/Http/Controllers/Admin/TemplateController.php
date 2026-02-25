<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScriptTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = ScriptTemplate::latest()->paginate(10);
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        // Mengarahkan ke form pembuatan template
        return view('admin.templates.create'); 
    }

    public function edit(ScriptTemplate $template)
    {
        // Mengarahkan ke form edit template dengan membawa data template lama
        return view('admin.templates.edit', compact('template'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:script_templates,name',
            'content' => 'required|string',
        ]);

        ScriptTemplate::create([
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.templates.index')->with('success', 'Template baru berhasil ditambahkan!');
    }

    public function update(Request $request, ScriptTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:script_templates,name,' . $template->id,
            'content' => 'required|string',
        ]);

        $template->update([
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui!');
    }

    public function destroy(ScriptTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil dihapus!');
    }
}