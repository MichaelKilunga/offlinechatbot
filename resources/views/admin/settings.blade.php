@extends("layouts.admin", ["title" => "Settings"])

@section("content")
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="grid-side" style="grid-template-columns: 1fr 320px;">
            <div class="stack">

                <!-- Engine -->
                <div class="card" id="engine">
                    <div class="card-header"><div><div class="card-title">🔌 Engine</div><div class="card-subtitle">How replies are generated</div></div></div>
                    <div class="card-body">
                        <div class="grid-2" style="gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Engine status</label>
                                <select name="ai_enabled" class="form-select">
                                    <option value="1" {{ $settings['ai_enabled'] == '1' ? 'selected' : '' }}>🟢 Active: answering messages</option>
                                    <option value="0" {{ $settings['ai_enabled'] == '0' ? 'selected' : '' }}>🔴 Paused: send maintenance message</option>
                                </select>
                                <div class="form-hint">Paused stops all model calls (SMS and web). Moderation still runs.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Model name</label>
                                <input type="text" name="ai_model" class="form-input mono" value="{{ $settings['ai_model'] }}">
                                <div class="form-hint">Gemini model id, e.g. <span class="mono">gemini-flash-lite-latest</span>, <span class="mono">gemini-2.5-flash</span>. Test in the playground after changing.</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maintenance auto-reply</label>
                            <textarea name="ai_maintenance_message" class="form-textarea" rows="2">{{ $settings['ai_maintenance_message'] }}</textarea>
                            <div class="form-hint">Sent instead of an answer while paused. Keep under 160 characters for a single SMS: currently {{ mb_strlen($settings['ai_maintenance_message']) }}.</div>
                        </div>
                        <div class="grid-3" style="gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Max response words</label>
                                <input type="number" name="ai_max_words" class="form-input" value="{{ $settings['ai_max_words'] }}" min="10" max="1000">
                                <div class="form-hint">Instruction inside the prompt. One SMS ≈ 25 words.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max output tokens</label>
                                <input type="number" name="ai_max_tokens" class="form-input" value="{{ $settings['ai_max_tokens'] }}" min="100" max="20000">
                                <div class="form-hint">Hard API cap. 2000–4000 is enough for SMS answers.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Temperature</label>
                                <input type="number" step="0.1" name="ai_temperature" class="form-input" value="{{ $settings['ai_temperature'] }}" min="0" max="2">
                                <div class="form-hint">0 = strict and repeatable, 1+ = creative.</div>
                            </div>
                        </div>
                        <div class="grid-2" style="gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Request timeout (seconds)</label>
                                <input type="number" name="ai_timeout" class="form-input" value="{{ $settings['ai_timeout'] }}" min="5" max="120">
                                <div class="form-hint">Citizens get the fallback apology if the model takes longer.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Channels -->
                <div class="card" id="channels">
                    <div class="card-header"><div><div class="card-title">📡 Channels</div><div class="card-subtitle">SMS and web chat behaviour</div></div></div>
                    <div class="card-body">
                        <div class="grid-3" style="gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Primary language</label>
                                <select name="primary_language" class="form-select">
                                    <option value="auto" {{ $settings['primary_language'] == 'auto' ? 'selected' : '' }}>🌍 Auto detect per message</option>
                                    <option value="sw" {{ $settings['primary_language'] == 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
                                    <option value="en" {{ $settings['primary_language'] == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                </select>
                                <div class="form-hint">Picks the template, legal entries and constraint wording.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">SMS keyword</label>
                                <input type="text" name="sms_keyword" class="form-input mono" value="{{ $settings['sms_keyword'] }}" maxlength="20" style="text-transform:uppercase;">
                                <div class="form-hint">Messages to the shortcode must start with this word. Must match the keyword registered at Africa's Talking.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Web chat history shown</label>
                                <input type="number" name="web_chat_limit" class="form-input" value="{{ $settings['web_chat_limit'] }}" min="1" max="50">
                                <div class="form-hint">Previous messages loaded when a citizen opens web chat.</div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Service name (admin branding)</label>
                            <input type="text" name="bot_name" class="form-input" value="{{ $settings['bot_name'] }}" style="max-width:320px;">
                        </div>
                    </div>
                </div>

                <!-- Moderation -->
                <div class="card" id="moderation">
                    <div class="card-header"><div><div class="card-title">🛡️ Moderation</div><div class="card-subtitle">Abusive-language strikes and bans</div></div></div>
                    <div class="card-body">
                        <div class="grid-2" style="gap:1rem;">
                            <div class="form-group">
                                <label class="form-label">Keyword moderation</label>
                                <select name="moderation_enabled" class="form-select">
                                    <option value="1" {{ $settings['moderation_enabled'] == '1' ? 'selected' : '' }}>🟢 On: warn and ban on abusive words</option>
                                    <option value="0" {{ $settings['moderation_enabled'] == '0' ? 'selected' : '' }}>🔴 Off: never check words</option>
                                </select>
                                <div class="form-hint">Safety blocks from the model itself still count as strikes.</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Strikes before ban</label>
                                <input type="number" name="moderation_ban_threshold" class="form-input" value="{{ $settings['moderation_ban_threshold'] }}" min="1" max="20">
                                <div class="form-hint">A user is banned when their strike count reaches this number. Applies to new strikes only.</div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Extra forbidden words</label>
                            <textarea name="moderation_extra_words" class="form-textarea" rows="3" placeholder="word1, word2, neno3">{{ $settings['moderation_extra_words'] }}</textarea>
                            <div class="form-hint">Comma or newline separated, matched as whole words, case-insensitive. Added on top of the built-in Swahili and English list ({{ count((new \App\Services\ModerationService)->words()) }} words active now).</div>
                        </div>
                    </div>
                </div>

                <!-- Cost -->
                <div class="card" id="cost">
                    <div class="card-header"><div><div class="card-title">💵 Cost tracking (optional)</div><div class="card-subtitle">Used only to estimate spend on the Usage page</div></div></div>
                    <div class="card-body">
                        <div class="grid-2" style="gap:1rem;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Input price, USD per 1M tokens</label>
                                <input type="number" step="0.0001" name="ai_price_input_per_m" class="form-input" value="{{ $settings['ai_price_input_per_m'] }}" min="0">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Output price, USD per 1M tokens</label>
                                <input type="number" step="0.0001" name="ai_price_output_per_m" class="form-input" value="{{ $settings['ai_price_output_per_m'] }}" min="0">
                            </div>
                        </div>
                        <div class="form-hint">Leave at 0 to hide the estimate. Check your provider's current price list.</div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:.6rem;">
                    <button type="submit" class="btn btn-primary">💾 Save all settings</button>
                </div>
            </div>

            <!-- Side: environment -->
            <div class="stack">
                <div class="card" style="position:sticky; top:80px;">
                    <div class="card-header"><div class="card-title">🔑 Environment (.env)</div></div>
                    <div class="card-body" style="padding:.6rem 1.2rem;">
                        <div class="check"><span class="check-dot {{ $env['gemini_key'] ? 'ok' : 'bad' }}"></span><div>Model API key<div class="muted" style="font-size:.7rem;">GEMINI_API_KEY</div></div></div>
                        <div class="check"><span class="check-dot {{ $env['at_key'] ? 'ok' : 'bad' }}"></span><div>Africa's Talking key<div class="muted" style="font-size:.7rem;">AT_USERNAME = {{ $env['at_username'] ?: 'missing' }}</div></div></div>
                        <div class="check"><span class="check-dot {{ $env['at_from'] ? 'ok' : 'bad' }}"></span><div>Shortcode / sender<div class="muted" style="font-size:.7rem;">AT_FROM = {{ $env['at_from'] ?: 'missing' }}</div></div></div>
                        <div class="check"><span class="check-dot ok"></span><div>Queue: {{ $env['queue'] }} · Cache: {{ $env['cache'] }}</div></div>
                        <div class="check"><span class="check-dot {{ $env['app_debug'] ? 'bad' : 'ok' }}"></span><div>APP_ENV = {{ $env['app_env'] }}, debug {{ $env['app_debug'] ? 'ON' : 'off' }}</div></div>
                        <div class="check"><span class="check-dot {{ $env['mail_from'] ? 'ok' : 'bad' }}"></span><div>Contact form mail to<div class="muted" style="font-size:.7rem;">{{ $env['mail_from'] ?: 'missing' }}</div></div></div>
                    </div>
                    <div class="card-footer form-hint">Secrets live in <span class="mono">.env</span> on the server and are not editable here. Restart the queue worker after changing them.</div>
                </div>

                <div class="card">
                    <div class="card-header"><div class="card-title">Defaults</div></div>
                    <div class="card-body" style="padding:.8rem 1.2rem;">
                        <dl class="kv" style="grid-template-columns: 1fr auto; font-size:.74rem;">
                            @foreach($defaults as $key => $default)
                                @if(!in_array($key, ['ai_maintenance_message', 'moderation_extra_words']))
                                    <dt class="mono">{{ $key }}</dt><dd class="mono muted">{{ $default }}</dd>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
