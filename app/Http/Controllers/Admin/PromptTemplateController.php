<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PromptTemplate;

class PromptTemplateController extends Controller
{
    public function index()
    {
        $templates = PromptTemplate::latest()->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:prompt_templates',
            'template' => 'required',
            'temperature' => 'required|numeric|min:0|max:2',
            'max_tokens' => 'required|integer|min:10',
            'tone' => 'required|string',
            'language' => 'required|in:en,sw',
        ]);

        PromptTemplate::create($data);

        return redirect()->back()->with('success', 'Template created.');
    }

    public function toggle(PromptTemplate $template)
    {
        $template->update(['is_active' => !$template->is_active]);
        
        // Ensure only one template is active per language if needed? 
        // For now just toggle.
        
        return redirect()->back()->with('success', 'Template status updated.');
    }
}
