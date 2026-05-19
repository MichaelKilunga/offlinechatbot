<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PromptEngine;
use App\Models\PromptTemplate;
use App\Models\Curriculum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromptEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_prompt_engine_injects_legal_disclaimers_and_rules()
    {
        // 1. Create a dummy prompt template
        PromptTemplate::create([
            'name' => 'Default Swahili',
            'language' => 'sw',
            'template' => 'Wewe ni msaidizi wa kisheria nchini Tanzania. Mwongozo: {context}. Swali: {user_input}',
            'is_active' => true,
        ]);

        // 2. Create curriculum entry for context lookup
        Curriculum::create([
            'title' => 'Haki ya Kukamatwa',
            'content' => 'Kila mwananchi ana haki ya kupewa sababu za kukamatwa kwake.',
            'summary' => 'Haki ya kukamatwa',
            'tags' => 'arrest, haki za msingi',
            'keywords' => ['kukamatwa', 'haki'],
            'language' => 'sw',
            'is_active' => true,
        ]);

        $promptEngine = new PromptEngine();
        $prompt = $promptEngine->build('Naomba kujua haki yangu wakati wa kukamatwa', 'sw');

        // Assert curriculum context injected
        $this->assertStringContainsString('Kila mwananchi ana haki ya kupewa sababu', $prompt);
        // Assert Swahili disclaimer instruction injected
        $this->assertStringContainsString('angalizo', strtolower($prompt));
        $this->assertStringContainsString('tanzania', strtolower($prompt));
    }
}
