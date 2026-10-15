<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\SystemSetting;

class ModerationService
{
    /**
     * Built-in list of abusive/forbidden keywords in Swahili and English.
     * Admins can extend this list from Settings → Moderation (moderation_extra_words).
     */
    protected array $forbiddenWords = [
        // Swahili (General offensive)
        'pumbavu', 'mjinga', 'mshenzi', 'fala', 'mavi', 'umaarufu', 'mbwa', 'kuma', 'mboro', 'shoga', 'ngono', 'malaya',

        // English (General offensive)
        'fuck', 'shit', 'idiot', 'stupid', 'bastard', 'bitch', 'asshole', 'dick', 'pussy', 'sex', 'porn', 'abuse',
    ];

    /**
     * Number of strikes after which a user is banned (admin-configurable).
     */
    public static function banThreshold(): int
    {
        return max(1, (int) SystemSetting::get('moderation_ban_threshold', 3));
    }

    /**
     * Whether keyword moderation is switched on at all.
     */
    public static function enabled(): bool
    {
        return SystemSetting::get('moderation_enabled', '1') == '1';
    }

    /**
     * Full word list: built-in plus admin-added words.
     */
    public function words(): array
    {
        $extra = (string) SystemSetting::get('moderation_extra_words', '');
        $extraWords = array_filter(array_map(fn($w) => Str::lower(trim($w)), preg_split('/[,\n]+/', $extra)));

        return array_values(array_unique(array_merge($this->forbiddenWords, $extraWords)));
    }

    /**
     * Check if the given text contains any forbidden words.
     */
    public function isAbusive(string $text): bool
    {
        if (!static::enabled()) {
            return false;
        }

        $text = Str::lower($text);

        foreach ($this->words() as $word) {
            // Whole-word match to avoid false positives (e.g. "asset" containing "ass")
            if ($word !== '' && preg_match('/\b' . preg_quote($word, '/') . '\b/iu', $text)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the warning message based on the language.
     */
    public function getWarningMessage(string $language, int $abuseCount): string
    {
        if ($language === 'sw') {
            return "ONYO: Lugha ya matusi haikubaliki. Huu ni onyo lako la " . $abuseCount . ". Utafungiwa huduma ukiendelea.";
        }

        return "WARNING: Abusive language is not accepted. This is your warning #" . $abuseCount . ". You will be banned from this service if this continues.";
    }

    /**
     * Get the ban message.
     */
    public function getBanMessage(string $language): string
    {
        if ($language === 'sw') {
            return "HUDUMA IMEFUNGIWA: Umefungiwa kupata huduma hii kutokana na kukiuka vigezo na masharti (matusi).";
        }

        return "SERVICE BANNED: You have been banned from using this service due to violating our terms (abusive language).";
    }
}
