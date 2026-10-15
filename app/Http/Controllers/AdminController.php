<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Message;
use App\Models\AiLog;
use App\Models\Curriculum;
use App\Models\PromptTemplate;
use App\Models\LegalAidProvider;
use App\Models\CommunityPost;
use App\Models\AdminActivityLog;
use App\Models\SystemSetting;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->startOfDay();
        $weekAgo = now()->subDays(7);

        $stats = [
            'total_users'      => User::count(),
            'new_users_7d'     => User::where('created_at', '>=', $weekAgo)->count(),
            'active_users_7d'  => Message::where('created_at', '>=', $weekAgo)->distinct('user_id')->count('user_id'),
            'banned_users'     => User::where('is_banned', true)->count(),
            'flagged_users'    => User::where('abuse_count', '>', 0)->where('is_banned', false)->count(),
            'total_messages'   => Message::count(),
            'messages_today'   => Message::where('created_at', '>=', $today)->count(),
            'inbound_today'    => Message::where('created_at', '>=', $today)->where('direction', 'inbound')->count(),
            'tokens_today'     => (int) AiLog::where('created_at', '>=', $today)->sum('total_tokens'),
            'tokens_7d'        => (int) AiLog::where('created_at', '>=', $weekAgo)->sum('total_tokens'),
            'tokens_total'     => (int) AiLog::sum('total_tokens'),
            'ai_calls_7d'      => AiLog::where('created_at', '>=', $weekAgo)->count(),
            'avg_tokens'       => (int) round(AiLog::avg('total_tokens') ?? 0),
            'curricula'        => Curriculum::count(),
            'curricula_active' => Curriculum::where('is_active', true)->count(),
            'providers'        => LegalAidProvider::count(),
            'templates_active' => PromptTemplate::where('is_active', true)->count(),
            'posts_pending'    => CommunityPost::where('is_approved', false)->count(),
            'queue_pending'    => Schema::hasTable('jobs') ? DB::table('jobs')->count() : 0,
            'queue_failed'     => Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : 0,
        ];

        // 14-day activity series (inbound messages + tokens per day)
        $days = collect(range(13, 0))->map(fn($i) => now()->subDays($i)->toDateString());
        $msgByDay = Message::where('direction', 'inbound')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->get(['created_at'])
            ->groupBy(fn($m) => $m->created_at->toDateString())
            ->map->count();
        $tokByDay = AiLog::where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->get(['created_at', 'total_tokens'])
            ->groupBy(fn($l) => $l->created_at->toDateString())
            ->map(fn($g) => (int) $g->sum('total_tokens'));

        $series = $days->map(fn($d) => [
            'date'     => $d,
            'label'    => \Carbon\Carbon::parse($d)->format('D d'),
            'messages' => $msgByDay[$d] ?? 0,
            'tokens'   => $tokByDay[$d] ?? 0,
        ]);

        // Filterable interactions feed
        $query = Message::with(['user', 'aiLog'])->latest();
        if ($request->filled('phone')) {
            $query->whereHas('user', fn($q) => $q->where('phone_number', 'like', '%' . $request->phone . '%'));
        }
        if ($request->filled('direction') && in_array($request->direction, ['inbound', 'outbound'])) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('q')) {
            $query->where('content', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $interactions = $query->paginate(20)->withQueryString();

        $recentActivity = Schema::hasTable('admin_activity_logs')
            ? AdminActivityLog::latest()->take(8)->get()
            : collect();

        $aiEnabled = SystemSetting::get('ai_enabled', '1') == '1';
        $moderationEnabled = SystemSetting::get('moderation_enabled', '1') == '1';
        $geminiConfigured = !empty(config('services.gemini.key'));
        $smsConfigured = !empty(config('services.at.api_key')) && !empty(config('services.at.username'));
        $lastInbound = Message::where('direction', 'inbound')->latest()->value('created_at');

        return view('admin.dashboard', compact(
            'stats', 'series', 'interactions', 'recentActivity',
            'aiEnabled', 'moderationEnabled', 'geminiConfigured', 'smsConfigured', 'lastInbound'
        ));
    }
}
