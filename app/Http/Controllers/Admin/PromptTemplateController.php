<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PromptTemplate;
use App\Models\AdminActivityLog;
use App\Services\PromptEngine;
use App\Services\AiService;

class PromptTemplateController extends Controller
{
    public function index()
    {
        $templates = PromptTemplate::latest()->get();
        $activeByLanguage = [
            'sw' => PromptTemplate::where('language', 'sw')->where('is_active', true)->first(),
            'en' => PromptTemplate::where('language', 'en')->where('is_active', true)->first(),
        ];
        return view('admin.templates.index', compact('templates', 'activeByLanguage'));
    }

    private function rules(?PromptTemplate $template = null): array
    {
        return [
            'name'        => 'required|string|max:255|unique:prompt_templates,name' . ($template ? ',' . $template->id : ''),
            'template'    => 'required|string',
            'temperature' => 'required|numeric|min:0|max:2',
            'max_tokens'  => 'required|integer|min:10|max:20000',
            'tone'        => 'required|string|max:60',
            'language'    => 'required|in:en,sw',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if (!str_contains($data['template'], '{user_input}')) {
            return back()->withInput()->with('error', 'The template must contain the {user_input} placeholder.');
        }

        // New templates start inactive so the live prompt never changes by accident.
        $data['is_active'] = false;
        $template = PromptTemplate::create($data);

        AdminActivityLog::record('template.created', "Created prompt template \"{$template->name}\"", $template);

        return redirect()->back()->with('success', 'Template created (inactive). Activate it when ready.');
    }

    public function edit(PromptTemplate $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, PromptTemplate $template)
    {
        $data = $request->validate($this->rules($template));

        if (!str_contains($data['template'], '{user_input}')) {
            return back()->withInput()->with('error', 'The template must contain the {user_input} placeholder.');
        }

        $changed = array_keys(array_filter($data, fn($v, $k) => (string) $template->{$k} !== (string) $v, ARRAY_FILTER_USE_BOTH));
        $template->update($data);

        AdminActivityLog::record('template.updated', "Updated prompt template \"{$template->name}\"", $template, ['changed' => $changed]);

        return redirect()->route('admin.templates.index')->with('success', 'Template updated.');
    }

    public function destroy(PromptTemplate $template)
    {
        AdminActivityLog::record('template.deleted', "Deleted prompt template \"{$template->name}\"", null, ['id' => $template->id]);
        $template->delete();
        return redirect()->route('admin.templates.index')->with('success', 'Template deleted.');
    }

    public function toggle(PromptTemplate $template)
    {
        $activating = !$template->is_active;

        if ($activating) {
            // Only one template may be live per language: the engine picks the first active one.
            PromptTemplate::where('language', $template->language)
                ->where('id', '!=', $template->id)
                ->update(['is_active' => false]);
        }

        $template->update(['is_active' => $activating]);

        $state = $activating ? 'activated' : 'deactivated';
        AdminActivityLog::record("template.{$state}", "Prompt template \"{$template->name}\" {$state} ({$template->language})", $template);

        return redirect()->back()->with('success', $activating
            ? "\"{$template->name}\" is now the live {$template->language} template."
            : "\"{$template->name}\" deactivated. The built-in default will be used for {$template->language} until another template is activated.");
    }

    /**
     * Prompt playground: shows exactly what the engine sends for a sample question.
     */
    public function preview(Request $request)
    {
        return view('admin.templates.preview', [
            'question' => $request->old('question', 'Nimekamatwa na polisi Sinza, nifanye nini?'),
            'language' => $request->old('language', 'sw'),
            'prompt'   => null,
            'reply'    => null,
        ]);
    }

    public function runPreview(Request $request, AiService $aiService)
    {
        $data = $request->validate([
            'question' => 'required|string|max:1000',
            'language' => 'required|in:sw,en',
            'send'     => 'nullable|in:0,1',
        ]);

        $engine = new PromptEngine();
        $prompt = $engine->build($data['question'], $data['language']);
        $reply = null;

        if (($data['send'] ?? '0') === '1') {
            $started = microtime(true);
            $result = $aiService->generateContextualResponse($data['question'], $data['language']);
            $reply = [
                'text'   => $result['text'] ?? '',
                'tokens' => $result['tokens'] ?? null,
                'model'  => $result['model'] ?? null,
                'ms'     => (int) round((microtime(true) - $started) * 1000),
            ];
            AdminActivityLog::record('template.test_sent', 'Sent a test question to the model from the prompt playground', null, [
                'language' => $data['language'],
                'tokens'   => $reply['tokens']['totalTokenCount'] ?? null,
            ]);
        }

        return view('admin.templates.preview', [
            'question' => $data['question'],
            'language' => $data['language'],
            'prompt'   => $prompt,
            'reply'    => $reply,
        ]);
    }
}
