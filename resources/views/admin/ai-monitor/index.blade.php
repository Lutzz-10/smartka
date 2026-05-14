@extends('layouts.admin')
@section('title', 'AI Monitor')
@section('page-title', 'AI Monitor')
@section('page-subtitle', 'Pantau penggunaan Smartka AI')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  @foreach([
    ['🤖', 'Chat Hari Ini',       $todaySessions,              'blue'],
    ['📅', 'Chat Minggu Ini',     $weekSessions,               'green'],
    ['📊', 'Chat Bulan Ini',      $monthSessions,              'purple'],
    ['⚡', 'Rata-rata/User',      round($avgPerUser, 1) . 'x', 'yellow'],
  ] as [$icon, $label, $value, $color])
  <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
    <div class="text-2xl mb-2">{{ $icon }}</div>
    <div class="text-2xl font-extrabold text-gray-800">{{ $value }}</div>
    <div class="text-gray-400 text-xs mt-0.5">{{ $label }}</div>
  </div>
  @endforeach
</div>

<div class="grid md:grid-cols-3 gap-6 mb-6">

  {{-- Tren 7 hari --}}
  <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h3 class="font-bold text-gray-800 mb-4">Tren Chat AI — 7 Hari Terakhir</h3>
    <div class="flex items-end gap-3 h-28"
      x-data="{
        messages: {{ json_encode($dailyTrend->pluck('messages')->toArray()) }},
        days: {{ json_encode($dailyTrend->pluck('date')->toArray()) }},
        hovered: null
      }">
      <template x-for="(val, i) in messages" :key="i">
        <div class="flex-1 flex flex-col items-center gap-1 relative">
          <div x-show="hovered === i"
            class="absolute -top-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap z-10"
            x-text="val + ' pesan'">
          </div>
          <div class="w-full rounded-t-lg bg-blue-500 hover:bg-blue-600 transition cursor-pointer"
            :style="'height:' + (val > 0 ? Math.max((val / Math.max(...messages)) * 100, 5) : 3) + 'px; max-height:100px;'"
            @mouseenter="hovered = i" @mouseleave="hovered = null">
          </div>
          <span class="text-xs text-gray-400" x-text="days[i]"></span>
        </div>
      </template>
    </div>
  </div>

  {{-- Feedback stats --}}
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h3 class="font-bold text-gray-800 mb-4">Kualitas Jawaban AI</h3>
    @php
      $totalFeedback = $helpfulCount + $notHelpfulCount;
      $helpfulPct    = $totalFeedback > 0 ? round(($helpfulCount / $totalFeedback) * 100) : 0;
    @endphp
    <div class="text-center mb-4">
      <div class="text-4xl font-extrabold text-green-500">{{ $helpfulPct }}%</div>
      <div class="text-gray-400 text-sm">Dinilai Membantu</div>
    </div>
    <div class="space-y-3 text-sm">
      <div class="flex justify-between items-center">
        <span class="flex items-center gap-2 text-gray-600">👍 Membantu</span>
        <span class="font-bold text-green-600">{{ number_format($helpfulCount) }}</span>
      </div>
      <div class="w-full bg-gray-100 rounded-full h-2">
        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $helpfulPct }}%"></div>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center gap-2 text-gray-600">👎 Tidak Membantu</span>
        <span class="font-bold text-red-500">{{ number_format($notHelpfulCount) }}</span>
      </div>
    </div>
    <div class="mt-4 text-xs text-gray-400 text-center">
      Total {{ number_format($totalFeedback) }} feedback diterima
    </div>
  </div>
</div>

{{-- System Prompt Editor --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
  <div class="flex items-center justify-between mb-4">
    <h3 class="font-bold text-gray-800">Pengaturan AI</h3>
    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">
      Gemini {{ config('services.gemini.model') }}
    </span>
  </div>

  <form method="POST" action="{{ route('admin.settings.ai-prompt') }}">
    @csrf @method('PUT')

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1.5">
        System Prompt AI
        <span class="text-gray-400 font-normal">(tersimpan di database, aktif langsung tanpa deploy)</span>
      </label>
      <textarea name="system_prompt" rows="8"
        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono resize-none">{{ $systemPrompt }}</textarea>
    </div>

    <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-1.5">
        Batas Pertanyaan Gratis / Hari
      </label>
      <div class="flex items-center gap-3">
        <input type="number" name="daily_free_limit" value="{{ $dailyFreeLimit }}"
          min="1" max="50"
          class="w-24 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center font-bold">
        <span class="text-gray-500 text-sm">pertanyaan per hari untuk user free</span>
      </div>
    </div>

    <button type="submit"
      class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
      Simpan Pengaturan AI ✓
    </button>
  </form>
</div>

{{-- Log Percakapan --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100">
    <h3 class="font-bold text-gray-800">Log Percakapan Terbaru</h3>
  </div>
  <div class="divide-y divide-gray-50">
    @forelse($recentSessions as $session)
    <div class="px-6 py-4 hover:bg-gray-50 transition">
      <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-sm flex-shrink-0">
            🧑‍🎓
          </div>
          <div class="min-w-0">
            <div class="font-semibold text-gray-800 text-sm">{{ $session->user?->name }}</div>
            <div class="text-gray-500 text-xs truncate">{{ $session->title }}</div>
          </div>
        </div>
        <div class="text-right flex-shrink-0">
          <div class="text-xs text-gray-400">{{ $session->created_at->diffForHumans() }}</div>
          <div class="text-xs font-semibold text-blue-600 mt-0.5">{{ $session->message_count }} pesan</div>
        </div>
      </div>
      @if($session->messages->isNotEmpty())
      <div class="mt-2 ml-11 text-xs text-gray-500 bg-gray-50 rounded-lg p-2 line-clamp-2">
        "{{ Str::limit($session->messages->first()->content, 100) }}"
      </div>
      @endif
    </div>
    @empty
    <div class="px-6 py-10 text-center text-gray-400 text-sm">
      Belum ada percakapan AI
    </div>
    @endforelse
  </div>
  <div class="px-6 py-4 border-t border-gray-100">
    {{ $recentSessions->links() }}
  </div>
</div>

@endsection