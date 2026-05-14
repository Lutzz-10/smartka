<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — SMARTKA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    h1,h2,h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
    .sidebar-link.active { background: #eff6ff; color: #1a56db; font-weight: 600; }
    .sidebar-link:hover  { background: #f9fafb; }
    ::-webkit-scrollbar       { width: 5px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
  </style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">

  {{-- ── SIDEBAR ────────────────────────────────── --}}
  <aside class="fixed top-0 left-0 h-full w-60 bg-white border-r border-gray-100 shadow-sm z-40 flex flex-col
    transform transition-transform duration-300
    md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-gray-100">
      <a href="/" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">🚀</div>
        <span class="font-extrabold text-lg text-gray-800" style="font-family:'Plus Jakarta Sans',sans-serif;">
          SMART<span class="text-blue-600">KA</span>
        </span>
      </a>
    </div>

    {{-- User info --}}
    <div class="px-4 py-4 border-b border-gray-100">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-lg flex-shrink-0">
          🧑‍🎓
        </div>
        <div class="min-w-0">
          <div class="font-semibold text-sm text-gray-800 truncate">{{ auth()->user()->name }}</div>
          <div class="flex items-center gap-1.5 mt-0.5">
            @if(auth()->user()->isPremium())
            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">PREMIUM</span>
            @else
            <span class="bg-gray-100 text-gray-500 text-xs font-medium px-2 py-0.5 rounded-full">FREE</span>
            @endif
            <span class="text-gray-400 text-xs">Kelas {{ auth()->user()->class_level }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
      @php
        $currentRoute = request()->routeIs('dashboard') ? 'dashboard' :
          (str_starts_with(request()->path(), 'latihan') ? 'latihan' :
          (str_starts_with(request()->path(), 'ai') ? 'ai' :
          (str_starts_with(request()->path(), 'laporan') ? 'laporan' : '')));
      @endphp

      @foreach([
        ['dashboard',        '🏠', 'Beranda',       route('dashboard')],
        ['latihan',          '📝', 'Latihan Soal',  '#'],
        ['tryout',           '⏱️', 'Try Out',        '#'],
        ['laporan',          '📊', 'Laporan',        '#'],
        ['ai',               '🤖', 'AI Tutor',       '#'],
        ['pembahasan',       '📖', 'Pembahasan',     '#'],
        ['peringkat',        '🏆', 'Peringkat',      '#'],
        ['akun',             '⚙️', 'Pengaturan',     '#'],
      ] as [$key, $icon, $label, $href])
      <a href="{{ $href }}"
        class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-600 transition {{ $currentRoute === $key ? 'active' : '' }}">
        <span class="text-lg w-6 text-center">{{ $icon }}</span>
        <span>{{ $label }}</span>
        @if($key === 'ai')
        <span class="ml-auto text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded font-semibold">AI</span>
        @endif
      </a>
      @endforeach
    </nav>

    {{-- Upgrade banner (free user) --}}
    @if(!auth()->user()->isPremium())
    <div class="mx-3 mb-3 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-4 text-white">
      <div class="text-xl mb-2">⚡</div>
      <div class="font-bold text-sm mb-1">Upgrade ke Premium</div>
      <div class="text-blue-200 text-xs mb-3">Soal unlimited & AI Chat tanpa batas!</div>
      <a href="#" class="block text-center bg-white text-blue-700 text-xs font-bold py-2 rounded-xl hover:bg-blue-50 transition">
        Upgrade Sekarang
      </a>
    </div>
    @endif

    {{-- Logout --}}
    <div class="px-3 py-3 border-t border-gray-100">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50 transition">
          <span class="text-lg">🚪</span> Keluar
        </button>
      </form>
    </div>
  </aside>

  {{-- Sidebar overlay mobile --}}
  <div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/40 z-30 md:hidden" x-transition></div>

  {{-- ── MAIN CONTENT ───────────────────────────── --}}
  <div class="md:ml-60 min-h-screen flex flex-col">

    {{-- Top bar --}}
    <header class="sticky top-0 z-20 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-4">
        <button class="md:hidden text-gray-600" @click="sidebarOpen = true">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <div>
          <h1 class="font-bold text-gray-800 text-base" style="font-family:'Plus Jakarta Sans',sans-serif;">
            @yield('page-title', 'Dashboard')
          </h1>
          <p class="text-gray-400 text-xs">@yield('page-subtitle', 'Selamat datang kembali!')</p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        {{-- Notifikasi --}}
        <button class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 hover:bg-gray-200 transition relative">
          🔔
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        {{-- Avatar --}}
        <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center text-base">🧑‍🎓</div>
      </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm flex gap-2">
      <span>✅</span> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex gap-2">
      <span>⚠️</span> {{ session('error') }}
    </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 p-6">
      @yield('content')
    </main>

    {{-- Dalam loop menu, atau tambahkan setelah loop --}}
@if(!auth()->user()->isPremium())
<a href="{{ route('premium') }}"
  class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition
    bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 font-semibold border border-blue-200">
  <span class="text-lg w-6 text-center">⭐</span>
  <span>Upgrade Premium</span>
  <span class="ml-auto text-xs bg-blue-600 text-white px-1.5 py-0.5 rounded font-semibold">HOT</span>
</a>
@endif

    {{-- Footer --}}
    <footer class="px-6 py-4 border-t border-gray-100 text-xs text-gray-400 text-center">
      © {{ date('Y') }} SMARTKA — Belajar Cerdas, Raih Prestasi Terbaik 🚀
    </footer>
  </div>

  {{-- AI Chat Floating Widget --}}
  @auth
    @include('components.ai-chat-widget')
  @endauth

</body>
</html>

</body>
</html>