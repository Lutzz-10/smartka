@extends('layouts.landing')
@section('title', 'SMARTKA')

@section('content')

{{-- ═══════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════ --}}
<nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 bg-transparent"
  x-data="{ open: false }">
  <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">

    {{-- Logo --}}
    <a href="/" class="flex items-center gap-2.5">
      <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
        <span class="text-white text-lg">🚀</span>
      </div>
      <span class="text-xl font-extrabold text-gray-900" style="font-family:'Plus Jakarta Sans',sans-serif">
        SMART<span class="text-blue-600">KA</span>
      </span>
    </a>

    {{-- Desktop Menu --}}
    <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
      <a href="#fitur"      class="hover:text-blue-600 transition">Fitur</a>
      <a href="#jenjang"    class="hover:text-blue-600 transition">Jenjang</a>
      <a href="#paket"      class="hover:text-blue-600 transition">Paket</a>
      <a href="#ai-tutor"   class="hover:text-blue-600 transition">AI Tutor</a>
      <a href="#testimoni"  class="hover:text-blue-600 transition">Testimoni</a>
    </div>

    {{-- CTA --}}
    <div class="hidden md:flex items-center gap-3">
      <a href="{{ route('login') }}"
        class="text-sm font-semibold text-blue-600 border border-blue-600 px-4 py-2 rounded-xl hover:bg-blue-50 transition">
        Masuk
      </a>
      <a href="{{ route('register') }}"
        class="text-sm font-semibold text-white bg-blue-600 px-5 py-2 rounded-xl hover:bg-blue-700 transition shadow-sm">
        Daftar Gratis ✨
      </a>
    </div>

    {{-- Mobile Hamburger --}}
    <button class="md:hidden text-gray-700" @click="open = !open">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        <path x-show="open"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  {{-- Mobile Menu --}}
  <div x-show="open" x-transition class="md:hidden bg-white border-t border-gray-100 px-6 py-4 space-y-3 shadow-lg">
    <a href="#fitur"     class="block text-sm font-medium text-gray-700 hover:text-blue-600 py-2">Fitur</a>
    <a href="#jenjang"   class="block text-sm font-medium text-gray-700 hover:text-blue-600 py-2">Jenjang</a>
    <a href="#paket"     class="block text-sm font-medium text-gray-700 hover:text-blue-600 py-2">Paket</a>
    <a href="#ai-tutor"  class="block text-sm font-medium text-gray-700 hover:text-blue-600 py-2">AI Tutor</a>
    <div class="flex gap-3 pt-2">
      <a href="{{ route('login') }}"    class="flex-1 text-center border border-blue-600 text-blue-600 text-sm font-semibold py-2.5 rounded-xl">Masuk</a>
      <a href="{{ route('register') }}" class="flex-1 text-center bg-blue-600 text-white text-sm font-semibold py-2.5 rounded-xl">Daftar</a>
    </div>
  </div>
</nav>


