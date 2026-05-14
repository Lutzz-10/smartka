@extends('layouts.auth')
@section('title', 'Lupa Password')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <div class="flex items-center justify-center gap-2 mb-6">
      <span class="text-2xl">🚀</span>
      <span class="text-2xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <div class="text-center mb-6">
        <div class="text-5xl mb-3">🔑</div>
        <h2 class="text-xl font-bold text-gray-800" style="font-family:'Plus Jakarta Sans',sans-serif">Lupa Password?</h2>
        <p class="text-gray-500 text-sm mt-1">Masukkan email dan kami kirimkan link reset password.</p>
      </div>

      @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm flex gap-2">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm flex gap-2">
        <span>⚠️</span><span>{{ $errors->first() }}</span>
      </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Terdaftar</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            required autofocus>
        </div>
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition text-sm">
          Kirim Link Reset Password →
        </button>
      </form>

      <div class="text-center mt-5">
        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Login</a>
      </div>
    </div>
  </div>
</div>
@endsection