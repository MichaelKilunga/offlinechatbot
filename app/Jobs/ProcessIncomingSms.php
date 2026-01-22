<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\User;
use App\Models\Message;
use App\Models\AiLog;
use App\Services\AiService;
use App\Services\SmsService;

class ProcessIncomingSms implements ShouldQueue
{
    use Queueable;

    protected $from;
    protected $text;

    /**
     * Create a new job instance.
     */
    public function __construct($from, $text)
    {
        $this->from = $from;
        $this->text = $text;
    }

    /**
     * Execute the job.
     */
    public function handle(AiService $aiService, SmsService $smsService): void
    {
        // 1. Find or create user
        $user = User::firstOrCreate(['phone_number' => $this->from]);

        // 2. Store user message
        $inboundMessage = Message::create([
            'user_id' => $user->id,
            'direction' => 'inbound',
            'content' => $this->text,
        ]);

        // 3. Resolve Language
        $language = \App\Models\SystemSetting::where('key', 'primary_language')->value('value') ?? 'sw';

        // 4. Generate Contextual AI Response
        $aiResult = $aiService->generateContextualResponse($this->text, $language);

        $aiResponseText = $aiResult['text'];

        // 5. Send SMS
        $smsService->send($this->from, $aiResponseText);

        // 6. Store system message
        $outboundMessage = Message::create([
            'user_id' => $user->id,
            'direction' => 'outbound',
            'content' => $aiResponseText,
        ]);

        // 7. Log AI interaction
        AiLog::create([
            'message_id' => $outboundMessage->id,
            'model' => $aiResult['model'] ?? 'gemini-2.5-flash',
            'prompt' => $this->text, // Simple log of user query
            'response' => $aiResponseText,
            'prompt_tokens' => $aiResult['tokens']['promptTokenCount'] ?? 0,
            'completion_tokens' => $aiResult['tokens']['candidatesTokenCount'] ?? 0,
            'total_tokens' => $aiResult['tokens']['totalTokenCount'] ?? 0,
        ]);
    }
}