{{-- ═══════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════ --}}
<section class="relative min-h-screen flex items-center overflow-hidden"
  style="background: linear-gradient(135deg, #1e3a8a 0%, #1a56db 50%, #0e9f6e 100%);">

  {{-- Decorative blobs --}}
  <div class="absolute top-20 right-10 w-72 h-72 bg-white opacity-5 blob"></div>
  <div class="absolute bottom-10 left-10 w-96 h-96 bg-white opacity-5 blob" style="animation: float 5s ease-in-out infinite;"></div>
  <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-yellow-300 opacity-10 blob float-anim"></div>

  <div class="relative max-w-6xl mx-auto px-6 pt-28 pb-20 grid md:grid-cols-2 gap-12 items-center">

    {{-- Teks --}}
    <div class="text-white fade-in-up">
      {{-- Badge --}}
      <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur border border-white/20 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <span class="text-yellow-300">⭐</span>
        Dipercaya <span class="font-bold">50.000+</span> siswa aktif Indonesia
      </div>

      <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-6" style="font-family:'Plus Jakarta Sans',sans-serif;">
        Persiapkan Ujianmu<br>dengan Cara yang<br>
        <span class="text-yellow-300">Lebih Cerdas 🚀</span>
      </h1>

      <p class="text-blue-100 text-lg leading-relaxed mb-8 max-w-lg">
        Platform latihan soal & try out untuk siswa kelas 6, 9, dan 12.
        Ribuan soal, analisis mendalam, dan bimbingan <strong class="text-white">AI personal</strong> siap 24/7.
      </p>

      <div class="flex flex-wrap gap-4 mb-10">
        <a href="{{ route('register') }}"
          class="bg-white text-blue-700 font-bold px-7 py-3.5 rounded-xl hover:bg-yellow-50 transition shadow-xl text-base">
          Mulai Gratis Sekarang →
        </a>
        <a href="#fitur"
          class="border border-white/40 text-white font-semibold px-7 py-3.5 rounded-xl hover:bg-white/10 transition text-base">
          Lihat Demo ▶
        </a>
      </div>

      {{-- Social proof avatars --}}
      <div class="flex items-center gap-3">
        <div class="flex -space-x-2">
          @foreach(['🧑‍🎓','👩‍🎓','🧑‍💻','👩‍💻','🎓'] as $avatar)
          <div class="w-9 h-9 rounded-full bg-white/20 border-2 border-white flex items-center justify-center text-sm">
            {{ $avatar }}
          </div>
          @endforeach
        </div>
        <div class="text-sm text-blue-100">
          <span class="text-white font-semibold">+50.000 siswa</span> sudah bergabung
        </div>
      </div>
    </div>

    {{-- Ilustrasi / Dashboard Preview --}}
    <div class="hidden md:block relative">
      {{-- Card utama --}}
      <div class="bg-white rounded-2xl shadow-2xl p-6 float-anim">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">📊</div>
          <div>
            <div class="font-bold text-gray-800 text-sm">Dashboard Belajar</div>
            <div class="text-xs text-gray-400">Halo, Budi! 🔥 5 hari streak</div>
          </div>
        </div>
        {{-- Mini chart bars --}}
        <div class="flex items-end gap-2 h-20 mb-3">
          @foreach([40,65,50,80,70,90,85] as $h)
          <div class="flex-1 bg-blue-600 rounded-t-md opacity-80 transition hover:opacity-100"
            style="height:{{ $h }}%"></div>
          @endforeach
        </div>
        <div class="flex justify-between text-xs text-gray-400">
          <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-3 gap-2 text-center">
          <div><div class="font-bold text-blue-600 text-lg">240</div><div class="text-xs text-gray-400">Soal</div></div>
          <div><div class="font-bold text-green-500 text-lg">85%</div><div class="text-xs text-gray-400">Skor</div></div>
          <div><div class="font-bold text-yellow-500 text-lg">#12</div><div class="text-xs text-gray-400">Ranking</div></div>
        </div>
      </div>

      {{-- Card floating AI --}}
      <div class="absolute -bottom-6 -left-10 bg-white rounded-2xl shadow-xl p-4 w-56 float-anim-delay">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
            <span class="text-white text-sm">🤖</span>
          </div>
          <div class="text-xs">
            <div class="font-bold text-gray-700">Smartka AI</div>
            <div class="text-green-500">● Online</div>
          </div>
        </div>
        <div class="bg-blue-50 rounded-xl p-2.5 text-xs text-gray-600 leading-relaxed">
          "Limit fungsi itu artinya nilai yang didekati oleh f(x) saat x mendekati a..."
        </div>
      </div>

      {{-- Card floating skor --}}
      <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-4 float-anim-delay2">
        <div class="text-center">
          <div class="text-3xl font-extrabold text-green-500">95</div>
          <div class="text-xs text-gray-400">Skor Try Out</div>
          <div class="text-xs font-semibold text-green-600 mt-1">🎉 Naik 15 poin!</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Wave bottom --}}
  <div class="absolute bottom-0 left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 80L1440 80L1440 20C1200 80 960 0 720 40C480 80 240 0 0 40L0 80Z" fill="white"/>
    </svg>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     STATS
