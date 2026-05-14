@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex">

  {{-- KIRI: Ilustrasi --}}
  <div class="hidden lg:flex w-3/5 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 flex-col items-center justify-center p-12 relative overflow-hidden">
    {{-- Dekorasi background --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>

    <div class="relative z-10 text-center text-white max-w-md">
      {{-- Logo --}}
      <div class="flex items-center justify-center gap-3 mb-10">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
          <span class="text-blue-600 text-2xl">🚀</span>
        </div>
        <span class="text-3xl font-extrabold tracking-wide" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
      </div>

      {{-- Ilustrasi emoji besar --}}
      <div class="text-9xl mb-8 select-none">📚</div>

      <h2 class="text-3xl font-bold mb-4" style="font-family:'Plus Jakarta Sans',sans-serif">
        Selamat Datang Kembali,<br>Pejuang Nilai! 💪
      </h2>
      <p class="text-blue-200 text-lg leading-relaxed">
        Ribuan soal, analisis AI, dan try out menunggumu.<br>
        Yuk lanjutkan perjalanan belajarmu!
      </p>

      {{-- Stats --}}
      <div class="flex gap-6 justify-center mt-10">
        <div class="text-center">
          <div class="text-2xl font-bold">50K+</div>
          <div class="text-blue-300 text-sm">Siswa Aktif</div>
        </div>
        <div class="w-px bg-blue-400"></div>
        <div class="text-center">
          <div class="text-2xl font-bold">200K+</div>
          <div class="text-blue-300 text-sm">Soal Tersedia</div>
        </div>
        <div class="w-px bg-blue-400"></div>
        <div class="text-center">
          <div class="text-2xl font-bold">95%</div>
          <div class="text-blue-300 text-sm">Tingkat Lulus</div>
        </div>
      </div>
    </div>
  </div>

  {{-- KANAN: Form Login --}}
  <div class="w-full lg:w-2/5 flex items-center justify-center p-8">
    <div class="w-full max-w-md">

      {{-- Logo mobile --}}
      <div class="flex items-center gap-2 mb-8 lg:hidden">
        <span class="text-2xl">🚀</span>
        <span class="text-xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
      </div>

      <h1 class="text-2xl font-bold text-gray-800 mb-1" style="font-family:'Plus Jakarta Sans',sans-serif">Masuk ke Akun</h1>
      <p class="text-gray-500 mb-6 text-sm">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar gratis</a></p>

      {{-- Alert error --}}
      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">⚠️</span>
        <span>{{ $errors->first() }}</span>
      </div>
      @endif

      {{-- Alert success (dari logout/reset) --}}
      @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">✅</span>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Alert info (OTP) --}}
      @if(session('info'))
      <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-3 text-sm">
        <span class="text-lg">ℹ️</span>
        <span>{{ session('info') }}</span>
      </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" x-data="{ showPass: false }">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
          <input
            type="email" name="email" value="{{ old('email') }}"
            placeholder="nama@email.com"
            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-400 bg-red-50 @enderror"
            required autofocus
          >
        </div>

        {{-- Password --}}
        <div class="mb-4">
          <div class="flex justify-between items-center mb-1.5">
            <label class="text-sm font-medium text-gray-700">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Lupa password?</a>
          </div>
          <div class="relative">
            <input
              :type="showPass ? 'text' : 'password'"
              name="password"
              placeholder="Masukkan password"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
              required
            >
            <button type="button" @click="showPass = !showPass"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <span x-show="!showPass">👁️</span>
              <span x-show="showPass">🙈</span>
            </button>
          </div>
        </div>

        {{-- Remember me --}}
        <div class="flex items-center mb-6">
          <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 rounded border-gray-300">
          <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
        </div>

        {{-- Submit --}}
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
          Masuk ke SMARTKA →
        </button>
      </form>

      <div class="my-5 flex items-center gap-3">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-xs text-gray-400">atau</span>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <p class="text-center text-xs text-gray-400 mt-6">
        Dengan masuk, kamu menyetujui
        <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> SMARTKA.
      </p>
    </div>
  </div>
</div>
@endsection