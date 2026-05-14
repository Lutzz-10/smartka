<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — SMARTKA Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    h1,h2,h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
    .admin-link.active { background:#1e3a5f; color:#fff; }
    .admin-link:hover  { background:#1e3a5f; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: false }">

  {{-- SIDEBAR --}}
  <aside class="fixed top-0 left-0 h-full w-60 bg-gray-900 z-40 flex flex-col
    transform transition-transform duration-300
    md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-gray-700">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">🚀</div>
        <div>
          <div class="font-extrabold text-white text-base" style="font-family:'Plus Jakarta Sans',sans-serif;">
            SMARTKA
          </div>
          <div class="text-gray-400 text-xs">Admin Panel</div>
        </div>
      </div>
    </div>

    {{-- Admin info --}}
    <div class="px-4 py-3 border-b border-gray-700">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm">👨‍💼</div>
        <div>
          <div class="text-white text-xs font-semibold">{{ auth()->user()->name }}</div>
          <div class="text-gray-400 text-xs">Administrator</div>
        </div>
      </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
      @php
        $currentPath = request()->path();
      @endphp

      @foreach([
        ['admin/dashboard',  '📊', 'Dashboard',     'admin.dashboard'],
        ['admin/soal',       '📝', 'Bank Soal',      'admin.soal.index'],
        ['admin/paket',      '📦', 'Paket Latihan',  'admin.paket.index'],
        ['admin/pengguna',   '👥', 'Pengguna',       'admin.pengguna.index'],
        ['admin/ai-monitor', '🤖', 'AI Monitor',     'admin.ai-monitor'],
      ] as [$path, $icon, $label, $routeName])
      <a href="{{ route($routeName) }}"
        class="admin-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-300 transition
          {{ str_starts_with($currentPath, $path) ? 'active' : '' }}">
        <span class="text-base w-5 text-center">{{ $icon }}</span>
        {{ $label }}
      </a>
      @endforeach

      <div class="pt-3 pb-1">
        <div class="text-gray-500 text-xs uppercase tracking-wider px-3 py-1">Pengaturan</div>
      </div>

      <a href="{{ route('home') }}" target="_blank"
        class="admin-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-300 transition">
        <span class="text-base w-5 text-center">🌐</span> Lihat Website
      </a>
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-3 border-t border-gray-700">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-red-400 hover:bg-red-900/30 transition">
          <span>🚪</span> Keluar
        </button>
      </form>
    </div>
  </aside>

  {{-- Overlay mobile --}}
  <div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/50 z-30 md:hidden" x-transition></div>

  {{-- MAIN --}}
  <div class="md:ml-60 min-h-screen flex flex-col">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-4">
        <button class="md:hidden" @click="sidebarOpen = true">☰</button>
        <div>
          <h1 class="font-bold text-gray-800" style="font-family:'Plus Jakarta Sans',sans-serif;">
            @yield('page-title', 'Dashboard')
          </h1>
          <p class="text-gray-400 text-xs">@yield('page-subtitle', '')</p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold">ADMIN</span>
        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm text-white">👨‍💼</div>
      </div>
    </header>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
      <span>✅</span> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
      <span>⚠️</span> {{ session('error') }}
    </div>
    @endif

    <main class="flex-1 p-6">
      @yield('content')
    </main>

    <footer class="px-6 py-3 border-t border-gray-200 text-xs text-gray-400 text-center">
      © {{ date('Y') }} SMARTKA Admin Panel
    </footer>
  </div>
</body>
</html>