═══════════════════════════════════════════ --}}
<section class="py-16 bg-white">
  <div class="max-w-5xl mx-auto px-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      @foreach([
        ['50K+',  'Siswa Aktif',        '🧑‍🎓', 'blue'],
        ['200K+', 'Soal Tersedia',       '📝',   'green'],
        ['95%',   'Tingkat Kelulusan',   '🏆',   'yellow'],
        ['500+',  'Sekolah Mitra',       '🏫',   'purple'],
      ] as [$num, $label, $icon, $color])
      <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-blue-50 transition group">
        <div class="text-3xl mb-2">{{ $icon }}</div>
        <div class="text-3xl font-extrabold text-blue-600 mb-1 group-hover:scale-110 transition-transform">
          {{ $num }}
        </div>
        <div class="text-gray-500 text-sm">{{ $label }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     FITUR UNGGULAN
═══════════════════════════════════════════ --}}
<section id="fitur" class="py-20 bg-gray-50">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wide">
        Fitur Unggulan
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">
        Semua yang Kamu Butuhkan<br>untuk Sukses Ujian
      </h2>
      <p class="text-gray-500 max-w-xl mx-auto">
        Dirancang khusus untuk kurikulum Indonesia, dengan teknologi AI terdepan.
      </p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      @foreach([
        ['🧠', 'Latihan Soal Adaptif',    '#1a56db', 'Soal menyesuaikan tingkat kemampuanmu secara otomatis. Makin pintar, makin menantang!',          'Mulai Latihan'],
        ['⏱️', 'Try Out Online',           '#0e9f6e', 'Simulasi ujian nyata dengan timer, kondisi persis seperti hari H. Biasakan diri sebelum ujian!',  'Coba Try Out'],
        ['🤖', 'Analisis AI Kelemahan',    '#8b5cf6', 'Gemini AI menganalisis hasil latihan dan menemukan topik mana yang perlu lebih banyak latihan.',   'Lihat Analisis'],
        ['📊', 'Laporan Detail',           '#f59e0b', 'Pantau perkembangan nilai per mata pelajaran, per bab, dan per topik dalam grafik yang mudah dipahami.', 'Lihat Laporan'],
        ['📖', 'Pembahasan Lengkap',       '#ef4444', 'Setiap soal dilengkapi pembahasan teks dan video step-by-step. Tidak ada soal yang tidak kamu mengerti!', 'Baca Pembahasan'],
        ['💬', 'Chat AI Tutor 24/7',       '#1a56db', 'Tanya soal apapun kapanpun ke Smartka AI. Upload foto soal dari buku dan dapat jawaban instan!',   'Tanya AI'],
      ] as [$icon, $title, $color, $desc, $cta])
      <div class="feature-card bg-white rounded-2xl p-7 shadow-sm border border-gray-100">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-5"
          style="background-color: {{ $color }}18;">
          {{ $icon }}
        </div>
        <h3 class="font-bold text-gray-800 text-lg mb-3">{{ $title }}</h3>
        <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $desc }}</p>
        <span class="text-sm font-semibold" style="color: {{ $color }}">{{ $cta }} →</span>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     JENJANG KELAS
