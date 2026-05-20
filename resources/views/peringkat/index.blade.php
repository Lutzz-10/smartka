@extends('layouts.app')

@section('title', 'Peringkat Belajar')
@section('page-title', 'Papan Peringkat')
@section('page-subtitle', 'Pantau peringkat belajarmu dan raih posisi terbaik!')

@section('content')
<div class="space-y-6">

  {{-- ── 1. FILTER & HEADER SECTION ────────────────────────── --}}
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
    <div>
      <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
        🏆 Klasemen Liga Belajar SMARTKA
      </h2>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Peringkat dihitung berdasarkan nilai rata-rata latihan soal pada jenjang Kelas {{ auth()->user()->class_level }}
      </p>
    </div>
    
    {{-- Subject Filter Dropdown --}}
    <form action="{{ route('peringkat.index') }}" method="GET" id="filterForm" class="w-full md:w-auto">
      <div class="relative">
        <select name="subject" onchange="document.getElementById('filterForm').submit()"
          class="w-full md:w-64 bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-4 py-3 pr-8 font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer transition dark:bg-gray-700 dark:border-gray-600 dark:text-white">
          <option value="">🌍 Semua Mata Pelajaran</option>
          @foreach($subjects as $subj)
            <option value="{{ $subj->name }}" {{ $selectedSubject === $subj->name ? 'selected' : '' }}>
              {{ $subj->name }}
            </option>
          @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
          ▼
        </div>
      </div>
    </form>
  </div>

  {{-- ── 2. PERSONAL RANKING SUMMARY CARD (PREMIUM OUTLINE) ── --}}
  <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
    <div class="absolute right-0 top-0 opacity-10 transform translate-x-12 -translate-y-12">
      <span class="text-[180px] font-bold">🏆</span>
    </div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-white/10">
          @if($currentUserRank === 1)
            🥇
          @elseif($currentUserRank === 2)
            🥈
          @elseif($currentUserRank === 3)
            🥉
          @else
            ⚡
          @endif
        </div>
        <div>
          <span class="text-xs uppercase tracking-wider font-semibold text-blue-200">Kartu Prestasi Kamu</span>
          <h3 class="text-xl font-bold mt-0.5">{{ auth()->user()->name }}</h3>
          <p class="text-sm text-blue-100 mt-1">
            @if($currentUserRank)
              @if($currentUserRank <= 3)
                Luar biasa! Kamu berhasil menempati posisi podium! Pertahankan prestasimu! 🎉
              @elseif($currentUserRank <= 10)
                Hebat sekali! Kamu berada di kelompok 10 besar sekolah! Sedikit lagi menuju podium! 🚀
              @else
                Peringkatmu sudah bagus! Tingkatkan frekuensi latihan untuk merangsek ke Top 10! 💪
              @endif
            @else
              Kamu belum memiliki catatan latihan. Ayo selesaikan 1 paket soal sekarang! 📝
            @endif
          </p>
        </div>
      </div>

      <div class="flex items-center gap-6 divide-x divide-white/20">
        <div class="text-center md:text-left">
          <div class="text-xs text-blue-200">Peringkat</div>
          <div class="text-3xl font-extrabold tracking-tight mt-1">
            {{ $currentUserRank ? '#' . $currentUserRank : '—' }}
          </div>
        </div>
        <div class="pl-6 text-center md:text-left">
          <div class="text-xs text-blue-200">Nilai Rata-rata</div>
          <div class="text-3xl font-extrabold tracking-tight mt-1">
            {{ $currentUserAvg ? number_format($currentUserAvg, 1) : '0.0' }}
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($podium->isEmpty() && $paginatedRemaining->isEmpty())
    {{-- EMPTY STATE --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm dark:bg-gray-800 dark:border-gray-700">
      <div class="text-6xl mb-4">💤</div>
      <h3 class="text-lg font-bold text-gray-800 dark:text-white">Belum Ada Peringkat Terdata</h3>
      <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-md mx-auto">
        Belum ada siswa di jenjang Kelas {{ auth()->user()->class_level }} yang menyelesaikan latihan soal untuk mata pelajaran ini. Jadilah yang pertama!
      </p>
      <a href="{{ route('latihan.index') }}" 
         class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm">
        📝 Mulai Latihan Soal
      </a>
    </div>
  @else
    {{-- ── 3. VISUAL PODIUM (TOP 3) ────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end mt-4">
      
      {{-- SECOND PLACE (Rank 2) --}}
      <div class="order-2 md:order-1 bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm relative group hover:border-blue-200 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600">
        @if($podium->has(1))
          <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-slate-100 border-2 border-slate-300 text-slate-700 rounded-full w-8 h-8 flex items-center justify-center font-bold text-xs">
            2
          </div>
          <div class="relative w-20 h-20 mx-auto mt-2">
            <div class="w-full h-full bg-slate-100 rounded-2xl flex items-center justify-center text-4xl shadow-inner border border-slate-200">
              🧑‍🎓
            </div>
            <div class="absolute -bottom-2 -right-2 bg-slate-400 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-md">
              🥈
            </div>
          </div>
          <h4 class="font-bold text-gray-800 dark:text-white mt-4 truncate">{{ $podium[1]->name }}</h4>
          <span class="inline-block bg-slate-50 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-lg mt-1 dark:bg-gray-700 dark:text-slate-300">
            Skor: {{ number_format($podium[1]->avg_score, 1) }}
          </span>
          <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">
            {{ $podium[1]->sessions_count }} Sesi Latihan
          </div>
        @else
          {{-- Empty Rank 2 placeholder --}}
          <div class="py-8">
            <div class="text-gray-300 text-3xl">🥈</div>
            <p class="text-xs text-gray-400 mt-2">Peringkat 2 Belum Tersedia</p>
          </div>
        @endif
      </div>

      {{-- FIRST PLACE (Rank 1 - Raised & Highlighted) --}}
      <div class="order-1 md:order-2 bg-gradient-to-b from-yellow-50 to-white border-2 border-amber-300 rounded-2xl p-8 text-center shadow-md relative group hover:scale-[1.03] transition-all duration-300 dark:from-yellow-950/20 dark:to-gray-800 dark:border-amber-600">
        @if($podium->has(0))
          <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-amber-400 border-2 border-white text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-sm shadow-md">
            1
          </div>
          <div class="relative w-24 h-24 mx-auto mt-2">
            <div class="w-full h-full bg-amber-100 rounded-2xl flex items-center justify-center text-5xl shadow-inner border border-amber-200">
              👑
            </div>
            <div class="absolute -bottom-2 -right-2 bg-amber-400 text-white text-xs font-bold w-8 h-8 rounded-full flex items-center justify-center shadow-md text-lg">
              🥇
            </div>
          </div>
          <h4 class="font-extrabold text-gray-900 dark:text-white mt-4 text-lg truncate">{{ $podium[0]->name }}</h4>
          <span class="inline-block bg-amber-100 text-amber-800 text-sm font-bold px-3 py-1 rounded-full mt-1 dark:bg-amber-950 dark:text-amber-300">
            Skor: {{ number_format($podium[0]->avg_score, 1) }}
          </span>
          <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            ⭐ Bintang Pelajar Kelas {{ auth()->user()->class_level }}
          </div>
          <div class="text-xs text-gray-400 mt-1">
            {{ $podium[0]->sessions_count }} Sesi Latihan
          </div>
        @else
          <div class="py-12">
            <div class="text-gray-300 text-4xl">🥇</div>
            <p class="text-xs text-gray-400 mt-2">Peringkat 1 Belum Tersedia</p>
          </div>
        @endif
      </div>

      {{-- THIRD PLACE (Rank 3) --}}
      <div class="order-3 md:order-3 bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm relative group hover:border-amber-100 transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600">
        @if($podium->has(2))
          <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-amber-50 border-2 border-amber-200 text-amber-700 rounded-full w-8 h-8 flex items-center justify-center font-bold text-xs">
            3
          </div>
          <div class="relative w-20 h-20 mx-auto mt-2">
            <div class="w-full h-full bg-amber-50/50 rounded-2xl flex items-center justify-center text-4xl shadow-inner border border-amber-100">
              🧑‍🎓
            </div>
            <div class="absolute -bottom-2 -right-2 bg-amber-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-md">
              🥉
            </div>
          </div>
          <h4 class="font-bold text-gray-800 dark:text-white mt-4 truncate">{{ $podium[2]->name }}</h4>
          <span class="inline-block bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-lg mt-1 dark:bg-gray-700 dark:text-amber-300">
            Skor: {{ number_format($podium[2]->avg_score, 1) }}
          </span>
          <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">
            {{ $podium[2]->sessions_count }} Sesi Latihan
          </div>
        @else
          {{-- Empty Rank 3 placeholder --}}
          <div class="py-8">
            <div class="text-gray-300 text-3xl">🥉</div>
            <p class="text-xs text-gray-400 mt-2">Peringkat 3 Belum Tersedia</p>
          </div>
        @endif
      </div>

    </div>

    {{-- ── 4. REMAINING RANKS LIST (Rank 4+) ───────────────── --}}
    @if(!$paginatedRemaining->isEmpty())
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
          <h3 class="font-bold text-gray-800 dark:text-white">Peringkat Selanjutnya</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider dark:bg-gray-700 dark:text-gray-300">
                <th class="py-4 px-6 text-center w-20">Peringkat</th>
                <th class="py-4 px-6">Nama Siswa</th>
                <th class="py-4 px-6 text-center">Jenjang</th>
                <th class="py-4 px-6 text-center">Sesi Latihan</th>
                <th class="py-4 px-6 text-right">Nilai Rata-rata</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
              @foreach($paginatedRemaining as $index => $row)
                @php
                  // Compute actual global rank based on page number
                  $globalRank = 4 + (($paginatedRemaining->currentPage() - 1) * $paginatedRemaining->perPage()) + $index;
                  $isCurrentUser = $row->id === auth()->id();
                @endphp
                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/50 {{ $isCurrentUser ? 'bg-blue-50/50 dark:bg-blue-950/20 font-medium' : '' }}">
                  <td class="py-4 px-6 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $isCurrentUser ? 'bg-blue-600 text-white font-bold shadow-sm' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                      #{{ $globalRank }}
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center text-sm dark:bg-gray-700">
                        🧑‍🎓
                      </div>
                      <div>
                        <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                          {{ $row->name }}
                          @if($isCurrentUser)
                            <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full dark:bg-blue-950 dark:text-blue-300">KM</span>
                          @endif
                        </span>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full dark:bg-gray-700 dark:text-gray-300">
                      Kelas {{ auth()->user()->class_level }}
                    </span>
                  </td>
                  <td class="py-4 px-6 text-center font-medium">
                    {{ $row->sessions_count }} Sesi
                  </td>
                  <td class="py-4 px-6 text-right font-bold text-gray-900 dark:text-white">
                    <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-lg dark:bg-green-950/30 dark:text-green-400">
                      {{ number_format($row->avg_score, 1) }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        {{-- PAGINATION CONTAINER --}}
        @if($paginatedRemaining->hasPages())
          <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between dark:bg-gray-700/50 dark:border-gray-700">
            <div class="text-xs text-gray-500 dark:text-gray-400">
              Menampilkan {{ $paginatedRemaining->firstItem() }} - {{ $paginatedRemaining->lastItem() }} dari {{ $paginatedRemaining->total() }} siswa selanjutnya.
            </div>
            <div>
              {{ $paginatedRemaining->links() }}
            </div>
          </div>
        @endif
      </div>
    @endif
  @endif

</div>
@endsection
