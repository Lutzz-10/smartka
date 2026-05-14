@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4"
  x-data="{ showPass: false, showConfirm: false, password: '', strength: 0,
    check(p) { let s=0; if(p.length>=8)s++; if(/[A-Z]/.test(p))s++; if(/[0-9]/.test(p))s++; if(/[^A-Za-z0-9]/.test(p))s++; this.strength=s; } }">
  <div class="w-full max-w-md">
    <div class="flex items-center justify-center gap-2 mb-6">
      <span class="text-2xl">🚀</span>
      <span class="text-2xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <div class="text-center mb-6">
        <div class="text-5xl mb-3">🔒</div>
        <h2 class="text-xl font-bold text-gray-800" style="font-family:'Plus Jakarta Sans',sans-serif">Buat Password Baru</h2>
        <p class="text-gray-500 text-sm mt-1">Pastikan password baru kamu kuat dan mudah diingat.</p>
      </div>

      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="Email terdaftar"
            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
          <div class="relative">
            <input :type="showPass ? 'text' : 'password'" name="password"
              x-model="password" @input="check($event.target.value)"
              placeholder="Minimal 8 karakter"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            <button type="button" @click="showPass=!showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
              <span x-show="!showPass">👁️</span><span x-show="showPass">🙈</span>
            </button>
          </div>
          <div class="flex gap-1 mt-2" x-show="password.length > 0">
            <template x-for="i in 4" :key="i">
              <div class="flex-1 h-1.5 rounded-full transition-all"
                :class="strength >= i ? (strength<=1?'bg-red-400':strength<=2?'bg-yellow-400':'bg-green-500') : 'bg-gray-200'"></div>
            </template>
          </div>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
          <div class="relative">
            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
              placeholder="Ulangi password baru"
              class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            <button type="button" @click="showConfirm=!showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
              <span x-show="!showConfirm">👁️</span><span x-show="showConfirm">🙈</span>
            </button>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition text-sm">
          Simpan Password Baru →
        </button>
      </form>
    </div>
  </div>
</div>
@endsection