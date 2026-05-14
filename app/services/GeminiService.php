<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.gemini.api_key');
        $this->model   = config('services.gemini.model', 'gemini-2.0-flash');
        $this->baseUrl = config('services.gemini.base_url',
            'https://generativelanguage.googleapis.com/v1beta/models/');
    }

    // ─── Build system prompt ───────────────────────────────
    public function buildSystemPrompt(array $context): string
    {
        // Ambil dari database dulu, fallback ke default
        $basePrompt = Setting::get('ai_system_prompt',
            'Kamu adalah Smartka AI, asisten tutor belajar yang ramah, sabar, dan cerdas untuk siswa Indonesia.'
        );

        $weakTopics = !empty($context['weak_topics'])
            ? implode(', ', $context['weak_topics'])
            : 'belum ada data';

        return $basePrompt . "

═══════════════════════════════════
KONTEKS SISWA SAAT INI:
═══════════════════════════════════
- Nama          : {$context['name']}
- Jenjang       : {$context['class_level']}
- Skor rata-rata: {$context['avg_score']}%
- Topik lemah   : {$weakTopics}
- Status akun   : {$context['subscription']}
- Soal aktif    : {$context['active_question']}

═══════════════════════════════════
PANDUAN MENJAWAB:
═══════════════════════════════════
1. Gunakan Bahasa Indonesia yang ramah, santai, tapi tetap akurat
2. Untuk rumus matematika, tulis dengan format rapi dan langkah-langkah jelas
3. Berikan contoh nyata atau analogi yang mudah dipahami siswa
4. Koreksi kesalahan konsep dengan lembut dan konstruktif
5. Sesekali berikan semangat dan motivasi singkat (tapi jangan berlebihan)
6. JANGAN jawab pertanyaan di luar konteks pendidikan/akademik
7. Jika ditanya soal, berikan pembahasan step-by-step yang jelas
8. Jika ditanya topik, ringkas poin-poin kunci terlebih dahulu
9. Gunakan emoji secukupnya agar lebih menarik (jangan berlebihan)
10. Jawab tepat sasaran, tidak terlalu panjang kecuali diminta detail

FORMAT RESPONS:
- Gunakan **bold** untuk istilah penting
- Gunakan bullet point untuk list
- Gunakan angka untuk langkah-langkah berurutan
- Pisahkan bagian dengan baris kosong agar mudah dibaca";
    }

    // ─── Main chat method ──────────────────────────────────
    public function chat(
        array   $history,
        string  $userMessage,
        array   $context,
        ?string $imageBase64 = null
    ): string {
        if (empty($this->apiKey)) {
            throw new \Exception('Gemini API key belum dikonfigurasi.');
        }

        $systemPrompt = $this->buildSystemPrompt($context);
        $contents     = [];

        // Inject system prompt via user/model turn (Gemini trick)
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $systemPrompt]],
        ];
        $contents[] = [
            'role'  => 'model',
            'parts' => [['text' => 'Siap! Saya Smartka AI, siap membantu belajarmu. Ada yang ingin kamu tanyakan? 😊']],
        ];

        // Riwayat percakapan (max 10 pesan terakhir)
        foreach (array_slice($history, -10) as $msg) {
            $contents[] = [
                'role'  => $msg['role'],
                'parts' => [['text' => $msg['content']]],
            ];
        }

        // Pesan user saat ini
        $parts = [['text' => $userMessage]];

        // Tambahkan gambar jika ada (Gemini Vision)
        if ($imageBase64) {
            $parts[] = [
                'inline_data' => [
                    'mime_type' => 'image/jpeg',
                    'data'      => $imageBase64,
                ],
            ];
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => $parts,
        ];

        $url = $this->baseUrl . $this->model . ':generateContent?key=' . $this->apiKey;

        try {
            $response = Http::timeout(30)->post($url, [
                'contents'         => $contents,
                'generationConfig' => [
                    'temperature'     => 0.7,
                    'maxOutputTokens' => 1024,
                    'topP'            => 0.95,
                    'topK'            => 40,
                ],
                'safetySettings'   => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Gemini HTTP error', ['message' => $e->getMessage()]);
            throw new \Exception('Koneksi ke Smartka AI gagal. Periksa koneksi internetmu ya!');
        }

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            $errorBody = $response->json();
            $errorMsg  = $errorBody['error']['message'] ?? 'Unknown error';

            if ($response->status() === 429) {
                throw new \Exception('Smartka AI sedang sibuk. Coba lagi dalam beberapa detik ya!');
            }

            throw new \Exception('Gagal menghubungi Smartka AI: ' . $errorMsg);
        }

        $reply = $response->json('candidates.0.content.parts.0.text');

        if (empty($reply)) {
            Log::warning('Gemini empty response', ['body' => $response->body()]);
            return 'Maaf, saya tidak bisa menjawab saat ini. Coba tanyakan dengan cara yang berbeda ya! 😊';
        }

        return $reply;
    }

    // ─── Test koneksi API ──────────────────────────────────
    public function testConnection(): bool
    {
        try {
            $reply = $this->chat([], 'Halo!', [
                'name'            => 'Test',
                'class_level'     => 'Kelas 9',
                'avg_score'       => 0,
                'weak_topics'     => [],
                'subscription'    => 'Gratis',
                'active_question' => 'tidak ada',
            ]);
            return !empty($reply);
        } catch (\Exception $e) {
            return false;
        }
    }
}