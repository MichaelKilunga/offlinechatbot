<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessIncomingSms;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    public function inbound(Request $request)
    {
        // Africa's Talking sends 'from' and 'text' in the POST body
        $from = $request->input('from');
        $text = $request->input('text');
        
        // log infomation
        Log::info('Incoming SMS from: ' . $from . ' with text: ' . $text);
        
        if (!$from || !$text) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // Dispatch job immediately to ensure fast response
        ProcessIncomingSms::dispatch($from, $text);

        return response()->json(['status' => 'success']);
    }
}
