<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $selectedSubject = $request->query('subject');

        // Fetch active subjects in this class level for the filter dropdown
        $subjects = Subject::where('class_level', $user->class_level)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        // ─── Ranking Query ──────────────────────────────────
        // Rank students who are in the same class_level and have at least one test result
        if ($selectedSubject) {
            $query = User::where('role', 'student')
                ->where('class_level', $user->class_level)
                ->join('results', 'users.id', '=', 'results.user_id')
                ->selectRaw("users.id, users.name, users.avatar, ROUND(AVG(CAST(JSON_UNQUOTE(JSON_EXTRACT(results.score_per_subject, '$.\"$selectedSubject\"')) AS DECIMAL(5,2))), 1) as avg_score, COUNT(results.id) as sessions_count")
                ->whereNotNull("results.score_per_subject->{$selectedSubject}")
                ->groupBy('users.id', 'users.name', 'users.avatar')
                ->orderByDesc('avg_score');
        } else {
            $query = User::where('role', 'student')
                ->where('class_level', $user->class_level)
                ->join('results', 'users.id', '=', 'results.user_id')
                ->selectRaw('users.id, users.name, users.avatar, ROUND(AVG(results.total_score), 1) as avg_score, COUNT(results.id) as sessions_count')
                ->groupBy('users.id', 'users.name', 'users.avatar')
                ->orderByDesc('avg_score');
        }

        $allRankings = $query->get();

        // ─── Find Current User Rank ─────────────────────────
        $currentUserRank = null;
        $currentUserAvg = null;
        foreach ($allRankings as $index => $rank) {
            if ($rank->id === $user->id) {
                $currentUserRank = $index + 1;
                $currentUserAvg = $rank->avg_score;
                break;
            }
        }

        // ─── Process Podium (Top 3) & Paginated List ────────
        $podium = $allRankings->take(3)->values();
        $remaining = $allRankings->slice(3)->values();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $remaining->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedRemaining = new LengthAwarePaginator(
            $currentItems,
            $remaining->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query()
            ]
        );

        return view('peringkat.index', compact(
            'subjects',
            'selectedSubject',
            'podium',
            'paginatedRemaining',
            'currentUserRank',
            'currentUserAvg'
        ));
    }
}
