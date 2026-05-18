@extends('layouts.auth')
@section('title', 'Daftar Gratis')

@section('content')
<div class="min-h-screen flex"
  x-data="{
    step: 1,
    name: '{{ old('name') }}',
    email: '{{ old('email') }}',
    phone: '{{ old('phone') }}',
    password: '',
    password_confirmation: '',
    class_level: '{{ old('class_level') }}',
    showPass: false,
    showPassConfirm: false,
    passwordStrength: 0,
    checkStrength(p) {
      let s = 0;
      if (p.length >= 8) s++;
      if (/[A-Z]/.test(p)) s++;
      if (/[0-9]/.test(p)) s++;
      if (/[^A-Za-z0-9]/.test(p)) s++;
      this.passwordStrength = s;
    }
  }">

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
      <div class="text-9xl mb-8 select-none">🎯</div>

      <h2 class="text-3xl font-bold mb-4" style="font-family:'Plus Jakarta Sans',sans-serif">
        Mulai Perjalanan<br>Prestasimu Disini! 🌟
      </h2>
      <p class="text-blue-200 text-lg leading-relaxed">
        Ribuan soal, analisis AI, dan try out menunggumu.<br>
        Daftar sekarang dan nikmati fitur gratis!
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

  {{-- KANAN: Form Register --}}
  <div class="w-full lg:w-2/5 flex items-start justify-center p-8 bg-white overflow-y-auto h-screen py-12 lg:py-16">
    <div class="w-full max-w-md">

      {{-- Logo mobile --}}
      <div class="flex items-center gap-2 mb-8 lg:hidden justify-center">
        <span class="text-2xl">🚀</span>
        <span class="text-xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
      </div>

      {{-- Stepper --}}
      <div class="flex items-center justify-center mb-6 gap-2">
        <template x-for="i in 2" :key="i">
          <div class="flex items-center">
            <div class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold transition-all"
              :class="step >= i ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-500'">
              <span x-show="step > i">✓</span>
              <span x-show="step <= i" x-text="i"></span>
            </div>
            <div x-show="i < 2" class="w-16 h-1 mx-1 rounded transition-all"
              :class="step > i ? 'bg-blue-600' : 'bg-gray-200'"></div>
          </div>
        </template>
      </div>
      

      {{-- Error global --}}
      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm flex items-start gap-3">
        <span class="text-lg">⚠️</span>
        <span>{{ $errors->first() }}</span>
      </div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="hidden" name="grade_level" x-model="class_level">

        {{-- ── STEP 1: Data Diri ── --}}
        <div x-show="step === 1" x-transition>
          <h1 class="text-2xl font-bold text-gray-800 mb-1" style="font-family:'Plus Jakarta Sans',sans-serif">Buat Akun Baru</h1>
          <p class="text-gray-500 text-sm mb-6">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk</a></p>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
            <input type="text" x-model="name" placeholder="Contoh: Budi Santoso"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" x-model="email" placeholder="nama@email.com"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor HP</label>
            <input type="tel" x-model="phone" placeholder="08xxxxxxxxxx"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <div class="relative">
              <input :type="showPass ? 'text' : 'password'" x-model="password"
                @input="checkStrength($event.target.value)"
                placeholder="Minimal 8 karakter"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
              <button type="button" @click="showPass = !showPass"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <span x-show="!showPass">👁️</span><span x-show="showPass">🙈</span>
              </button>
            </div>
            {{-- Password strength --}}
            <div class="flex gap-1 mt-2" x-show="password.length > 0">
              <template x-for="i in 4" :key="i">
                <div class="flex-1 h-1.5 rounded-full transition-all"
                  :class="passwordStrength >= i
                    ? (passwordStrength <= 1 ? 'bg-red-400' : passwordStrength <= 2 ? 'bg-yellow-400' : 'bg-green-500')
                    : 'bg-gray-200'">
                </div>
              </template>
            </div>
            <p class="text-xs mt-1 transition" x-show="password.length > 0"
              :class="passwordStrength <= 1 ? 'text-red-500' : passwordStrength <= 2 ? 'text-yellow-600' : 'text-green-600'"
              x-text="passwordStrength <= 1 ? 'Lemah' : passwordStrength <= 2 ? 'Sedang' : passwordStrength <= 3 ? 'Kuat' : 'Sangat Kuat'">
            </p>
          </div>

          <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
            <div class="relative">
              <input :type="showPassConfirm ? 'text' : 'password'" x-model="password_confirmation"
                placeholder="Ulangi password"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
              <button type="button" @click="showPassConfirm = !showPassConfirm"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <span x-show="!showPassConfirm">👁️</span><span x-show="showPassConfirm">🙈</span>
              </button>
            </div>
            <p class="text-xs mt-1 text-red-500"
              x-show="password_confirmation.length > 0 && password !== password_confirmation">
              Password tidak cocok
            </p>
          </div>

          <div class="flex items-start gap-2 mb-6">
            <input type="checkbox" name="terms" id="terms" class="mt-0.5 w-4 h-4 text-blue-600 rounded border-gray-300" required>
            <label for="terms" class="text-sm text-gray-600">
              Saya menyetujui <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a> SMARTKA
            </label>
          </div>

          <button type="button" @click="step = 2"
            :disabled="!name || !email || !phone || !password || password !== password_confirmation"
            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
            Lanjut — Pilih Jenjang →
          </button>
        </div>

        {{-- ── STEP 2: Pilih Jenjang ── --}}
        <div x-show="step === 2" style="display: none;" x-transition>
          <h2 class="text-xl font-bold text-gray-800 mb-1" style="font-family:'Plus Jakarta Sans',sans-serif">Kamu Kelas Berapa?</h2>
          <p class="text-gray-500 text-sm mb-6">Pilih jenjang untuk mendapatkan soal yang sesuai.</p>

          <div class="grid gap-4 mb-8">
            {{-- Kelas 6 --}}
            <div @click="class_level = '6'"
              :class="class_level === '6' ? 'border-blue-600 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'"
              class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
              <div class="flex items-center gap-4">
                <div class="text-4xl">🏫</div>
                <div class="flex-1">
                  <div class="font-bold text-gray-800 text-base">Kelas 6 SD</div>
                  <div class="text-xs text-gray-500 mt-0.5">Seleksi masuk SMP favorit</div>
                  <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Matematika</span>
                    <span class="bg-green-100 text-green-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">IPA</span>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">B. Indonesia</span>
                  </div>
                </div>
                <div x-show="class_level === '6'" class="text-blue-600 text-xl">✅</div>
              </div>
            </div>

            {{-- Kelas 9 --}}
            <div @click="class_level = '9'"
              :class="class_level === '9' ? 'border-blue-600 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'"
              class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
              <div class="flex items-center gap-4">
                <div class="text-4xl">🏢</div>
                <div class="flex-1">
                  <div class="font-bold text-gray-800 text-base">Kelas 9 SMP</div>
                  <div class="text-xs text-gray-500 mt-0.5">Seleksi masuk SMA/SMK negeri</div>
                  <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Matematika</span>
                    <span class="bg-green-100 text-green-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">IPA</span>
                    <span class="bg-red-100 text-red-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">B. Inggris</span>
                  </div>
                </div>
                <div x-show="class_level === '9'" class="text-blue-600 text-xl">✅</div>
              </div>
            </div>

            {{-- Kelas 12 --}}
            <div @click="class_level = '12'"
              :class="class_level === '12' ? 'border-blue-600 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'"
              class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
              <div class="flex items-center gap-4">
                <div class="text-4xl">🎓</div>
                <div class="flex-1">
                  <div class="font-bold text-gray-800 text-base">Kelas 12 SMA/SMK</div>
                  <div class="text-xs text-gray-500 mt-0.5">UTBK/SNBT & ujian mandiri PTN</div>
                  <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">TPS</span>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Literasi</span>
                    <span class="bg-purple-100 text-purple-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Saintek/Soshum</span>
                  </div>
                </div>
                <div x-show="class_level === '12'" class="text-blue-600 text-xl">✅</div>
              </div>
            </div>
          </div>

          <div class="flex gap-3">
            <button type="button" @click="step = 1"
              class="w-1/3 border border-gray-300 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-50 transition text-sm">
              ← Kembali
            </button>
            <button type="submit" :disabled="!class_level"
              class="w-2/3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
              Daftar Sekarang →
            </button>
          </div>

          {{-- Inject fields hidden untuk dikirim --}}
          <input type="hidden" name="name" x-model="name">
          <input type="hidden" name="email" x-model="email">
          <input type="hidden" name="phone" x-model="phone">
          <input type="hidden" name="password" x-model="password">
          <input type="hidden" name="password_confirmation" x-model="password_confirmation">
        </div>

      </form>

    

      <p class="text-center text-xs text-gray-400 mt-6">
        Dengan mendaftar, kamu menyetujui
        <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> SMARTKA.
      </p>

    </div>
  </div>
</div>
@endsection