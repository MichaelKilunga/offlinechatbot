<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Settings
        \App\Models\SystemSetting::updateOrCreate(['key' => 'primary_language'], ['value' => 'sw']);
        \App\Models\SystemSetting::updateOrCreate(['key' => 'bot_name'], ['value' => 'HuruLearn']);

        // Templates
        \App\Models\PromptTemplate::updateOrCreate(['name' => 'Default Swahili'], [
            'template' => "Wewe ni msaidizi wa kisheria nchini Tanzania. Tumia maelezo haya ya ziada kama mwongozo: {context}. Jibu swali hili la mwananchi kuhusu sheria za Tanzania kwa ufupi na kwa lugha rahisi ya Kiswahili: {user_input}",
            'temperature' => 0.7,
            'max_tokens' => 300,
            'tone' => 'legal_educational',
            'language' => 'sw',
            'is_active' => true,
        ]);

        \App\Models\PromptTemplate::updateOrCreate(['name' => 'Default English'], [
            'template' => "You are a legal assistant in Tanzania. Use this context as a guide: {context}. Answer the citizen's question about Tanzanian law briefly and in simple English: {user_input}",
            'temperature' => 0.7,
            'max_tokens' => 300,
            'tone' => 'legal_educational',
            'language' => 'en',
            'is_active' => true,
        ]);
    }
}
