<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Message;
use App\Models\AdminActivityLog;

class ConversationController extends Controller
{
    private function filtered(Request $request)
    {
        $query = Message::with(['user', 'aiLog']);

        if ($request->filled('phone')) {
            $query->whereHas('user', fn($q) => $q->where('phone_number', 'like', '%' . $request->phone . '%'));
        }
        if ($request->filled('direction') && in_array($request->direction, ['inbound', 'outbound'])) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('q')) {
            $query->where('content', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->type === 'moderated') {
            $query->where(function ($w) {
                $w->where('content', 'like', 'ONYO%')
                  ->orWhere('content', 'like', 'WARNING%')
                  ->orWhere('content', 'like', 'HUDUMA IMEFUNGIWA%')
                  ->orWhere('content', 'like', 'SERVICE BANNED%');
            });
        } elseif ($request->type === 'ai') {
            $query->has('aiLog');
        } elseif ($request->type === 'no_ai') {
            $query->where('direction', 'outbound')->doesntHave('aiLog');
        }

        return $query;
    }

    public function index(Request $request)
    {
        $messages = $this->filtered($request)->latest()->paginate(30)->withQueryString();
        $total = Message::count();

        return view('admin.conversations.index', compact('messages', 'total'));
    }

    public function show(Message $message)
    {
        $message->load(['user', 'aiLog']);

        // Surrounding context: the 6 messages before and after in the same conversation
        $before = Message::where('user_id', $message->user_id)->where('id', '<', $message->id)->latest('id')->take(6)->get()->reverse();
        $after  = Message::where('user_id', $message->user_id)->where('id', '>', $message->id)->oldest('id')->take(6)->get();

        return view('admin.conversations.show', compact('message', 'before', 'after'));
    }

    public function destroy(Message $message)
    {
        $phone = $message->user?->phone_number;
        $message->delete();

        AdminActivityLog::record('message.deleted', "Deleted message #{$message->id} from {$phone}", null, ['message_id' => $message->id]);

        return redirect()->route('admin.conversations.index')->with('success', 'Message deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request)->oldest();
        $filename = 'hurulearn-conversations-' . now()->format('Ymd-His') . '.csv';

        AdminActivityLog::record('conversations.exported', 'Exported conversations to CSV', null, $request->only(['phone', 'direction', 'q', 'from', 'to', 'type']));

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'time', 'phone', 'direction', 'content', 'model', 'prompt_tokens', 'completion_tokens', 'total_tokens']);
            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $m) {
                    fputcsv($out, [
                        $m->id,
                        $m->created_at->toDateTimeString(),
                        $m->user?->phone_number,
                        $m->direction,
                        $m->content,
                        $m->aiLog?->model,
                        $m->aiLog?->prompt_tokens,
                        $m->aiLog?->completion_tokens,
                        $m->aiLog?->total_tokens,
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