═══════════════════════════════════════════ --}}
<section id="jenjang" class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wide">
        Jenjang Belajar
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">
        Pilih Jenjangmu, <br>Kami Siapkan Jalannya
      </h2>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      {{-- Kelas 6 --}}
      <div class="group border-2 border-gray-100 hover:border-blue-400 rounded-3xl p-8 transition-all hover:shadow-xl">
        <div class="text-6xl mb-5 group-hover:scale-110 transition-transform">🏫</div>
        <div class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-3">SD</div>
        <h3 class="text-xl font-extrabold text-gray-800 mb-2">Kelas 6 SD</h3>
        <p class="text-gray-500 text-sm mb-5">Persiapan seleksi masuk SMP favorit pilihanmu.</p>
        <div class="space-y-2 mb-6">
          @foreach(['📐 Matematika', '🔬 IPA', '📚 Bahasa Indonesia', '🌍 IPS'] as $mapel)
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <span class="text-green-500">✓</span> {{ $mapel }}
          </div>
          @endforeach
        </div>
        <a href="{{ route('register') }}"
          class="block text-center border-2 border-blue-600 text-blue-600 font-semibold py-3 rounded-xl hover:bg-blue-600 hover:text-white transition">
          Mulai Belajar →
        </a>
      </div>

      {{-- Kelas 9 (highlighted) --}}
      <div class="relative border-2 border-blue-500 rounded-3xl p-8 shadow-2xl bg-gradient-to-b from-blue-600 to-blue-700 text-white">
        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-extrabold px-5 py-1.5 rounded-full shadow-md">
          🔥 PALING BANYAK
        </div>
        <div class="text-6xl mb-5">🏢</div>
        <div class="inline-block bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full mb-3">SMP</div>
        <h3 class="text-xl font-extrabold mb-2">Kelas 9 SMP</h3>
        <p class="text-blue-100 text-sm mb-5">Persiapan seleksi masuk SMA/SMK negeri pilihanmu.</p>
        <div class="space-y-2 mb-6">
          @foreach(['📐 Matematika', '🔬 IPA', '🌍 IPS', '📚 Bhs. Indonesia', '🗣️ Bhs. Inggris'] as $mapel)
          <div class="flex items-center gap-2 text-sm text-blue-100">
            <span class="text-green-300">✓</span> {{ $mapel }}
          </div>
          @endforeach
        </div>
        <a href="{{ route('register') }}"
          class="block text-center bg-white text-blue-700 font-bold py-3 rounded-xl hover:bg-yellow-50 transition shadow-md">
          Mulai Belajar →
        </a>
      </div>

      {{-- Kelas 12 --}}
      <div class="group border-2 border-gray-100 hover:border-blue-400 rounded-3xl p-8 transition-all hover:shadow-xl">
        <div class="text-6xl mb-5 group-hover:scale-110 transition-transform">🎓</div>
        <div class="inline-block bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full mb-3">SMA/SMK</div>
        <h3 class="text-xl font-extrabold text-gray-800 mb-2">Kelas 12 SMA</h3>
        <p class="text-gray-500 text-sm mb-5">Persiapan UTBK/SNBT & ujian mandiri PTN terbaik.</p>
        <div class="space-y-2 mb-6">
          @foreach(['🧩 TPS', '📖 Literasi', '📐 Matematika', '🔬 IPA / 🌍 IPS'] as $mapel)
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <span class="text-green-500">✓</span> {{ $mapel }}
          </div>
          @endforeach
        </div>
        <a href="{{ route('register') }}"
          class="block text-center border-2 border-blue-600 text-blue-600 font-semibold py-3 rounded-xl hover:bg-blue-600 hover:text-white transition">
          Mulai Belajar →
        </a>
      </div>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     AI TUTOR SECTION
