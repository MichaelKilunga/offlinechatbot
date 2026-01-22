<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\PromptTemplate;
use Illuminate\Support\Str;

/**
 * PromptEngine
 * 
 * A cohesive system for building context-aware, low-latency prompts for SMS tutoring.
 * Injects curriculum data based on keyword proximity and enforces strict response formatting.
 */
class PromptEngine
{
    /**
     * Generates a fully formatted, AI-ready prompt string.
     * 
     * @param string $question The student's incoming query.
     * @param string $language The target language ('en' for English, 'sw' for Swahili).
     * @param string|null $manualContext Optional manual context override for specific flows.
     * @return string
     */
    public function build(string $question, string $language = 'sw', ?string $manualContext = null): string
    {
        // 1. RESOLVE CURRICULUM CONTEXT
        // Context is injected by matching query keywords against the 'curriculums' table.
        // FALLBACK: If no matching curriculum is found, it directs the AI to use general knowledge.
        $context = $manualContext ?? $this->autoFetchContext($question, $language);
        $contextDisplay = $context ?: ($language === 'sw' 
            ? "Muktadha wa mtaala haupatikani. Tumia maarifa yako ya jumla ya kieleimu." 
            : "No specific curriculum context found. Use your general educational knowledge.");

        // 2. LOAD ACTIVE TEMPLATE
        // Fetches the administrator-configured template for the specific language.
        $promptObj = PromptTemplate::where('language', $language)
            ->where('is_active', true)
            ->first();

        // Base wrapper if no template is found in Database
        $templateStr = $promptObj?->template ?? ($language === 'sw' 
            ? "Wewe ni mwalimu. Muktadha: {context}. Swali: {user_input}" 
            : "You are a tutor. Context: {context}. Question: {user_input}");

        // 3. APPLY STRICT CONSTRAINTS
        // Enforces the "Short, complete, focused, and no-chatter" requirements.
        $constraints = $this->getConstraints($language);

        // 4. ASSEMBLY
        $finalPrompt = str_replace(
            ['{context}', '{user_input}'],
            [$contextDisplay, $question],
            $templateStr
        );

        return $finalPrompt . "\n\n" . $constraints;
    }

    /**
     * Scans the curriculum database for relevant educational material.
     * Uses keyword intersection for fast matching against JSON keywords field.
     */
    private function autoFetchContext(string $question, string $language): ?string
    {
        // Extract search-ready keywords (lowercase, alphanumeric, length > 3)
        $keywords = array_filter(
            explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $question))),
            fn($word) => strlen($word) > 3
        );

        if (empty($keywords)) return null;

        // Fetch active curriculum matches
        $relevant = Curriculum::where('is_active', true)
            ->where('language', $language)
            ->get()
            ->filter(function($item) use ($keywords) {
                $itemKeywords = (array) $item->keywords;
                return count(array_intersect($keywords, $itemKeywords)) > 0;
            })
            ->sortByDesc(fn($item) => count(array_intersect($keywords, (array)$item->keywords)))
            ->take(1); // Keep it strictly focused on the most relevant topic

        if ($relevant->isEmpty()) return null;

        return $relevant->map(fn($c) => "[MADA: {$c->title}]\n{$c->content}")->first();
    }

    /**
     * Returns the strict formatting instructions to minimize SMS cost and maximize focus.
     */
    private function getConstraints(string $language): string
    {
        if ($language === 'sw') {
            return "MASHARTI MUHIMU:\n- Jibu kwa ufupi na ukamilifu (Upeo maneno 50).\n- USITUMIE salamu (mfano: 'Habari', 'Hujambo').\n- Lenga swali pekee bila maongezi yasiyo na lazima.\n- Jibu lazima liwe katika lugha ya Kiswahili pekee.";
        }

        return "STRICT CONSTRAINTS:\n- Respond briefly and completely (Max 50 words).\n- DO NOT use greetings (e.g., 'Hello', 'Hi', 'Student').\n- Focus strictly on the answer without extra chatter.\n- Response must be in English only.";
    }
}
