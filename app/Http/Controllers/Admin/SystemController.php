<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\AdminActivityLog;
use App\Models\Message;
use App\Models\AiLog;
use App\Models\User;
use App\Models\Curriculum;
use App\Models\LegalAidProvider;
use App\Models\PromptTemplate;
use App\Models\CommunityPost;
use App\Models\CommunityThread;

class SystemController extends Controller
{
    public function index()
    {
        $checks = [
            ['label' => 'Model API key (Gemini)', 'ok' => !empty(config('services.gemini.key')), 'detail' => 'GEMINI_API_KEY in .env'],
            ['label' => "Africa's Talking credentials", 'ok' => !empty(config('services.at.api_key')) && !empty(config('services.at.username')), 'detail' => 'AT_USERNAME / AT_API_KEY'],
            ['label' => 'SMS sender / shortcode', 'ok' => !empty(config('services.at.from')), 'detail' => config('services.at.from') ?: 'AT_FROM missing'],
            ['label' => 'Database connection', 'ok' => $this->dbOk(), 'detail' => config('database.default')],
            ['label' => 'Queue worker schedule', 'ok' => true, 'detail' => 'queue:work runs every minute via scheduler (' . config('queue.default') . ')'],
            ['label' => 'Debug mode off', 'ok' => !config('app.debug'), 'detail' => config('app.debug') ? 'APP_DEBUG=true: disable in production' : 'APP_DEBUG=false'],
            ['label' => 'Active prompt template (sw)', 'ok' => PromptTemplate::where('language', 'sw')->where('is_active', true)->exists(), 'detail' => 'Falls back to built-in default if missing'],
            ['label' => 'Active prompt template (en)', 'ok' => PromptTemplate::where('language', 'en')->where('is_active', true)->exists(), 'detail' => 'Falls back to built-in default if missing'],
            ['label' => 'Legal database has active content', 'ok' => Curriculum::where('is_active', true)->exists(), 'detail' => Curriculum::where('is_active', true)->count() . ' active entries'],
            ['label' => 'Legal aid providers loaded', 'ok' => LegalAidProvider::exists(), 'detail' => LegalAidProvider::count() . ' providers'],
            ['label' => 'Activity log table', 'ok' => Schema::hasTable('admin_activity_logs'), 'detail' => 'Run php artisan migrate if missing'],
        ];

        $queue = [
            'pending' => Schema::hasTable('jobs') ? DB::table('jobs')->count() : null,
            'failed'  => Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : null,
            'oldest_pending' => Schema::hasTable('jobs') ? DB::table('jobs')->min('created_at') : null,
        ];
        $failedJobs = Schema::hasTable('failed_jobs')
            ? DB::table('failed_jobs')->orderByDesc('failed_at')->take(10)->get()
            : collect();

        $tables = [
            'Users' => User::count(),
            'Messages' => Message::count(),
            'Model logs' => AiLog::count(),
            'Legal entries' => Curriculum::count(),
            'Legal aid providers' => LegalAidProvider::count(),
            'Prompt templates' => PromptTemplate::count(),
            'Community threads' => CommunityThread::count(),
            'Community posts' => CommunityPost::count(),
            'Admin activity' => Schema::hasTable('admin_activity_logs') ? AdminActivityLog::count() : 0,
        ];

        $runtime = [
            'Laravel' => app()->version(),
            'PHP' => PHP_VERSION,
            'Environment' => config('app.env'),
            'Timezone' => config('app.timezone'),
            'Queue driver' => config('queue.default'),
            'Cache store' => config('cache.default'),
            'Session driver' => config('session.driver'),
            'Server time' => now()->toDateTimeString(),
        ];

        $logTail = $this->logTail(80);
        $lastInbound = Message::where('direction', 'inbound')->latest()->first();
        $lastModelCall = AiLog::latest()->first();

        return view('admin.system.index', compact('checks', 'queue', 'failedJobs', 'tables', 'runtime', 'logTail', 'lastInbound', 'lastModelCall'));
    }

    public function activity(Request $request)
    {
        if (!Schema::hasTable('admin_activity_logs')) {
            return redirect()->route('admin.system.index')->with('error', 'Activity log table missing. Run: php artisan migrate');
        }

        $query = AdminActivityLog::latest();
        if ($request->filled('action')) {
            $query->where('action', 'like', $request->action . '%');
        }
        if ($request->filled('q')) {
            $query->where('description', 'like', '%' . $request->q . '%');
        }

        $logs = $query->paginate(40)->withQueryString();
        $actions = AdminActivityLog::select('action')->distinct()->orderBy('action')->pluck('action')
            ->map(fn($a) => explode('.', $a)[0])->unique()->values();

        return view('admin.system.activity', compact('logs', 'actions'));
    }

    public function runTool(Request $request, string $tool)
    {
        try {
            switch ($tool) {
                case 'clear-cache':
                    Cache::flush();
                    Artisan::call('view:clear');
                    Artisan::call('config:clear');
                    $msg = 'Application, view and config caches cleared.';
                    break;

                case 'retry-failed':
                    Artisan::call('queue:retry', ['id' => ['all']]);
                    $msg = 'All failed jobs pushed back onto the queue.';
                    break;

                case 'flush-failed':
                    Artisan::call('queue:flush');
                    $msg = 'Failed job list cleared.';
                    break;

                case 'run-queue':
                    Artisan::call('queue:work', ['--stop-when-empty' => true, '--tries' => 1]);
                    $msg = 'Queue drained once (pending jobs processed).';
                    break;

                case 'purge-old-messages':
                    $days = max(7, (int) $request->input('days', 90));
                    $cutoff = now()->subDays($days);
                    $count = Message::where('created_at', '<', $cutoff)->count();
                    Message::where('created_at', '<', $cutoff)->delete();
                    $msg = "Deleted {$count} messages older than {$days} days.";
                    break;

                default:
                    return back()->with('error', 'Unknown tool.');
            }

            $output = trim(Artisan::output());
            AdminActivityLog::record("system.{$tool}", $msg, null, $request->except('_token'));

            return back()->with('success', $msg)->with('tool_output', $output);
        } catch (\Throwable $e) {
            return back()->with('error', "Tool failed: " . $e->getMessage());
        }
    }

    private function dbOk(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function logTail(int $lines): string
    {
        $path = storage_path('logs/laravel.log');
        if (!is_file($path)) {
            return 'No log file yet.';
        }

        $size = filesize($path);
        $chunk = min($size, 64 * 1024);
        $fh = fopen($path, 'r');
        fseek($fh, -$chunk, SEEK_END);
        $data = fread($fh, $chunk);
        fclose($fh);

        $all = explode("\n", $data);
        return implode("\n", array_slice($all, -$lines));
    }
}
