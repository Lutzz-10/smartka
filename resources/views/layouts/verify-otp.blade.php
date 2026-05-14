@extends('layouts.auth')
@section('title', 'Verifikasi OTP')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4"
  x-data="{
    otp: ['','','','','',''],
    timer: 600,
    canResend: false,
    loading: false,
    success: false,
    init() {
      this.startTimer();
      this.$nextTick(() => this.$refs.otp0.focus());
    },
    startTimer() {
      this.canResend = false;
      this.timer = 600;
      const t = setInterval(() => {
        this.timer--;
        if (this.timer <= 0) { clearInterval(t); this.canResend = true; }
      }, 1000);
    },
    get timerDisplay() {
      const m = Math.floor(this.timer / 60).toString().padStart(2,'0');
      const s = (this.timer % 60).toString().padStart(2,'0');
      return m + ':' + s;
    },
    get otpValue() { return this.otp.join(''); },
    handleInput(index, event) {
      const val = event.target.value.replace(/\D/g,'');
      this.otp[index] = val.slice(-1);
      if (val && index < 5) this.$refs['otp'+(index+1)].focus();
    },
    handleKeydown(index, event) {
      if (event.key === 'Backspace' && !this.otp[index] && index > 0) {
        this.$refs['otp'+(index-1)].focus();
      }
    },
    handlePaste(event) {
      const paste = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
      for (let i = 0; i < 6 && i < paste.length; i++) this.otp[i] = paste[i];
      this.$refs.otp5.focus();
    },
    async resendOtp() {
      const res = await fetch('{{ route('otp.resend') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
      });
      const data = await res.json();
      if (res.ok) { this.startTimer(); this.otp = ['','','','','','']; this.$refs.otp0.focus(); }
    }
  }">

  <div class="w-full max-w-md">
    <div class="flex items-center justify-center gap-2 mb-6">
      <span class="text-2xl">🚀</span>
      <span class="text-2xl font-extrabold text-blue-600" style="font-family:'Plus Jakarta Sans',sans-serif">SMARTKA</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">

      {{-- Sukses state --}}
      <div x-show="success" x-transition class="py-4">
        <div class="text-7xl mb-4">🎉</div>
        <h2 class="text-xl font-bold text-green-600 mb-2" style="font-family:'Plus Jakarta Sans',sans-serif">Verifikasi Berhasil!</h2>
        <p class="text-gray-500 text-sm">Mengalihkan ke dashboard...</p>
        <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
          <div class="h-full bg-green-500 rounded-full animate-pulse" style="width:100%"></div>
        </div>
      </div>

      {{-- Form OTP --}}
      <div x-show="!success">
        <div class="text-6xl mb-4">📧</div>
        <h2 class="text-xl font-bold text-gray-800 mb-2" style="font-family:'Plus Jakarta Sans',sans-serif">Cek Email Kamu!</h2>
        <p class="text-gray-500 text-sm mb-6">Kode OTP 6 digit telah dikirim ke email kamu. Berlaku selama <span class="font-semibold text-blue-600" x-text="timerDisplay"></span>.</p>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
          {{ $errors->first() }}
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm">
          {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
          @csrf

          {{-- Input OTP 6 kotak --}}
          <div class="flex gap-3 justify-center mb-6" @paste.prevent="handlePaste($event)">
            <template x-for="(digit, index) in otp" :key="index">
              <input
                type="text" inputmode="numeric" maxlength="1"
                :ref="'otp' + index"
                :value="otp[index]"
                @input="handleInput(index, $event)"
                @keydown="handleKeydown(index, $event)"
                class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition otp-input"
                :class="otp[index] ? 'border-blue-500 bg-blue-50' : ''"
              >
            </template>
          </div>

          <input type="hidden" name="otp" :value="otpValue">

          <button type="submit" :disabled="otpValue.length < 6"
            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition text-sm mb-4">
            Verifikasi & Mulai Belajar ✓
          </button>
        </form>

        <p class="text-sm text-gray-500">
          Tidak menerima kode?
          <button type="button" @click="canResend && resendOtp()"
            :class="canResend ? 'text-blue-600 font-semibold cursor-pointer hover:underline' : 'text-gray-400 cursor-not-allowed'">
            Kirim ulang <span x-show="!canResend">(<span x-text="timerDisplay"></span>)</span>
          </button>
        </p>
      </div>

    </div>
  </div>
</div>
@endsection