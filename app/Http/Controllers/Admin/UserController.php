<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Message;
use App\Models\AdminActivityLog;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount([
                'messages',
                'messages as inbound_count' => fn($q) => $q->where('direction', 'inbound'),
            ])
            ->withMax('messages', 'created_at');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('phone_number', 'like', "%{$q}%")
                  ->orWhere('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('username', 'like', "%{$q}%");
            });
        }

        switch ($request->status) {
            case 'banned':  $query->where('is_banned', true); break;
            case 'flagged': $query->where('is_banned', false)->where('abuse_count', '>', 0); break;
            case 'clean':   $query->where('is_banned', false)->where('abuse_count', 0); break;
            case 'admins':  $query->where('is_admin', true); break;
        }

        $sort = in_array($request->sort, ['messages_count', 'abuse_count', 'created_at', 'messages_max_created_at'])
            ? $request->sort : 'messages_max_created_at';
        $query->orderByDesc($sort);

        $users = $query->paginate(25)->withQueryString();

        $counts = [
            'all'     => User::count(),
            'banned'  => User::where('is_banned', true)->count(),
            'flagged' => User::where('is_banned', false)->where('abuse_count', '>', 0)->count(),
            'admins'  => User::where('is_admin', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'counts', 'sort'));
    }

    public function show(User $user)
    {
        $messages = $user->messages()->with('aiLog')->latest()->paginate(40);
        $totals = [
            'messages' => $user->messages()->count(),
            'inbound'  => $user->messages()->where('direction', 'inbound')->count(),
            'tokens'   => (int) \App\Models\AiLog::whereIn('message_id', $user->messages()->select('id'))->sum('total_tokens'),
            'first'    => $user->messages()->min('created_at'),
            'last'     => $user->messages()->max('created_at'),
        ];
        $activity = AdminActivityLog::where('target_type', 'User')->where('target_id', $user->id)->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'messages', 'totals', 'activity'));
    }

    public function toggleBan(Request $request, User $user)
    {
        $user->update(['is_banned' => !$user->is_banned]);
        $state = $user->is_banned ? 'banned' : 'unbanned';

        AdminActivityLog::record("user.{$state}", "User {$user->phone_number} {$state}", $user, [
            'reason' => $request->input('reason'),
        ]);

        return back()->with('success', "User {$user->phone_number} has been {$state}.");
    }

    public function resetStrikes(User $user)
    {
        $previous = $user->abuse_count;
        $user->update(['abuse_count' => 0]);

        AdminActivityLog::record('user.strikes_reset', "Strikes reset for {$user->phone_number} (was {$previous})", $user);

        return back()->with('success', "Abuse strikes reset for {$user->phone_number}.");
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Admin accounts cannot be deleted from this panel.');
        }

        $phone = $user->phone_number;
        $count = $user->messages()->count();
        $user->delete();

        AdminActivityLog::record('user.deleted', "Deleted user {$phone} and {$count} messages", null, ['phone' => $phone]);

        return redirect()->route('admin.users.index')->with('success', "User {$phone} and their conversation history were deleted.");
    }
}