═══════════════════════════════════════════ --}}
<section id="ai-tutor" class="py-20 bg-gray-900 overflow-hidden relative">
  {{-- Background decoration --}}
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-10 left-10 w-64 h-64 bg-blue-500 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-64 h-64 bg-green-500 rounded-full blur-3xl"></div>
  </div>

  <div class="relative max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">

    {{-- Chat Preview --}}
    <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-2xl border border-gray-700">
      {{-- Chat header --}}
      <div class="bg-blue-600 px-5 py-4 flex items-center gap-3">
        <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">🤖</div>
        <div>
          <div class="text-white font-bold text-sm">Smartka AI</div>
          <div class="text-blue-200 text-xs flex items-center gap-1">
            <span class="w-2 h-2 bg-green-400 rounded-full inline-block"></span> Online 24/7
          </div>
        </div>
        <div class="ml-auto text-xs text-blue-200 bg-white/10 px-2 py-1 rounded-full">
          Didukung Gemini ✨
        </div>
      </div>

      {{-- Chat messages --}}
      <div class="p-5 space-y-4 min-h-64">
        {{-- User bubble --}}
        <div class="flex justify-end">
          <div class="bg-blue-600 text-white text-sm rounded-2xl rounded-tr-sm px-4 py-3 max-w-xs">
            Kak, gimana cara ngerjain soal limit fungsi? Aku bingung banget 😅
          </div>
        </div>

        {{-- AI bubble --}}
        <div class="flex gap-3">
          <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm flex-shrink-0">🤖</div>
          <div class="bg-gray-700 text-gray-100 text-sm rounded-2xl rounded-tl-sm px-4 py-3 max-w-xs leading-relaxed">
            Tenang, Budi! Limit fungsi itu mudah kalau tahu triknya 😊<br><br>
            <strong class="text-blue-300">Rumus dasar:</strong><br>
            lim f(x) = L saat x → a<br><br>
            <strong class="text-blue-300">Langkah 1:</strong> Substitusi langsung<br>
            Coba masukkan nilai x = a ke f(x)
          </div>
        </div>

        {{-- User --}}
        <div class="flex justify-end">
          <div class="bg-blue-600 text-white text-sm rounded-2xl rounded-tr-sm px-4 py-3 max-w-xs">
            Oh! Terus kalau hasilnya 0/0 gimana?
          </div>
        </div>

        {{-- AI typing --}}
        <div class="flex gap-3" x-data="{ dots: 0 }" x-init="setInterval(() => dots = (dots+1)%4, 400)">
          <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm flex-shrink-0">🤖</div>
          <div class="bg-gray-700 text-gray-400 text-sm rounded-2xl rounded-tl-sm px-4 py-3">
            <span>Smartka AI sedang mengetik</span>
            <span x-text="'.'.repeat(dots)" class="text-blue-400"></span>
          </div>
        </div>
      </div>

      {{-- Input bar --}}
      <div class="bg-gray-800 border-t border-gray-700 px-4 py-3 flex items-center gap-3">
        <div class="flex-1 bg-gray-700 rounded-full px-4 py-2 text-gray-400 text-sm">
          Tanya apapun tentang pelajaranmu...
        </div>
        <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700 transition">
          <span class="text-white text-sm">➤</span>
        </div>
      </div>
    </div>

    {{-- Teks kanan --}}
    <div class="text-white">
      <div class="inline-flex items-center gap-2 bg-blue-600/30 border border-blue-500/40 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <span>🤖</span> Didukung Google Gemini AI
      </div>
      <h2 class="text-3xl md:text-4xl font-extrabold mb-6" style="font-family:'Plus Jakarta Sans',sans-serif;">
        Punya Tutor AI yang<br>
        <span class="text-blue-400">Siap Bantu 24/7</span>
      </h2>
      <p class="text-gray-300 text-lg leading-relaxed mb-8">
        Tidak perlu tunggu guru atau les privat mahal. Smartka AI siap menjawab soal apapun kapanpun kamu butuhkan.
      </p>

      <div class="space-y-4 mb-8">
        @foreach([
          ['📸', 'Upload foto soal',          'Foto soal dari buku, langsung dapat pembahasan'],
          ['⚡', 'Jawaban instan',             'Tidak perlu menunggu, dijawab dalam detik'],
          ['📋', 'Step-by-step',              'Penjelasan bertahap, mudah dipahami'],
          ['🎯', 'Disesuaikan ke kamu',       'AI tahu kelemahanmu dan fokus membantu disana'],
        ] as [$icon, $title, $desc])
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 bg-blue-600/30 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
            {{ $icon }}
          </div>
          <div>
            <div class="font-semibold text-white">{{ $title }}</div>
            <div class="text-gray-400 text-sm">{{ $desc }}</div>
          </div>
        </div>
        @endforeach
      </div>

      <a href="{{ route('register') }}"
        class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold px-7 py-3.5 rounded-xl transition shadow-lg">
        Coba AI Tutor Gratis →
      </a>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     TESTIMONI
