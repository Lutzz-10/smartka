<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\TestPackage;
use App\Models\UserSession;
use App\Models\AiDailyUsage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
    $user = Auth::user();

    // ... sisa kode sama
    
        $user = Auth::user();

        // ── Metric cards ─────────────────────────────
        $totalSoal = $user->sessions()
            ->where('status', 'completed')
            ->withCount('answers')
            ->get()
            ->sum('answers_count');

        $avgScore = $user->results()->avg('total_score') ?? 0;

        $totalTryout = $user->sessions()
            ->where('status', 'completed')
            ->count();

        // ── Streak harian ────────────────────────────
        $streak = $this->calculateStreak($user->id);

        // ── Progress mingguan (7 hari) ────────────────
        $weeklyProgress = collect(range(6, 0))->map(function ($daysAgo) use ($user) {
            $date = Carbon::today()->subDays($daysAgo);
            $sessions = UserSession::where('user_id', $user->id)
                ->whereDate('finished_at', $date)
                ->where('status', 'completed')
                ->with('result')
                ->get();

            $avgScore = $sessions->isNotEmpty()
                ? $sessions->map(fn($s) => optional($s->result)->total_score ?? 0)->avg()
                : 0;

            return [
                'date'      => $date->format('D'),
                'score'     => round($avgScore),
                'sessions'  => $sessions->count(),
            ];
        });

        // ── Paket latihan tersedia ────────────────────
        $packages = TestPackage::where('status', 'published')
            ->where('class_level', $user->class_level)
            ->where(function ($q) {
                $q->whereNull('available_until')
                  ->orWhere('available_until', '>=', now());
            })
            ->withCount('questions')
            ->latest()
            ->take(6)
            ->get();

        // ── Rekomendasi AI (topik lemah) ──────────────
        $latestResult  = $user->results()->latest()->first();
        $weakTopics    = $latestResult?->weakness_topics ?? [];

        // ── Kuota AI hari ini ─────────────────────────
        $aiQuota = $user->todayAiQuota();

        return view('dashboard.index', compact(
            'user',
            'totalSoal',
            'avgScore',
            'totalTryout',
            'streak',
            'weeklyProgress',
            'packages',
            'weakTopics',
            'aiQuota',
            'latestResult'
        ));
    }

    // ── Helper: hitung streak ─────────────────────────
    private function calculateStreak(int $userId): int
    {
        $streak = 0;
        $date   = Carbon::today();

        while (true) {
            $hasActivity = UserSession::where('user_id', $userId)
                ->whereDate('finished_at', $date)
                ->where('status', 'completed')
                ->exists();

            if (!$hasActivity) break;

            $streak++;
            $date = $date->subDay();

            if ($streak >= 365) break;
        }

        return $streak;
    }
}