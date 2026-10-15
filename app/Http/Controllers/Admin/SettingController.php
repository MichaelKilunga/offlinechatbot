<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SystemSetting;
use App\Models\AdminActivityLog;

class SettingController extends Controller
{
    /**
     * Every setting the panel manages, with validation and defaults.
     * Keys not listed here are ignored on save, so stray form fields can't pollute the table.
     */
    public static function definitions(): array
    {
        return [
            // General
            'primary_language'       => ['rule' => 'required|in:auto,sw,en',          'default' => 'sw'],
            'bot_name'               => ['rule' => 'required|string|max:60',           'default' => 'HuruLearn'],
            'sms_keyword'            => ['rule' => 'required|alpha_num|max:20',        'default' => 'HURU'],

            // Engine
            'ai_enabled'             => ['rule' => 'required|in:0,1',                  'default' => '1'],
            'ai_maintenance_message' => ['rule' => 'required|string|max:480',          'default' => 'Samahani, mfumo wetu unafanyiwa matengenezo. Tafadhali jaribu tena baadaye. (System is under maintenance, please try later.)'],
            'ai_model'               => ['rule' => 'required|string|max:80',           'default' => 'gemini-flash-lite-latest'],
            'ai_max_words'           => ['rule' => 'required|integer|min:10|max:1000', 'default' => '320'],
            'ai_max_tokens'          => ['rule' => 'required|integer|min:100|max:20000','default' => '8000'],
            'ai_temperature'         => ['rule' => 'required|numeric|min:0|max:2',     'default' => '0.7'],
            'ai_timeout'             => ['rule' => 'required|integer|min:5|max:120',   'default' => '30'],

            // Web chat
            'web_chat_limit'         => ['rule' => 'required|integer|min:1|max:50',    'default' => '4'],

            // Moderation
            'moderation_enabled'     => ['rule' => 'required|in:0,1',                  'default' => '1'],
            'moderation_ban_threshold' => ['rule' => 'required|integer|min:1|max:20',  'default' => '3'],
            'moderation_extra_words' => ['rule' => 'nullable|string|max:5000',         'default' => ''],

            // Cost tracking (USD per 1M tokens, 0 = disabled)
            'ai_price_input_per_m'   => ['rule' => 'nullable|numeric|min:0|max:1000',  'default' => '0'],
            'ai_price_output_per_m'  => ['rule' => 'nullable|numeric|min:0|max:1000',  'default' => '0'],
        ];
    }

    public function index()
    {
        $defs = static::definitions();
        $stored = SystemSetting::all()->pluck('value', 'key');

        $settings = collect($defs)->map(fn($d, $key) => $stored[$key] ?? $d['default']);

        return view('admin.settings', [
            'settings' => $settings,
            'defaults' => collect($defs)->map(fn($d) => $d['default']),
            'env' => [
                'gemini_key' => !empty(config('services.gemini.key')),
                'at_username' => config('services.at.username'),
                'at_key' => !empty(config('services.at.api_key')),
                'at_from' => config('services.at.from'),
                'queue' => config('queue.default'),
                'cache' => config('cache.default'),
                'mail_from' => config('mail.from.address'),
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $defs = static::definitions();
        $data = $request->validate(collect($defs)->map(fn($d) => $d['rule'])->all());

        $changed = [];
        foreach ($data as $key => $value) {
            $value = is_null($value) ? '' : trim((string) $value);
            $old = SystemSetting::where('key', $key)->value('value');
            if ($old !== $value) {
                $changed[$key] = ['from' => $old, 'to' => $value];
            }
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($changed) {
            $summary = implode(', ', array_keys($changed));
            AdminActivityLog::record('settings.updated', "Settings changed: {$summary}", null, $changed);
        }

        return redirect()->back()->with('success', $changed
            ? 'Settings saved. Changed: ' . implode(', ', array_keys($changed)) . '.'
            : 'Settings saved. Nothing changed.');
    }
}
