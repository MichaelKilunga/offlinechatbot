<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityThread;
use App\Models\CommunityPost;
use App\Models\CommunityMember;
use App\Models\CommunityReaction;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Public threads
        $publicThreads = CommunityThread::where('is_private', false)->withCount('posts')->latest()->get();
        
        // Threads user is a member of (if logged in)
        $joinedThreads = collect();
        if ($user) {
            $joinedThreads = $user->joinedThreads()
                ->withCount(['posts as unread_count' => function ($query) use ($user) {
                    $query->where('community_posts.created_at', '>', function ($q) use ($user) {
                        $q->select('last_read_at')
                          ->from('community_members')
                          ->whereColumn('community_thread_id', 'community_posts.community_thread_id')
                          ->where('user_id', $user->id);
                    });
                }])
                ->latest()
                ->get();
        }

        return view('community.index', compact('publicThreads', 'joinedThreads', 'user'));
    }

    public function show(CommunityThread $thread)
    {
        $user = Auth::user();

        // Check if thread is private and user is a member
        if ($thread->is_private) {
            if (!$user || !$thread->members->contains($user)) {
                return redirect()->route('community.index')->with('error', 'This is a private thread.');
            }
        }

        $posts = $thread->posts()->with(['user', 'reactions', 'replies.user'])->whereNull('parent_id')->orderBy('created_at', 'asc')->paginate(50);
        $members = $thread->members;

        // Update last read for member
        if ($user) {
            $membership = CommunityMember::where('community_thread_id', $thread->id)->where('user_id', $user->id)->first();
            if ($membership) {
                $membership->update(['last_read_at' => now()]);
            }
        }

        return view('community.show', compact('thread', 'posts', 'members', 'user'));
    }

    public function storeThread(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login', ['redirect_to' => url()->current()])->with('error', 'Please login to create a thread.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_private' => 'boolean',
        ]);

        $thread = CommunityThread::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'description' => $request->description,
            'creator_id' => $userId,
            'is_private' => $request->has('is_private'),
        ]);

        // Creator is the first member and admin of the thread
        $thread->members()->attach($userId, ['role' => 'admin']);

        return redirect()->route('community.show', $thread);
    }

    public function storePost(Request $request, CommunityThread $thread)
    {
        $userId = Auth::id();
        if (!$userId) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login', ['redirect_to' => url()->current()]);
        }

        $user = User::find($userId);
        if (!$thread->members->contains($user)) {
            return response()->json(['error' => 'You are not a member of this thread.'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:community_posts,id',
        ]);

        $post = CommunityPost::create([
            'community_thread_id' => $thread->id,
            'user_id' => $userId,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'is_approved' => true,
        ]);
        
        // Update user's last read for this thread as they just posted
        CommunityMember::where('community_thread_id', $thread->id)->where('user_id', $userId)->update(['last_read_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'post' => $post->load('user')]);
        }

        return back();
    }

    public function join(CommunityThread $thread)
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->route('login', ['redirect_to' => url()->current()]);

        if ($thread->is_private) {
            return back()->with('error', 'Cannot join a private thread without an invite.');
        }

        $thread->members()->syncWithoutDetaching([$userId => ['role' => 'member']]);

        return redirect()->route('community.show', $thread);
    }

    public function leave(CommunityThread $thread)
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->route('login');

        $thread->members()->detach($userId);

        return redirect()->route('community.index');
    }

    // Reactions
    public function toggleReaction(Request $request, CommunityPost $post)
    {
        $userId = Auth::id();
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $request->validate([
            'type' => 'required|string|in:like,thumbs_up,thumbs_down',
        ]);

        $reaction = CommunityReaction::where('community_post_id', $post->id)
            ->where('user_id', $userId)
            ->where('type', $request->type)
            ->first();

        if ($reaction) {
            $reaction->delete();
            $status = 'removed';
        } else {
            // Remove other types of reactions from this user on this post if you want only one reaction per post
            CommunityReaction::where('community_post_id', $post->id)
                ->where('user_id', $userId)
                ->delete();

            CommunityReaction::create([
                'community_post_id' => $post->id,
                'user_id' => $userId,
                'type' => $request->type,
            ]);
            $status = 'added';
        }

        $counts = [
            'like' => $post->reactions()->where('type', 'like')->count(),
            'thumbs_up' => $post->reactions()->where('type', 'thumbs_up')->count(),
            'thumbs_down' => $post->reactions()->where('type', 'thumbs_down')->count(),
        ];

        return response()->json(['status' => 'success', 'reaction_status' => $status, 'counts' => $counts]);
    }

    // Admin moderation
    public function toggleApproval(CommunityPost $post)
    {
        // This should be protected by admin middleware
        $post->update(['is_approved' => !$post->is_approved]);
        return back()->with('success', 'Post approval toggled.');
    }
}
