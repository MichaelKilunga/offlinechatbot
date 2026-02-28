<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_thread()
    {
        $user = \App\Models\User::factory()->create();
        
        $response = $this->withSession(['chat_user_id' => $user->id])
            ->post(route('community.threads.store'), [
                'title' => 'Test Thread',
                'description' => 'Test Description',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('community_threads', ['title' => 'Test Thread']);
    }

    public function test_user_can_join_public_thread()
    {
        $user = \App\Models\User::factory()->create();
        
        $thread = \App\Models\CommunityThread::create([
            'title' => 'Public Thread',
            'slug' => 'public-thread',
            'is_private' => false,
        ]);

        $response = $this->withSession(['chat_user_id' => $user->id])
            ->post(route('community.join', $thread->slug));

        $response->assertStatus(302);
        $this->assertTrue($thread->members()->where('user_id', $user->id)->exists());
    }

    public function test_user_can_post_in_joined_thread()
    {
        $user = \App\Models\User::factory()->create();
        
        $thread = \App\Models\CommunityThread::create([
            'title' => 'Joined Thread',
            'slug' => 'joined-thread',
            'is_private' => false,
        ]);
        $thread->members()->attach($user->id);

        $response = $this->withSession(['chat_user_id' => $user->id])
            ->post(route('community.posts.store', $thread->slug), [
                'content' => 'Hello World',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('community_posts', ['content' => 'Hello World']);
    }
}
