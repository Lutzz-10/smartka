@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data platform SMARTKA')

@section('content')

{{-- Metric Cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
  @foreach([
    ['📝', 'Total Soal',        number_format($totalSoal),                          'blue'],
    ['👥', 'Pengguna Aktif',    number_format($totalUsers),                         'green'],
    ['⭐', 'User Premium',      number_format($premiumUsers),                       'yellow'],
    ['💰', 'Pendapatan Bulan',  'Rp ' . number_format($revenueMonth/1000, 0) . 'K', 'purple'],
    ['⏱️', 'Try Out Hari Ini',  number_format($tryoutToday),                        'orange'],
    ['🤖', 'AI Chat Hari Ini',  number_format($aiChatToday),                        'blue'],
  ] as [$icon, $label, $value, $color])
  <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
    <div class="text-2xl mb-2">{{ $icon }}</div>
    <div class="text-xl font-extrabold text-gray-800">{{ $value }}</div>
    <div class="text-gray-400 text-xs mt-0.5">{{ $label }}</div>
  </div>
  @endforeach
</div>

{{-- Charts Row --}}
<div class="grid md:grid-cols-3 gap-6 mb-6">

  {{-- Tren pengguna baru --}}
  <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h3 class="font-bold text-gray-800 mb-4">Pengguna Baru — 30 Hari Terakhir</h3>
    <div class="flex items-end gap-1 h-32"
      x-data="{ bars: {{ json_encode($userTrend->pluck('count')->toArray()) }} }">
      <template x-for="(val, i) in bars" :key="i">
        <div class="flex-1 rounded-t-sm bg-blue-500 hover:bg-blue-600 transition cursor-pointer"
          :style="'height:' + (val > 0 ? Math.max((val / Math.max(...bars)) * 100, 5) : 3) + '%'"
          :title="val + ' pengguna'">
        </div>
      </template>
    </div>
    <div class="flex justify-between text-xs text-gray-400 mt-2">
      <span>{{ $userTrend->first()['date'] }}</span>
      <span>{{ $userTrend->last()['date'] }}</span>
    </div>
  </div>

  {{-- Free vs Premium --}}
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h3 class="font-bold text-gray-800 mb-4">Free vs Premium</h3>
    <div class="flex items-center justify-center mb-4">
      <div class="relative w-32 h-32">
        @php
          $total     = $totalUsers ?: 1;
          $premPct   = round(($premiumUsers / $total) * 100);
          $freePct   = 100 - $premPct;
          $circumference = 2 * 3.14159 * 40;
          $premDash  = ($premPct / 100) * $circumference;
          $freeDash  = ($freePct / 100) * $circumference;
        @endphp
        <svg class="w-32 h-32 -rotate-90" viewBox="0 0 100 100">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="12"/>
          <circle cx="50" cy="50" r="40" fill="none" stroke="#1a56db" stroke-width="12"
            stroke-dasharray="{{ $premDash }} {{ $circumference }}"
            stroke-linecap="round"/>
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
          <div class="text-2xl font-extrabold text-blue-600">{{ $premPct }}%</div>
          <div class="text-xs text-gray-400">Premium</div>
        </div>
      </div>
    </div>
    <div class="space-y-2 text-sm">
      <div class="flex justify-between items-center">
        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-blue-600 rounded-full"></span>Premium</span>
        <span class="font-semibold">{{ number_format($premiumUsers) }}</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-gray-200 rounded-full"></span>Free</span>
        <span class="font-semibold">{{ number_format($freeUsers) }}</span>
      </div>
    </div>
  </div>
</div>

{{-- Revenue + Hardest Questions --}}
<div class="grid md:grid-cols-2 gap-6">

  {{-- Revenue trend --}}
  <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <h3 class="font-bold text-gray-800 mb-4">Pendapatan 6 Bulan Terakhir</h3>
    <div class="flex items-end gap-3 h-32"
      x-data="{ bars: {{ json_encode($revenueTrend->pluck('amount')->toArray()) }}, months: {{ json_encode($revenueTrend->pluck('month')->toArray()) }} }">
      <template x-for="(val, i) in bars" :key="i">
        <div class="flex-1 flex flex-col items-center gap-1">
          <div class="w-full rounded-t-lg bg-green-500 hover:bg-green-600 transition"
            :style="'height:' + (val > 0 ? Math.max((val / Math.max(...bars)) * 100, 5) : 3) + 'px; max-height:100px;'"
            :title="'Rp ' + val.toLocaleString('id-ID')">
          </div>
          <span class="text-xs text-gray-400" x-text="months[i].substring(0,3)"></span>
        </div>
      </template>
    </div>
  </div>

  {{-- Soal tersulit --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
      <h3 class="font-bold text-gray-800">Soal Paling Banyak Salah</h3>
      <span class="text-xs text-gray-400">Top 10</span>
    </div>
    <div class="divide-y divide-gray-50">
      @forelse($hardestQuestions as $i => $q)
      <div class="px-6 py-3 flex items-center gap-3 hover:bg-gray-50 transition">
        <span class="text-xs font-bold text-gray-400 w-5">{{ $i + 1 }}</span>
        <div class="flex-1 min-w-0">
          <div class="text-sm text-gray-700 truncate">
            {{ Str::limit(strip_tags($q->question_text), 55) }}
          </div>
          <div class="text-xs text-gray-400 mt-0.5">{{ $q->subject_name }}</div>
        </div>
        <div class="text-right flex-shrink-0">
          <div class="text-sm font-bold text-red-500">{{ $q->wrong_count }}✗</div>
          <div class="text-xs text-gray-400">dari {{ $q->total_attempts }}</div>
        </div>
      </div>
      @empty
      <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data jawaban</div>
      @endforelse
    </div>
  </div>
</div>

@endsection