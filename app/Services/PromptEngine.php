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
            ? "Muktadha maalum wa kisheria haupatikani. Tumia maarifa yako ya jumla ya kisheria kulingana na sheria na katiba ya Tanzania." 
            : "No specific legal context found. Use your general legal knowledge based on the laws and constitution of Tanzania.";
    }

    /**
     * Provides a default prompt structure per language.
     */
    private function getDefaultTemplate(string $lang): string
    {
        return $lang === 'sw' 
            ? "Wewe ni msaidizi wa kisheria nchini Tanzania. Muktadha wa kisheria: {context}. Swali la mwananchi: {user_input}" 
            : "You are a legal assistant in Tanzania. Legal context: {context}. Citizen Question: {user_input}";
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
            return "MASHARTI MUHIMU:\n" .
                   "- LAZIMA utambue lugha aliyotumia mwananchi na ujibu kwa lugha HIYO HIYO aliyouliza (Kiswahili kama ameuliza kwa Kiswahili, Kiingereza kama ameuliza kwa Kiingereza).\n" .
                   "- Jibu kwa ufupi na ukamilifu (Max maneno {$maxWords}).\n" .
                   "- USIWEKE salamu wala maongezi yasiyo na maana. Hakikisha unatoa muktadha wa sheria za Tanzania pekee. Angalizo: \"Maelezo haya ni ya kielimu pekee na si ushauri wa kisheria wa kitaalamu.\"\n" .
                   "- LAZIMA uhitimishe jibu lako kwa kuonyesha kuwa huduma hii ni kuunga mkono Kampeni ya Msaada wa Kisheria ya Mama Samia (Samia Legal Aid Campaign).\n" .
                   "- IKIWA mwananchi ameuliza moja kwa moja anwani za ofisi, simu za msaada, au kama suala lake ni la dharura sana/zito linalohitaji wakili wa ana kwa ana (kama vile ugomvi mkubwa wa ardhi, ukatili wa kijinsia, au ukamataji haramu): LAZIMA utaje anwani hizi kwa ajili ya msaada wa ziada:\n" .
                   "  * Ofisi za Msaada wa Kisheria za Mama Samia (Wizara ya Katiba na Sheria - MoCLA): Dodoma (Mji wa Serikali Mtumba) au madawati yao ya mikoa.\n" .
                   "  * TLS (Tanganyika Law Society): Regent Estate, Dar es Salaam, Simu: +255 784 329 071.\n" .
                   "  * LHRC (Kituo cha Sheria na Haki za Binadamu): Simu ya Bure (Toll-Free): 0800 750 075.\n" .
                   "  * USIWEKE anwani hizi kwenye majibu ya kawaida ya kirafiki usipoulizwa au isipokuwa ni lazima kabisa.";
        }

        return "STRICT CONSTRAINTS:\n" .
               "- YOU MUST detect the exact language of the citizen's question and respond entirely in that SAME language (English or Swahili).\n" .
               "- Respond briefly and completely (Max {$maxWords} words).\n" .
               "- DO NOT include greetings or extra conversation. Ensure you provide Tanzanian legal context only. Disclaimer: \"This content is for educational purposes only and does not constitute professional legal advice.\"\n" .
               "- YOU MUST conclude your response by indicating that this service is a supportive measure for the Samia Legal Aid Campaign.\n" .
               "- IF AND ONLY IF the citizen specifically asks for office addresses, contact numbers, or in critical/urgent situations requiring immediate real-world legal intervention (e.g. violent abuse, threat of physical eviction, active arrest): YOU MUST provide these official assistance contacts for further support:\n" .
               "  * Samia Legal Aid Campaign (Ministry of Constitutional and Legal Affairs - MoCLA): Dodoma Government City (Mtumba) or their regional help desks.\n" .
               "  * TLS (Tanganyika Law Society): Regent Estate, Dar es Salaam, Phone: +255 784 329 071.\n" .
               "  * LHRC (Legal and Human Rights Centre): Toll-Free Helpline: 0800 750 075.\n" .
               "  * Do not include these contacts in ordinary educational responses unless requested or highly necessary.";
    }
}
