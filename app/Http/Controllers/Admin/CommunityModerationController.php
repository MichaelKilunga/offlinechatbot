<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CommunityPost;
use App\Models\CommunityThread;
use App\Models\AdminActivityLog;

class CommunityModerationController extends Controller
{
    public function index(Request $request)
    {
        $posts = CommunityPost::with(['user', 'thread'])->latest();

        if ($request->status === 'pending') {
            $posts->where('is_approved', false);
        } elseif ($request->status === 'pinned') {
            $posts->where('is_pinned', true);
        }
        if ($request->filled('thread')) {
            $posts->where('community_thread_id', $request->thread);
        }
        if ($request->filled('q')) {
            $posts->where('content', 'like', '%' . $request->q . '%');
        }

        $posts = $posts->paginate(20)->withQueryString();

        $threads = CommunityThread::withCount(['posts', 'members'])->with('creator')->latest()->get();

        $counts = [
            'posts'   => CommunityPost::count(),
            'pending' => CommunityPost::where('is_approved', false)->count(),
            'pinned'  => CommunityPost::where('is_pinned', true)->count(),
            'threads' => $threads->count(),
        ];

        return view('admin.community.index', compact('posts', 'threads', 'counts'));
    }

    public function toggleApproval(CommunityPost $post)
    {
        $post->update(['is_approved' => !$post->is_approved]);
        $state = $post->is_approved ? 'approved' : 'hidden';

        AdminActivityLog::record("community.post_{$state}", "Community post #{$post->id} {$state}", $post);

        return back()->with('success', "Post {$state}.");
    }

    public function togglePin(CommunityPost $post)
    {
        $post->update(['is_pinned' => !$post->is_pinned]);
        $state = $post->is_pinned ? 'pinned' : 'unpinned';

        AdminActivityLog::record("community.post_{$state}", "Community post #{$post->id} {$state}", $post);

        return back()->with('success', "Post {$state}.");
    }

    public function destroyPost(CommunityPost $post)
    {
        AdminActivityLog::record('community.post_deleted', "Deleted community post #{$post->id}", null, ['id' => $post->id, 'thread' => $post->community_thread_id]);
        $post->delete();
        return back()->with('success', 'Post deleted.');
    }

    public function destroyThread(CommunityThread $thread)
    {
        if ($thread->is_system) {
            return back()->with('error', 'System threads cannot be deleted.');
        }

        AdminActivityLog::record('community.thread_deleted', "Deleted community thread \"{$thread->title}\"", null, ['id' => $thread->id]);
        $thread->delete();
        return back()->with('success', 'Thread deleted together with its posts.');
    }
}