═══════════════════════════════════════════ --}}
<section id="testimoni" class="py-20 bg-white">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wide">
        Testimoni
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">
        Kata Mereka yang Sudah<br>Berhasil Bersama SMARTKA
      </h2>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      @foreach([
        ['Rina Amelia',      'Kelas 12 SMA',   'SMAN 1 Jakarta',     '5', 'Berkat SMARTKA, nilai UTBK-ku naik drastis! Analisis AI-nya beneran bantu aku fokus ke topik yang lemah. Alhamdulillah keterima di UI! 🎉'],
        ['Budi Santoso',     'Kelas 9 SMP',    'SMPN 3 Bandung',     '5', 'Try out online-nya mirip banget sama ujian asli. Aku jadi lebih percaya diri dan ga panik waktu ujian masuk SMA.'],
        ['Siti Rahayu',      'Kelas 6 SD',     'SDN Merdeka Bekasi', '5', 'Anakku jadi semangat belajar karena soalnya variatif dan ada AI yang bisa ditanya. Nilai ujiannya naik terus!'],
        ['Dimas Pratama',    'Kelas 12 SMA',   'SMAN 5 Surabaya',    '5', 'Chat AI Tutornya gokil! Bisa upload foto soal fisika yang susah dan langsung dapat penjelasan step-by-step. Recommended banget!'],
        ['Annisa Putri',     'Kelas 9 SMP',    'SMPN 1 Yogyakarta',  '5', 'Dashboard laporannya membantu banget. Orang tuaku bisa pantau perkembangan belajarku. Guru les privat di rumah sampai surprise liat nilainya!'],
        ['Farhan Maulana',   'Kelas 12 SMA',   'SMAN 2 Medan',       '5', 'Awalnya ragu soal premium, tapi setelah coba 1 bulan langsung perpanjang. Worth it banget! Soalnya lengkap dan pembahasannya jelas.'],
      ] as [$name, $class, $school, $rating, $quote])
      <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition">
        <div class="flex items-start gap-1 mb-3">
          @for($i = 0; $i < 5; $i++)
          <span class="text-yellow-400 text-sm">⭐</span>
          @endfor
        </div>
        <p class="text-gray-700 text-sm leading-relaxed mb-5 italic">"{{ $quote }}"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-lg">
            🧑‍🎓
          </div>
          <div>
            <div class="font-semibold text-gray-800 text-sm">{{ $name }}</div>
            <div class="text-gray-400 text-xs">{{ $class }} · {{ $school }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     PRICING
═══════════════════════════════════════════ --}}
<section id="paket" class="py-20 bg-gray-50">
  <div class="max-w-5xl mx-auto px-6">
    <div class="text-center mb-14">
      <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wide">
        Harga & Paket
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">
        Mulai Gratis, Upgrade Kapan Saja
      </h2>
      <p class="text-gray-500">Garansi uang kembali 7 hari jika tidak puas.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 items-start">

      {{-- FREE --}}
      <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
        <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Free</div>
        <div class="text-4xl font-extrabold text-gray-800 mb-1">Rp 0</div>
        <div class="text-gray-400 text-sm mb-7">Selamanya gratis</div>
        <ul class="space-y-3 text-sm text-gray-600 mb-8">
          @foreach([
            [true,  '20 soal per hari'],
            [true,  '1 try out per bulan'],
            [true,  '5 pertanyaan AI / hari'],
            [true,  'Analisis dasar'],
            [false, 'Pembahasan video'],
            [false, 'Analisis AI lengkap'],
            [false, 'Laporan orang tua'],
          ] as [$ok, $text])
          <li class="flex items-center gap-2.5">
            <span class="{{ $ok ? 'text-green-500' : 'text-gray-300' }}">{{ $ok ? '✓' : '✗' }}</span>
            <span class="{{ $ok ? '' : 'text-gray-400' }}">{{ $text }}</span>
          </li>
          @endforeach
        </ul>
        <a href="{{ route('register') }}"
          class="block text-center border-2 border-gray-300 text-gray-600 font-semibold py-3 rounded-xl hover:border-blue-400 hover:text-blue-600 transition">
          Mulai Gratis
        </a>
      </div>

      {{-- PREMIUM (highlighted) --}}
      <div class="bg-blue-600 rounded-3xl p-8 shadow-2xl relative -mt-4">
        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-extrabold px-5 py-1.5 rounded-full shadow-md whitespace-nowrap">
          🔥 PALING POPULER
        </div>
        <div class="text-sm font-bold text-blue-200 uppercase tracking-widest mb-3">Premium</div>
        <div class="text-4xl font-extrabold text-white mb-1">Rp 79K</div>
        <div class="text-blue-200 text-sm mb-1">per bulan</div>
        <div class="text-xs text-blue-300 mb-7 line-through">atau Rp 699K/tahun (hemat 26%)</div>
        <ul class="space-y-3 text-sm text-blue-100 mb-8">
          @foreach([
            'Soal tidak terbatas',
            'Try out tak terbatas',
            'AI Chat tanpa batas',
            'Pembahasan video lengkap',
            'Analisis AI mendalam',
            'Hint & bantuan soal',
            'Prioritas dukungan',
          ] as $text)
          <li class="flex items-center gap-2.5">
            <span class="text-green-300">✓</span> {{ $text }}
          </li>
          @endforeach
        </ul>
        <a href="{{ route('register') }}"
          class="block text-center bg-white text-blue-700 font-bold py-3.5 rounded-xl hover:bg-yellow-50 transition shadow-md">
          Mulai Premium →
        </a>
        <p class="text-center text-blue-200 text-xs mt-3">Garansi uang kembali 7 hari</p>
      </div>

      {{-- PREMIUM PLUS --}}
      <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
        <div class="text-sm font-bold text-yellow-600 uppercase tracking-widest mb-3">Premium Plus</div>
        <div class="text-4xl font-extrabold text-gray-800 mb-1">Rp 129K</div>
        <div class="text-gray-400 text-sm mb-7">per bulan</div>
        <ul class="space-y-3 text-sm text-gray-600 mb-8">
          @foreach([
            [true,  'Semua fitur Premium'],
            [true,  'Laporan ke orang tua'],
            [true,  'Konsultasi guru 2x/bulan'],
            [true,  'Prioritas dukungan VIP'],
            [true,  'Akses beta fitur baru'],
          ] as [$ok, $text])
          <li class="flex items-center gap-2.5">
            <span class="text-yellow-500">★</span> {{ $text }}
          </li>
          @endforeach
        </ul>
        <a href="{{ route('register') }}"
          class="block text-center border-2 border-yellow-400 text-yellow-700 font-semibold py-3 rounded-xl hover:bg-yellow-50 transition">
          Pilih Plus
        </a>
      </div>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     CTA BAWAH
