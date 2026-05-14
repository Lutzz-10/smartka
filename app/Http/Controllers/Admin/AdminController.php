<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiDailyUsage;
use App\Models\Payment;
use App\Models\Question;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // ── Metric cards ──────────────────────────────
        $totalSoal      = Question::where('status', 'active')->count();
        $totalUsers     = User::where('role', 'student')->count();
        $premiumUsers   = User::where('role', 'student')
            ->whereIn('subscription_status', ['premium', 'premium_plus'])
            ->count();
        $revenueMonth   = Payment::where('status', 'success')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at',  now()->year)
            ->sum('amount');
        $tryoutToday    = UserSession::whereDate('started_at', today())->count();
        $aiChatToday    = AiChatSession::whereDate('created_at', today())->count();

        // ── Tren pengguna baru 30 hari ────────────────
        $userTrend = collect(range(29, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'date'  => $date->format('d/m'),
                'count' => User::whereDate('created_at', $date)
                    ->where('role', 'student')
                    ->count(),
            ];
        });

        // ── Distribusi per jenjang ────────────────────
        $jenjangDist = User::where('role', 'student')
            ->select('class_level', DB::raw('count(*) as total'))
            ->groupBy('class_level')
            ->pluck('total', 'class_level');

        // ── Free vs Premium ───────────────────────────
        $freeUsers = $totalUsers - $premiumUsers;

        // ── 10 soal dengan salah terbanyak ────────────
        $hardestQuestions = DB::table('user_answers')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->select(
                'questions.id',
                'questions.question_text',
                'subjects.name as subject_name',
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('SUM(CASE WHEN user_answers.is_correct = 0 THEN 1 ELSE 0 END) as wrong_count')
            )
            ->groupBy('questions.id', 'questions.question_text', 'subjects.name')
            ->orderByDesc('wrong_count')
            ->limit(10)
            ->get();

        // ── Pendapatan 6 bulan terakhir ───────────────
        $revenueTrend = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = Carbon::today()->subMonths($monthsAgo);
            return [
                'month'  => $date->format('M Y'),
                'amount' => Payment::where('status', 'success')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at',  $date->year)
                    ->sum('amount'),
            ];
        });

        return view('admin.dashboard', compact(
            'totalSoal', 'totalUsers', 'premiumUsers', 'revenueMonth',
            'tryoutToday', 'aiChatToday', 'userTrend', 'jenjangDist',
            'freeUsers', 'hardestQuestions', 'revenueTrend'
        ));
    }
}