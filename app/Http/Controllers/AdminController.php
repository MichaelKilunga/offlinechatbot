<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Message;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalMessages = Message::count();
        $messagesToday = Message::whereDate('created_at', now()->today())->count();
        
        $interactions = Message::with(['user', 'aiLog'])
            ->latest()
            ->paginate(20);

        return view('admin.dashboard', compact('totalUsers', 'totalMessages', 'messagesToday', 'interactions'));
    }
}