═══════════════════════════════════════════ --}}
<section class="py-20 bg-gradient-to-r from-blue-700 to-blue-600 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
  </div>
  <div class="relative max-w-3xl mx-auto px-6 text-center text-white">
    <div class="text-6xl mb-6">🎯</div>
    <h2 class="text-3xl md:text-4xl font-extrabold mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
      Siap Raih Nilai Terbaik<br>dan Masuk Sekolah Impianmu?
    </h2>
    <p class="text-blue-100 text-lg mb-8 max-w-lg mx-auto">
      Bergabung dengan 50.000+ siswa yang sudah belajar lebih cerdas bersama SMARTKA. Gratis selamanya!
    </p>
    <div class="flex flex-wrap gap-4 justify-center">
      <a href="{{ route('register') }}"
        class="bg-white text-blue-700 font-extrabold px-8 py-4 rounded-xl hover:bg-yellow-50 transition shadow-2xl text-lg">
        Daftar Gratis Sekarang ✨
      </a>
      <a href="{{ route('login') }}"
        class="border-2 border-white/50 text-white font-semibold px-8 py-4 rounded-xl hover:bg-white/10 transition text-lg">
        Sudah punya akun? Masuk
      </a>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ --}}
<footer class="bg-gray-900 text-gray-400 pt-16 pb-8">
  <div class="max-w-6xl mx-auto px-6">
    <div class="grid md:grid-cols-4 gap-10 mb-12">
      {{-- Brand --}}
      <div class="md:col-span-2">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">🚀</div>
          <span class="text-white text-xl font-extrabold">SMARTKA</span>
        </div>
        <p class="text-sm leading-relaxed mb-5 max-w-xs">
          Platform belajar cerdas untuk siswa kelas 6, 9, dan 12 Indonesia. Didukung teknologi AI terdepan.
        </p>
        <div class="flex gap-3">
          @foreach(['📘', '📷', '🐦', '▶️'] as $social)
          <div class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-700 transition">
            {{ $social }}
          </div>
          @endforeach
        </div>
      </div>

      {{-- Links --}}
      <div>
        <div class="text-white font-semibold mb-4 text-sm">Platform</div>
        <ul class="space-y-2.5 text-sm">
          @foreach(['Fitur', 'Paket Harga', 'Try Out', 'AI Tutor', 'Blog'] as $link)
          <li><a href="#" class="hover:text-white transition">{{ $link }}</a></li>
          @endforeach
        </ul>
      </div>

      <div>
        <div class="text-white font-semibold mb-4 text-sm">Bantuan</div>
        <ul class="space-y-2.5 text-sm">
          @foreach(['Tentang Kami', 'FAQ', 'Kebijakan Privasi', 'Syarat & Ketentuan', 'Hubungi Kami'] as $link)
          <li><a href="#" class="hover:text-white transition">{{ $link }}</a></li>
          @endforeach
        </ul>
      </div>
    </div>

    <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-3 text-sm">
      <span>© {{ date('Y') }} SMARTKA. All rights reserved.</span>
      <span class="flex items-center gap-1">
        Dibuat dengan ❤️ untuk siswa Indonesia 🇮🇩
      </span>
    </div>
  </div>
</footer>

@endsection