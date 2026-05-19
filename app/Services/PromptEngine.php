<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\PromptTemplate;
use App\Models\LegalAidProvider;
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

        // 2.5 DYNAMIC LOCAL LEGAL AID PROVIDERS LOOKUP
        $localProviders = $this->findLocalProviders($question);
        if ($localProviders->isNotEmpty()) {
            $providersText = $resolvedLanguage === 'sw' 
                ? "\n\nWATOA HUDUMA WA KARIBU WA MSAADA WA KISHERIA WALIOPATIKANA (Kampeni ya Msaada wa Kisheria ya Mama Samia):\n"
                : "\n\nNEARBY REGISTERED LEGAL AID PROVIDERS FOUND (Mama Samia Legal Aid Campaign):\n";
                
            foreach ($localProviders as $provider) {
                if ($resolvedLanguage === 'sw') {
                    $providersText .= "- **{$provider->name}** (Mkoa: {$provider->region}, Wilaya: {$provider->district}):\n" .
                                      "  * Eneo/Anwani: {$provider->location}\n" .
                                      ($provider->phone ? "  * Simu: {$provider->phone}\n" : "") .
                                      ($provider->email ? "  * Barua pepe: {$provider->email}\n" : "");
                } else {
                    $providersText .= "- **{$provider->name}** (Region: {$provider->region}, District: {$provider->district}):\n" .
                                      "  * Location/Address: {$provider->location}\n" .
                                      ($provider->phone ? "  * Phone: {$provider->phone}\n" : "") .
                                      ($provider->email ? "  * Email: {$provider->email}\n" : "");
                }
            }
            $contextDisplay .= $providersText;
        }

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
        $govFactSheet = $lang === 'sw' 
            ? "\n\nMAELEZO RASMI YA VIONGOZI NA MAWASILIANO YA KISHERIA TANZANIA (Tumia haya pekee kujibu maswali ya viongozi au mawasiliano kwa usahihi kabisa):\n" .
              "- Mwanasheria Mkuu wa Serikali (Attorney General): Mhe. Hamza S. Johari (Aliteuliwa na Rais Samia Suluhu Hassan mnamo Novemba 2025).\n" .
              "- Jaji Mkuu wa Tanzania (Chief Justice): Mhe. Prof. Ibrahim Hamis Juma.\n" .
              "- Waziri wa Katiba na Sheria (MoCLA): Mhe. Dkt. Juma Zuberi Homera (Aliteuliwa mnamo Novemba 2025).\n" .
              "- Naibu Mwanasheria Mkuu (Deputy Attorney General): Mhe. Dkt. Ally Possi.\n" .
              "- Wakili Mkuu wa Serikali (Solicitor General): Mhe. Dkt. Boniface Luhende.\n" .
              "- Anwani ya Ofisi ya Mwanasheria Mkuu wa Serikali (OAG): Mtumba Government City (Mji wa Serikali Mtumba), S.L.P. 11492, Dodoma. Simu: +255 26 296 3647. Barua pepe: info@oag.go.tz. (Makao Makuu yamehamia Dodoma)."
            : "\n\nOFFICIAL TANZANIAN LEGAL LEADERS & CONTACT INFORMATION (Use this to answer related queries with 100% accuracy):\n" .
              "- Attorney General: Hon. Hamza S. Johari (Appointed by President Samia Suluhu Hassan in November 2025).\n" .
              "- Chief Justice of Tanzania: Hon. Prof. Ibrahim Hamis Juma.\n" .
              "- Minister for Constitutional and Legal Affairs (MoCLA): Hon. Dr. Juma Zuberi Homera (Appointed in November 2025).\n" .
              "- Deputy Attorney General: Hon. Dr. Ally Possi.\n" .
              "- Solicitor General: Hon. Dr. Boniface Luhende.\n" .
              "- Office of the Attorney General (OAG) Address: Mtumba Government City, P.O. Box 11492, Dodoma. Phone: +255 26 296 3647. Email: info@oag.go.tz. (Headquarters moved to Dodoma).";

        $baseContext = $context ?: ($lang === 'sw' 
            ? "Muktadha maalum wa kisheria haupatikani. Tumia maarifa yako ya jumla ya kisheria kulingana na sheria na katiba ya Tanzania." 
            : "No specific legal context found. Use your general legal knowledge based on the laws and constitution of Tanzania.");

        return $baseContext . $govFactSheet;
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
                   "- USIWEKE salamu wala maongezi yasiyo na maana. Hakikisha unatoa muktadha wa sheria za Tanzania pekee.\n" .
                   "- LAZIMA uhitimishe jibu lako kwa kuonyesha kuwa huduma hii ni kuunga mkono Kampeni ya Msaada wa Kisheria ya Mama Samia (Samia Legal Aid Campaign).\n" .
                   "- ANGALIZO LA KISHERIA: LAZIMA uhitimishe kwa kuweka Angalizo kuwa maelezo haya ni ya kielimu pekee na si ushauri wa kisheria wa kitaalamu.\n" .
                   "- IKIWA mwananchi ametaja mahali alipo (kama vile mkoa, wilaya, kata au mtaa, mfano Sinza Mori au Kahama Shinyanga) na anahitaji msaada wa kisheria: LAZIMA utambue eneo lake na kutoa kipaumbele cha kwanza kabisa kwa 'WATOA HUDUMA WA KARIBU' waliotajwa kwenye muktadha (context) kuwa wako karibu naye. Taja majina yao, anwani zao, na simu/barua pepe zao waziwazi mwanzoni mwa jibu lako.\n" .
                   "- IKIWA hakuna watoa huduma wa karibu waliotajwa kwenye muktadha au mwananchi anauliza anwani za kitaifa kwa ujumla, utataja anwani hizi za kitaifa za msaada wa ziada:\n" .
                   "  * Ofisi za Msaada wa Kisheria za Mama Samia (Wizara ya Katiba na Sheria - MoCLA): Dodoma (Mji wa Serikali Mtumba) au madawati yao ya mikoa.\n" .
                   "  * TLS (Tanganyika Law Society): Regent Estate, Dar es Salaam, Simu: +255 784 329 071.\n" .
                   "  * LHRC (Kituo cha Sheria na Haki za Binadamu): Simu ya Bure (Toll-Free): 0800 750 075.\n" .
                   "- USIWEKE anwani hizi kwenye majibu ya kawaida ya kirafiki usipoulizwa au isipokuwa ni lazima kabisa.";
        }

        return "STRICT CONSTRAINTS:\n" .
               "- YOU MUST detect the exact language of the citizen's question and respond entirely in that SAME language (English or Swahili).\n" .
               "- Respond briefly and completely (Max {$maxWords} words).\n" .
               "- DO NOT include greetings or extra conversation. Ensure you provide Tanzanian legal context only.\n" .
               "- YOU MUST conclude your response by indicating that this service is a supportive measure for the Samia Legal Aid Campaign.\n" .
               "- LEGAL DISCLAIMER: YOU MUST conclude with a Disclaimer indicating that this content is for educational purposes only and does not constitute professional legal advice.\n" .
               "- IF the citizen specifies their location (e.g. ward, district, region like Sinza Mori or Kahama Shinyanga) and requests legal aid: YOU MUST identify their location and prioritize any 'NEARBY REGISTERED LEGAL AID PROVIDERS' listed in the context at the beginning of your response, providing their name, exact address, and contact details.\n" .
               "- IF no specific nearby providers are available in the context, or if the citizen asks for general national offices, provide these official assistance contacts for further support:\n" .
               "  * Samia Legal Aid Campaign (Ministry of Constitutional and Legal Affairs - MoCLA): Dodoma Government City (Mtumba) or their regional help desks.\n" .
               "  * TLS (Tanganyika Law Society): Regent Estate, Dar es Salaam, Phone: +255 784 329 071.\n" .
               "  * LHRC (Legal and Human Rights Centre): Toll-Free Helpline: 0800 750 075.\n" .
               "- Do not include these contacts in ordinary educational responses unless requested or highly necessary.";
    }

    /**
     * Dynamically finds local legal aid providers based on the user's question.
     */
    private function findLocalProviders(string $question): \Illuminate\Database\Eloquent\Collection
    {
        $words = array_filter(
            explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $question))),
            fn($word) => strlen($word) > 3
        );

        if (empty($words)) {
            return collect();
        }

        // Search the database for matching providers
        $query = LegalAidProvider::query();
        
        $query->where(function($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('region', 'LIKE', '%' . $word . '%')
                  ->orWhere('district', 'LIKE', '%' . $word . '%')
                  ->orWhere('location', 'LIKE', '%' . $word . '%')
                  ->orWhere('name', 'LIKE', '%' . $word . '%');
            }
        });

        return $query->take(5)->get();
    }
}
