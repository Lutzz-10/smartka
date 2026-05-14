<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiDailyUsage;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiMonitorController extends Controller
{
    public function index()
    {
        // ── Statistik penggunaan ──────────────────────
        $todaySessions  = AiChatSession::whereDate('created_at', today())->count();
        $weekSessions   = AiChatSession::whereBetween('created_at', [
            now()->startOfWeek(), now()->endOfWeek()
        ])->count();
        $monthSessions  = AiChatSession::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        $todayMessages  = AiChatMessage::whereDate('created_at', today())->count();
        $avgPerUser     = AiDailyUsage::where('date', today())->avg('count') ?? 0;

        // ── Tren 7 hari ───────────────────────────────
        $dailyTrend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'date'     => $date->format('d/m'),
                'sessions' => AiChatSession::whereDate('created_at', $date)->count(),
                'messages' => AiChatMessage::whereDate('created_at', $date)->count(),
            ];
        });

        // ── Feedback stats ────────────────────────────
        $helpfulCount    = AiChatMessage::where('feedback', 'helpful')->count();
        $notHelpfulCount = AiChatMessage::where('feedback', 'not_helpful')->count();

        // ── Log percakapan terbaru ────────────────────
        $recentSessions = AiChatSession::with(['user', 'messages' => fn($q) =>
                $q->latest()->limit(1)
            ])
            ->latest()
            ->paginate(15);

        // ── System prompt dari settings ───────────────
        $systemPrompt    = Setting::get('ai_system_prompt', '');
        $dailyFreeLimit  = Setting::get('ai_daily_free_limit', 5);

        return view('admin.ai-monitor.index', compact(
            'todaySessions', 'weekSessions', 'monthSessions',
            'todayMessages', 'avgPerUser', 'dailyTrend',
            'helpfulCount', 'notHelpfulCount',
            'recentSessions', 'systemPrompt', 'dailyFreeLimit'
        ));
    }

    public function updatePrompt(Request $request)
    {
        $request->validate([
            'system_prompt'   => 'required|string|min:20',
            'daily_free_limit'=> 'required|integer|min:1|max:50',
        ]);

        Setting::where('key', 'ai_system_prompt')
            ->update(['value' => $request->system_prompt]);

        Setting::where('key', 'ai_daily_free_limit')
            ->update(['value' => $request->daily_free_limit]);

        return back()->with('success', 'Pengaturan AI berhasil disimpan!');
    }
}