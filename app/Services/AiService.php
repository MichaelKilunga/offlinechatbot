<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\PromptEngine;

class AiService
{
    public function generateResponse(string $prompt): string
    {
        $result = $this->callGemini($prompt);
        return $result['text'];
    }

    public function generateContextualResponse(string $userQuery, string $language = 'sw'): array
    {
        $engine = new PromptEngine();
        $prompt = $engine->build($userQuery, $language);

        return $this->callGemini($prompt);
    }

    private function callGemini(string $prompt, float $temp = 0.7, int $maxTokens = 200): array
    {
        try {
            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                Log::critical('Gemini API Key missing.');
                return ['text' => 'System error: AI unavailable.', 'tokens' => null];
            }

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $apiKey,
            ])->retry(3, 1000)->post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => $temp,
                    'maxOutputTokens' => $maxTokens,
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Gemini request failed: ' . $response->status());
            }

            $data = $response->json();
            
            return [
                'text' => trim($data['candidates'][0]['content']['parts'][0]['text'] ?? 'Samahani, sikuelewa.'),
                'tokens' => $data['usageMetadata'] ?? null,
                'model' => 'gemini-2.5-flash'
            ];

        } catch (\Throwable $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            return [
                'text' => 'Samahani, tafadhali jaribu tena baada ya muda mfupi.',
                'tokens' => null
            ];
        }
    }
}
