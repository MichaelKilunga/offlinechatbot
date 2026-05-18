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
     * Supported languages mapping.
     * To extend, simply add the ISO code and its display name or specific instructions.
     */
    private array $supportedLanguages = [
        'sw' => 'Swahili (Kiswahili)',
        'en' => 'English',
        // 'fr' => 'French', // Example of extension
    ];

    /**
     * Generates a fully formatted, AI-ready prompt string.
     * 
     * @param string $question The student's incoming query.
     * @param string|null $language The target language. If null, it will be detected.
     * @param string|null $manualContext Optional manual context override.
     * @return string
     */
    public function build(string $question, ?string $language = null, ?string $manualContext = null): string
    {
        // 1. LANGUAGE DETECTION & RESOLUTION
        $resolvedLanguage = $language ?: 'sw';
        
        // 2. RESOLVE CURRICULUM CONTEXT
        // Context is injected by matching query keywords against the 'curriculums' table.
        // FALLBACK: If no matching curriculum is found, it directs the AI to use general knowledge.
        $context = $manualContext ?? $this->autoFetchContext($question, $resolvedLanguage);
        $contextDisplay = $this->formatContext($context, $resolvedLanguage);

        // 3. LOAD ACTIVE TEMPLATE
        // Fetches the administrator-configured template for the specific language.
        $promptObj = PromptTemplate::where('language', $resolvedLanguage)
            ->where('is_active', true)
            ->first();

        // Base wrapper if no template is found in Database
        $templateStr = $promptObj?->template ?? $this->getDefaultTemplate($resolvedLanguage);

        // 4. APPLY STRICT CONSTRAINTS
        // Enforces the "Short, complete, focused, and no-chatter" requirements.
        $constraints = $this->getConstraints($resolvedLanguage);

        // 5. FINAL ASSEMBLY
        $finalPrompt = str_replace(
            ['{context}', '{user_input}'],
            [$contextDisplay, $question],
            $templateStr
        );

        return $finalPrompt . "\n\n" . $constraints;
    }



    /**
     * Formats the curriculum context or returns a fallback string.
     */
    private function formatContext(?string $context, string $lang): string
    {
        if ($context) return $context;

        return $lang === 'sw' 
            ? "Muktadha wa mtaala haupatikani. Tumia maarifa yako ya jumla ya kieleimu kulingana na mtaala wa Tanzania." 
            : "No specific curriculum context found. Use your general educational knowledge based on the Tanzanian curriculum.";
    }

    /**
     * Provides a default prompt structure per language.
     */
    private function getDefaultTemplate(string $lang): string
    {
        return $lang === 'sw' 
            ? "Wewe ni mwalimu msaidizi. Muktadha: {context}. Swali la mwanafunzi: {user_input}" 
            : "You are a teaching assistant. Context: {context}. Student Question: {user_input}";
    }

    /**
     * Scans the curriculum database for relevant educational material.
     */
    private function autoFetchContext(string $question, string $language): ?string
    {
        $keywords = array_filter(
            explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $question))),
            fn($word) => strlen($word) > 3
        );

        if (empty($keywords)) return null;

        $relevant = Curriculum::where('is_active', true)
            ->where('language', $language)
            ->get()
            ->filter(function($item) use ($keywords) {
                $itemKeywords = (array) $item->keywords;
                return count(array_intersect($keywords, $itemKeywords)) > 0;
            })
            ->sortByDesc(fn($item) => count(array_intersect($keywords, (array)$item->keywords)))
            ->take(1);

        if ($relevant->isEmpty()) return null;

        return $relevant->map(fn($c) => "[MADA: {$c->title}]\n{$c->content}")->first();
    }

    private function getConstraints(string $language): string
    {
        $maxWords = \App\Models\SystemSetting::where('key', 'ai_max_words')->value('value') ?? 320;

        if ($language === 'sw') {
            return "MASHARTI MUHIMU:\n- LAZIMA utambue lugha aliyotumia mwanafunzi na ujibu kwa lugha HIYO HIYO aliyouliza (Kiswahili kama ameuliza kwa Kiswahili, Kiingereza kama ameuliza kwa Kiingereza).\n- Jibu kwa ufupi na ukamilifu (Max maneno {$maxWords}).\n- USIWEKE salamu wala maongezi yasiyo ya kimasomo.";
        }

        return "STRICT CONSTRAINTS:\n- YOU MUST detect the exact language of the student's question and respond entirely in that SAME language (English or Swahili).\n- Respond briefly and completely (Max {$maxWords} words).\n- DO NOT include greetings or extra conversation.";
    }
}
