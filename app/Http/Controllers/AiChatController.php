<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiDailyUsage;
use App\Models\Setting;
use App\Models\User;           // ← tambah ini
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function __construct(private GeminiService $gemini)
    {
        // middleware dipindah ke route, tidak perlu disini
    }

    public function index()
    {
        /** @var User $user */   // ← tambah hint ini
        $user     = Auth::user();
        $sessions = AiChatSession::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();

        $aiQuota = $user->todayAiQuota();

        return view('ai-tutor.index', compact('sessions', 'aiQuota'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message'         => 'required|string|max:1000',
            'session_id'      => 'nullable|integer|exists:ai_chat_sessions,id',
            'active_question' => 'nullable|string|max:500',
            'image'           => 'nullable|image|max:4096',
        ]);

        /** @var User $user */   // ← tambah hint ini
        $user      = Auth::user();
        $remaining = null;
        $usage     = null;

        if (!$user->isPremium()) {
            $limit = (int) Setting::get('ai_daily_free_limit', 5);
            $usage = AiDailyUsage::firstOrCreate(
                ['user_id' => $user->id, 'date' => today()->toDateString()],
                ['count'   => 0]
            );

            if ($usage->count >= $limit) {
                return response()->json([
                    'error'     => 'limit_reached',
                    'message'   => 'Kamu sudah menggunakan ' . $limit . ' pertanyaan gratis hari ini.',
                    'remaining' => 0,
                ], 429);
            }
        }

        if ($request->session_id) {
            $session = AiChatSession::where('id', $request->session_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            $session = AiChatSession::create([
                'user_id' => $user->id,
                'title'   => mb_substr($request->message, 0, 60),
            ]);
        }

        $history = AiChatMessage::where('session_id', $session->id)
            ->latest()->limit(10)->get()->reverse()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()->toArray();

        $context = [
            'name'            => $user->name,
            'class_level'     => 'Kelas ' . $user->class_level,
            'avg_score'       => $user->getAverageScore(),
            'weak_topics'     => $user->getWeakTopics(),
            'subscription'    => $user->isPremium() ? 'Premium' : 'Gratis',
            'active_question' => $request->active_question ?? 'tidak ada konteks soal aktif',
        ];

        $imageBase64 = null;
        $imagePath   = null;

        if ($request->hasFile('image')) {
            $file        = $request->file('image');
            $imageBase64 = base64_encode(file_get_contents($file->path()));
            $imagePath   = $file->store('ai-images', 'public');
        }

        try {
            $aiReply = $this->gemini->chat($history, $request->message, $context, $imageBase64);
        } catch (\Exception $e) {
            Log::error('AI Chat error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'api_error', 'message' => $e->getMessage()], 500);
        }

        AiChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'user',
            'content'    => $request->message,
            'image_path' => $imagePath,
        ]);

        $aiMessage = AiChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'model',
            'content'    => $aiReply,
        ]);

        $session->increment('message_count');

        if (!$user->isPremium() && $usage) {
            $usage->increment('count');
            $remaining = max(0, (int) Setting::get('ai_daily_free_limit', 5) - $usage->count);
        }

        return response()->json([
            'reply'      => $aiReply,
            'session_id' => $session->id,
            'message_id' => $aiMessage->id,
            'remaining'  => $remaining,
        ]);
    }

    public function feedback(Request $request, AiChatMessage $message)
    {
        $request->validate(['feedback' => 'required|in:helpful,not_helpful']);

        AiChatSession::where('id', $message->session_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $message->update(['feedback' => $request->feedback]);
        return response()->json(['ok' => true]);
    }

    public function star(AiChatMessage $message)
    {
        AiChatSession::where('id', $message->session_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $message->update(['is_starred' => !$message->is_starred]);
        return response()->json(['starred' => $message->is_starred]);
    }

    public function sessions()
    {
        $sessions = AiChatSession::where('user_id', Auth::id())
            ->latest()->limit(20)
            ->get(['id', 'title', 'message_count', 'created_at']);

        return response()->json($sessions);
    }

    public function sessionMessages(AiChatSession $session)
    {
        if ($session->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = AiChatMessage::where('session_id', $session->id)
            ->oldest()
            ->get(['id', 'role', 'content', 'image_path', 'is_starred', 'feedback', 'created_at']);

        return response()->json(['session' => $session, 'messages' => $messages]);
    }
}