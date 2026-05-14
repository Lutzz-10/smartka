@extends('layouts.auth')
@section('title', 'Daftar Gratis')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4"
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

  <div class="w-full max-w-lg">

    {{-- Logo --}}
    <div class="flex items-center justify-center gap-2 mb-6">
      <span class="text-2xl">🚀</span>
      <span class="text-2xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center justify-center mb-8 gap-2">
      <template x-for="i in 3" :key="i">
        <div class="flex items-center">
          <div class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold transition-all"
            :class="step >= i ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 text-gray-500'">
            <span x-show="step > i">✓</span>
            <span x-show="step <= i" x-text="i"></span>
          </div>
          <div x-show="i < 3" class="w-16 h-1 mx-1 rounded transition-all"
            :class="step > i ? 'bg-blue-600' : 'bg-gray-200'"></div>
        </div>
      </template>
    </div>
    <div class="flex justify-between text-xs text-gray-500 mb-6 px-2">
      <span :class="step >= 1 ? 'text-blue-600 font-semibold' : ''">Data Diri</span>
      <span :class="step >= 2 ? 'text-blue-600 font-semibold' : ''">Pilih Jenjang</span>
      <span :class="step >= 3 ? 'text-blue-600 font-semibold' : ''">Verifikasi</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

      {{-- Error global --}}
      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm flex gap-2">
        <span>⚠️</span><span>{{ $errors->first() }}</span>
      </div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="hidden" name="class_level" x-model="class_level">

        {{-- ── STEP 1: Data Diri ── --}}
        <div x-show="step === 1" x-transition>
          <h2 class="text-xl font-bold text-gray-800 mb-1" style="font-family:'Plus Jakarta Sans',sans-serif">Buat Akun Baru</h2>
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
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
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
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
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
            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition text-sm">
            Lanjut — Pilih Jenjang →
          </button>
        </div>

        {{-- ── STEP 2: Pilih Jenjang ── --}}
        <div x-show="step === 2" x-transition>
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
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Matematika</span>
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">IPA</span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">B. Indonesia</span>
                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">IPS</span>
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
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Matematika</span>
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">IPA</span>
                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">IPS</span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">B. Indonesia</span>
                    <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">B. Inggris</span>
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
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">TPS</span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full">Literasi</span>
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Matematika</span>
                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">IPA/IPS</span>
                  </div>
                </div>
                <div x-show="class_level === '12'" class="text-blue-600 text-xl">✅</div>
              </div>
            </div>
          </div>

          <div class="flex gap-3">
            <button type="button" @click="step = 1"
              class="flex-1 border border-gray-300 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-50 transition text-sm">
              ← Kembali
            </button>
            <button type="submit" :disabled="!class_level"
              class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition text-sm">
              Daftar & Verifikasi →
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
    </div>
  </div>
</div>
@endsection