<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AiLog;
use App\Models\Message;
use App\Models\User;
use App\Models\SystemSetting;

class UsageController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) ($request->days ?: 30);
        $days = in_array($days, [7, 14, 30, 90]) ? $days : 30;
        $since = now()->subDays($days - 1)->startOfDay();

        $logs = AiLog::where('created_at', '>=', $since)->get(['created_at', 'model', 'prompt_tokens', 'completion_tokens', 'total_tokens']);

        $byDay = $logs->groupBy(fn($l) => $l->created_at->toDateString());
        $daily = collect(range($days - 1, 0))->map(function ($i) use ($byDay) {
            $d = now()->subDays($i)->toDateString();
            $g = $byDay->get($d, collect());
            return [
                'date'       => $d,
                'label'      => \Carbon\Carbon::parse($d)->format('M d'),
                'calls'      => $g->count(),
                'prompt'     => (int) $g->sum('prompt_tokens'),
                'completion' => (int) $g->sum('completion_tokens'),
                'total'      => (int) $g->sum('total_tokens'),
            ];
        });

        $byModel = $logs->groupBy('model')->map(fn($g, $model) => [
            'model'  => $model ?: 'unknown',
            'calls'  => $g->count(),
            'tokens' => (int) $g->sum('total_tokens'),
            'avg'    => $g->count() ? (int) round($g->sum('total_tokens') / $g->count()) : 0,
        ])->sortByDesc('tokens')->values();

        $summary = [
            'calls'       => $logs->count(),
            'prompt'      => (int) $logs->sum('prompt_tokens'),
            'completion'  => (int) $logs->sum('completion_tokens'),
            'total'       => (int) $logs->sum('total_tokens'),
            'avg'         => $logs->count() ? (int) round($logs->sum('total_tokens') / $logs->count()) : 0,
            'max'         => (int) $logs->max('total_tokens'),
            'all_time'    => (int) AiLog::sum('total_tokens'),
            'all_calls'   => AiLog::count(),
        ];

        // Optional cost estimate using admin-configured prices (USD per 1M tokens)
        $inPrice  = (float) SystemSetting::get('ai_price_input_per_m', 0);
        $outPrice = (float) SystemSetting::get('ai_price_output_per_m', 0);
        $summary['cost'] = ($summary['prompt'] / 1_000_000) * $inPrice + ($summary['completion'] / 1_000_000) * $outPrice;
        $summary['has_prices'] = $inPrice > 0 || $outPrice > 0;

        // Top users by inbound messages in the window
        $topUsers = Message::where('direction', 'inbound')
            ->where('created_at', '>=', $since)
            ->selectRaw('user_id, count(*) as c')
            ->groupBy('user_id')
            ->orderByDesc('c')
            ->take(10)
            ->with('user')
            ->get();

        // Most common question words (cheap insight into what people ask)
        $stop = ['huru','nini','kwa','na','ya','wa','za','the','is','what','how','can','a','an','of','to','in','on','my','me','i','ni','je','kama','kuhusu','about','naomba','tafadhali','please','au','or','and','ili','mimi','yangu','wangu','ndio','hapa','sheria','law'];
        $words = Message::where('direction', 'inbound')->where('created_at', '>=', $since)->pluck('content')
            ->flatMap(fn($c) => preg_split('/[^a-z0-9]+/i', mb_strtolower($c)))
            ->filter(fn($w) => strlen($w) > 3 && !in_array($w, $stop))
            ->countBy()
            ->sortDesc()
            ->take(25);

        $hourly = Message::where('direction', 'inbound')->where('created_at', '>=', $since)->get(['created_at'])
            ->groupBy(fn($m) => (int) $m->created_at->format('G'))->map->count();
        $hourly = collect(range(0, 23))->map(fn($h) => ['hour' => $h, 'count' => $hourly[$h] ?? 0]);

        return view('admin.usage', compact('days', 'daily', 'byModel', 'summary', 'topUsers', 'words', 'hourly'));
    }
}
