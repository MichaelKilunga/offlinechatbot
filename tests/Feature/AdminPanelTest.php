<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Message;
use App\Models\AiLog;
use App\Models\Curriculum;
use App\Models\PromptTemplate;
use App\Models\LegalAidProvider;
use App\Models\CommunityThread;
use App\Models\CommunityPost;
use App\Models\SystemSetting;
use App\Models\AdminActivityLog;
use App\Services\ModerationService;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $citizen;
    private Message $outbound;
    private Curriculum $entry;
    private PromptTemplate $swTemplate;
    private LegalAidProvider $provider;
    private CommunityThread $thread;
    private CommunityPost $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin', 'email' => 'admin@test.local', 'password' => 'secret123', 'is_admin' => true, 'phone_number' => '+255700000001',
        ]);
        $this->citizen = User::create(['phone_number' => '+255700000002', 'abuse_count' => 1]);

        $inbound = Message::create(['user_id' => $this->citizen->id, 'direction' => 'inbound', 'content' => 'Nimekamatwa na polisi Sinza nifanye nini?']);
        $this->outbound = Message::create(['user_id' => $this->citizen->id, 'direction' => 'outbound', 'content' => 'Una haki ya kukaa kimya.']);
        AiLog::create(['message_id' => $this->outbound->id, 'model' => 'gemini-test', 'prompt' => $inbound->content, 'response' => $this->outbound->content, 'prompt_tokens' => 100, 'completion_tokens' => 50, 'total_tokens' => 150]);

        $this->entry = Curriculum::create(['title' => 'Haki za Mtuhumiwa', 'content' => 'Una haki ya kukaa kimya.', 'keywords' => ['kukamatwa', 'polisi'], 'language' => 'sw', 'is_active' => true]);
        $this->swTemplate = PromptTemplate::create(['name' => 'SW A', 'template' => 'Muktadha: {context}. Swali: {user_input}', 'language' => 'sw', 'is_active' => true]);
        $this->provider = LegalAidProvider::create(['name' => 'Sinza Legal Aid', 'region' => 'Dar es Salaam', 'district' => 'UBUNGO', 'location' => 'Sinza Mori', 'phone' => '+255700000003']);
        $this->thread = CommunityThread::create(['title' => 'General', 'slug' => 'general-advice', 'creator_id' => $this->citizen->id]);
        $this->post = CommunityPost::create(['community_thread_id' => $this->thread->id, 'user_id' => $this->citizen->id, 'content' => 'Habari', 'is_approved' => false]);
    }

    private function asAdmin()
    {
        return $this->withBasicAuth('admin@test.local', 'secret123');
    }

    public function test_admin_pages_require_authentication()
    {
        $this->get('/admin')->assertStatus(401);
        $this->get('/admin/users')->assertStatus(401);
    }

    public function test_every_admin_page_renders()
    {
        $pages = [
            '/admin',
            '/admin?phone=255&direction=inbound&q=polisi',
            '/admin/users',
            '/admin/users?status=flagged&q=2557&sort=messages_count',
            '/admin/users/' . $this->citizen->id,
            '/admin/conversations',
            '/admin/conversations?type=ai&from=2020-01-01',
            '/admin/conversations/' . $this->outbound->id,
            '/admin/usage',
            '/admin/usage?days=7',
            '/admin/curriculum',
            '/admin/curriculum?q=haki&language=sw&status=active',
            '/admin/curriculum/' . $this->entry->id . '/edit',
            '/admin/providers',
            '/admin/providers?missing=district&region=Dar%20es%20Salaam',
            '/admin/providers/' . $this->provider->id . '/edit',
            '/admin/templates',
            '/admin/templates/preview',
            '/admin/templates/' . $this->swTemplate->id . '/edit',
            '/admin/community',
            '/admin/community?status=pending',
            '/admin/settings',
            '/admin/system',
            '/admin/system/activity',
        ];

        foreach ($pages as $page) {
            $this->asAdmin()->get($page)->assertStatus(200);
        }
    }

    public function test_settings_are_validated_saved_and_logged()
    {
        $payload = [
            'primary_language' => 'auto', 'bot_name' => 'HuruLearn', 'sms_keyword' => 'HURU',
            'ai_enabled' => '0', 'ai_maintenance_message' => 'Tunafanyiwa matengenezo.', 'ai_model' => 'gemini-2.5-flash',
            'ai_max_words' => 200, 'ai_max_tokens' => 3000, 'ai_temperature' => 0.4, 'ai_timeout' => 20,
            'web_chat_limit' => 6, 'moderation_enabled' => '1', 'moderation_ban_threshold' => 5,
            'moderation_extra_words' => "zebra, kiboko", 'ai_price_input_per_m' => 0.1, 'ai_price_output_per_m' => 0.4,
        ];

        $this->asAdmin()->post('/admin/settings', $payload)->assertRedirect()->assertSessionHas('success');

        $this->assertSame('0', SystemSetting::get('ai_enabled'));
        $this->assertSame('gemini-2.5-flash', SystemSetting::get('ai_model'));
        $this->assertSame(5, ModerationService::banThreshold());
        $this->assertTrue((new ModerationService)->isAbusive('wewe ni zebra kabisa'));
        $this->assertFalse((new ModerationService)->isAbusive('zebras are animals'));
        $this->assertDatabaseHas('admin_activity_logs', ['action' => 'settings.updated']);

        // Invalid value is rejected and nothing is written
        $this->asAdmin()->from('/admin/settings')->post('/admin/settings', array_merge($payload, ['ai_temperature' => 9]))
            ->assertRedirect('/admin/settings')->assertSessionHasErrors('ai_temperature');
        $this->assertSame('0.4', SystemSetting::get('ai_temperature'));
    }

    public function test_moderation_can_be_switched_off()
    {
        SystemSetting::set('moderation_enabled', '0');
        $this->assertFalse((new ModerationService)->isAbusive('stupid'));
        SystemSetting::set('moderation_enabled', '1');
        $this->assertTrue((new ModerationService)->isAbusive('stupid'));
    }

    public function test_only_one_template_is_live_per_language()
    {
        $second = PromptTemplate::create(['name' => 'SW B', 'template' => 'B {context} {user_input}', 'language' => 'sw', 'is_active' => false]);

        $this->asAdmin()->patch('/admin/templates/' . $second->id . '/toggle')->assertRedirect();

        $this->assertTrue($second->fresh()->is_active);
        $this->assertFalse($this->swTemplate->fresh()->is_active);
        $this->assertSame(1, PromptTemplate::where('language', 'sw')->where('is_active', true)->count());
    }

    public function test_new_templates_start_inactive_and_require_user_input_placeholder()
    {
        $this->asAdmin()->post('/admin/templates', [
            'name' => 'Draft', 'template' => 'Hello {context}', 'temperature' => 0.7, 'max_tokens' => 300, 'tone' => 'legal', 'language' => 'en',
        ])->assertSessionHas('error');
        $this->assertDatabaseMissing('prompt_templates', ['name' => 'Draft']);

        $this->asAdmin()->post('/admin/templates', [
            'name' => 'Draft', 'template' => 'Hello {context} {user_input}', 'temperature' => 0.7, 'max_tokens' => 300, 'tone' => 'legal', 'language' => 'en',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('prompt_templates', ['name' => 'Draft', 'is_active' => false]);
    }

    public function test_prompt_playground_builds_prompt_without_calling_model()
    {
        $response = $this->asAdmin()->post('/admin/templates/preview', ['question' => 'Nimekamatwa na polisi Sinza', 'language' => 'sw', 'send' => '0']);
        $response->assertStatus(200)
            ->assertSee('Una haki ya kukaa kimya.')   // curriculum context injected
            ->assertSee('Sinza Legal Aid')            // nearby provider injected
            ->assertSee('MASHARTI MUHIMU');           // constraints appended
        $this->assertSame(2, Message::count(), 'playground must not store messages');
    }

    public function test_user_can_be_banned_reset_and_deleted()
    {
        $this->asAdmin()->patch('/admin/users/' . $this->citizen->id . '/ban', ['reason' => 'spam'])->assertRedirect();
        $this->assertTrue($this->citizen->fresh()->is_banned);
        $this->assertDatabaseHas('admin_activity_logs', ['action' => 'user.banned', 'target_id' => $this->citizen->id]);

        $this->asAdmin()->patch('/admin/users/' . $this->citizen->id . '/reset-strikes')->assertRedirect();
        $this->assertSame(0, $this->citizen->fresh()->abuse_count);

        $this->asAdmin()->delete('/admin/users/' . $this->admin->id)->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        $this->asAdmin()->delete('/admin/users/' . $this->citizen->id)->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $this->citizen->id]);
        $this->assertSame(0, Message::count());
    }

    public function test_curriculum_crud_and_bulk_actions()
    {
        $this->asAdmin()->post('/admin/curriculum', [
            'title' => 'Sheria ya Ardhi', 'content' => 'Ardhi yote ni mali ya umma.', 'tags' => 'ardhi, umiliki', 'keywords' => 'shamba', 'language' => 'sw',
        ])->assertRedirect('/admin/curriculum');
        $new = Curriculum::where('title', 'Sheria ya Ardhi')->first();
        $this->assertContains('shamba', $new->keywords);
        $this->assertContains('umiliki', $new->keywords);
        $this->assertContains('ardhi', $new->keywords);

        $this->asAdmin()->put('/admin/curriculum/' . $new->id, [
            'title' => 'Sheria ya Ardhi 2', 'content' => 'Updated', 'language' => 'en',
        ])->assertRedirect();
        $this->assertSame('en', $new->fresh()->language);

        $this->asAdmin()->post('/admin/curriculum/bulk', ['ids' => [$new->id, $this->entry->id], 'action' => 'deactivate'])->assertRedirect();
        $this->assertSame(0, Curriculum::where('is_active', true)->count());

        $this->asAdmin()->patch('/admin/curriculum/' . $this->entry->id . '/toggle')->assertRedirect();
        $this->assertTrue($this->entry->fresh()->is_active);

        $this->asAdmin()->get('/admin/curriculum/export?language=sw')->assertStatus(200)->assertHeader('content-type', 'application/json');

        $this->asAdmin()->post('/admin/curriculum/bulk', ['ids' => [$new->id], 'action' => 'delete'])->assertRedirect();
        $this->assertDatabaseMissing('curriculums', ['id' => $new->id]);
    }

    public function test_providers_crud_and_export()
    {
        $this->asAdmin()->post('/admin/providers', ['name' => 'Kahama Paralegals', 'region' => 'Shinyanga', 'district' => 'kahama', 'language' => 'sw'])->assertRedirect();
        $this->assertDatabaseHas('legal_aid_providers', ['name' => 'Kahama Paralegals', 'district' => 'KAHAMA']);

        $this->asAdmin()->put('/admin/providers/' . $this->provider->id, ['name' => 'Sinza Legal Aid Centre', 'language' => 'sw', 'email' => 'not-an-email'])->assertSessionHasErrors('email');
        $this->asAdmin()->put('/admin/providers/' . $this->provider->id, ['name' => 'Sinza Legal Aid Centre', 'language' => 'sw'])->assertRedirect();
        $this->assertSame('Sinza Legal Aid Centre', $this->provider->fresh()->name);

        $this->asAdmin()->get('/admin/providers/export')->assertStatus(200);

        $this->asAdmin()->delete('/admin/providers/' . $this->provider->id)->assertRedirect();
        $this->assertDatabaseMissing('legal_aid_providers', ['id' => $this->provider->id]);
    }

    public function test_conversation_export_is_csv()
    {
        $response = $this->asAdmin()->get('/admin/conversations/export?phone=255700000002');
        $response->assertStatus(200);
        $csv = $response->streamedContent();
        $this->assertStringContainsString('id,time,phone,direction,content', $csv);
        $this->assertStringContainsString('+255700000002', $csv);
        $this->assertStringContainsString('gemini-test', $csv);
    }

    public function test_community_moderation_actions()
    {
        $this->asAdmin()->patch('/admin/community/posts/' . $this->post->id . '/approve')->assertRedirect();
        $this->assertTrue($this->post->fresh()->is_approved);

        $this->asAdmin()->patch('/admin/community/posts/' . $this->post->id . '/pin')->assertRedirect();
        $this->assertTrue($this->post->fresh()->is_pinned);

        $this->asAdmin()->delete('/admin/community/posts/' . $this->post->id)->assertRedirect();
        $this->assertDatabaseMissing('community_posts', ['id' => $this->post->id]);

        $this->asAdmin()->delete('/admin/community/threads/' . $this->thread->id)->assertRedirect();
        $this->assertDatabaseMissing('community_threads', ['id' => $this->thread->id]);
    }

    public function test_system_tools_run_and_are_logged()
    {
        $this->asAdmin()->post('/admin/system/tools/clear-cache')->assertSessionHas('success');
        $this->asAdmin()->post('/admin/system/tools/unknown-tool')->assertSessionHas('error');

        $old = Message::create(['user_id' => $this->citizen->id, 'direction' => 'inbound', 'content' => 'old']);
        Message::whereKey($old->id)->update(['created_at' => now()->subDays(400)]);
        $this->asAdmin()->post('/admin/system/tools/purge-old-messages', ['days' => 365])->assertSessionHas('success');
        $this->assertSame(2, Message::count());

        $this->assertSame(2, AdminActivityLog::where('action', 'like', 'system.%')->count());
    }
}
