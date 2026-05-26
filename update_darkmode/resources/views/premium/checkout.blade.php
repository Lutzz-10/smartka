@extends('layouts.app')
@section('title', 'Checkout')
@section('page-title', 'Checkout')
@section('page-subtitle', 'Selesaikan pembayaran untuk mengaktifkan Premium')

@section('content')
<div class="max-w-4xl mx-auto"
  x-data="{
    step: 1,
    promo: '',
    promoApplied: false,
    promoDiscount: 0,
    period: '{{ request('period', 'monthly') }}',
    basePrice: {{ $planData['price'] }},
    yearlyPrice: {{ $planData['price_year'] ?? $planData['price'] }},
    loading: false,
    get finalPrice() {
      const p = this.period === 'yearly' ? this.yearlyPrice : this.basePrice;
      return p - this.promoDiscount;
    },
    applyPromo() {
      if (this.promo.toUpperCase() === 'SMARTKA10') {
        const p = this.period === 'yearly' ? this.yearlyPrice : this.basePrice;
        this.promoDiscount = Math.floor(p * 0.1);
        this.promoApplied  = true;
      } else {
        this.promoDiscount = 0;
        this.promoApplied  = false;
        alert('Kode promo tidak valid.');
      }
    },
    formatRp(n) {
      return 'Rp ' + n.toLocaleString('id-ID');
    },
    async bayar() {
      if (this.loading) return;
      this.loading = true;
      try {
        const res = await fetch('{{ route('payment.process') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            plan: '{{ $plan }}',
            period: this.period,
            promo_code: this.promo,
          }),
        });
        const data = await res.json();

        // Dev mode fallback
        if (data.redirect) {
          window.location.href = data.redirect;
          return;
        }

        // Buka popup Midtrans Snap
        if (data.snap_token) {
          window.snap.pay(data.snap_token, {
            onSuccess: (result) => {
              // Kirim ke finish handler: aktifkan subscription → dashboard
              window.location.href = '{{ url('/payment/finish') }}/' + data.payment_id
                + '?transaction_status=' + (result.transaction_status || 'settlement')
                + '&transaction_id=' + (result.transaction_id || '');
            },
            onPending: (result) => {
              window.location.href = '{{ url('/payment/finish') }}/' + data.payment_id
                + '?transaction_status=pending';
            },
            onError: (result) => {
              alert('Pembayaran gagal. Silakan coba lagi.');
              this.loading = false;
            },
            onClose: () => {
              this.loading = false;
            },
          });
        } else if (data.error) {
          alert(data.error);
          this.loading = false;
        }
      } catch (e) {
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
        this.loading = false;
      }
    }
  }">

  {{-- ── Stepper (2 langkah) ─────────────────────────────────── --}}
  <div class="flex items-center justify-center gap-2 mb-8">
    @foreach(['Pilih Periode', 'Konfirmasi & Bayar'] as $i => $label)
    <div class="flex items-center">
      <div class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold transition-all"
        :class="{{ $i + 1 }} <= step ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-200 dark:bg-gray-700 text-gray-500'">
        <span x-show="{{ $i + 1 }} < step">✓</span>
        <span x-show="{{ $i + 1 }} >= step">{{ $i + 1 }}</span>
      </div>
      <span class="ml-2 text-xs font-medium hidden md:inline transition-colors"
        :class="{{ $i + 1 }} <= step ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500'">
        {{ $label }}
      </span>
      @if($i < 1)
      <div class="w-8 md:w-20 h-0.5 mx-3 rounded transition-all"
        :class="2 <= step ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"></div>
      @endif
    </div>
    @endforeach
  </div>

  <div class="grid md:grid-cols-3 gap-6">

    {{-- ── Kolom Kiri: Form ─────────────────────────────────── --}}
    <div class="md:col-span-2 space-y-4">

      {{-- STEP 1: Pilih Periode --}}
      <div x-show="step === 1" x-transition
        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">

        <h3 class="font-bold text-gray-800 dark:text-white mb-1 text-lg" style="font-family:'Plus Jakarta Sans',sans-serif;">
          Pilih Periode Berlangganan
        </h3>
        <p class="text-gray-400 dark:text-gray-500 text-sm mb-5">Pilih durasi yang paling sesuai untukmu.</p>

        <div class="grid gap-4 mb-6">

          {{-- Bulanan --}}
          <div @click="period = 'monthly'"
            :class="period === 'monthly'
              ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 shadow-md'
              : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600'"
            class="border-2 rounded-2xl p-5 cursor-pointer transition-all">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                  :class="period === 'monthly' ? 'bg-blue-100 dark:bg-blue-900/40' : 'bg-gray-100 dark:bg-gray-700'">
                  📅
                </div>
                <div>
                  <div class="font-bold text-gray-800 dark:text-white">Bulanan</div>
                  <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Bayar setiap bulan, batalkan kapan saja</div>
                </div>
              </div>
              <div class="text-right">
                <div class="font-extrabold text-blue-600 dark:text-blue-400 text-xl">
                  Rp {{ number_format($planData['price'], 0, ',', '.') }}
                </div>
                <div class="text-gray-400 dark:text-gray-500 text-xs">/ bulan</div>
              </div>
            </div>
          </div>

          {{-- Tahunan --}}
          @if(isset($planData['price_year']))
          <div @click="period = 'yearly'"
            :class="period === 'yearly'
              ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 shadow-md'
              : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600'"
            class="border-2 rounded-2xl p-5 cursor-pointer transition-all relative">
            <div class="absolute -top-3 right-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
              HEMAT 26%
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                  :class="period === 'yearly' ? 'bg-blue-100 dark:bg-blue-900/40' : 'bg-gray-100 dark:bg-gray-700'">
                  🎯
                </div>
                <div>
                  <div class="font-bold text-gray-800 dark:text-white">Tahunan</div>
                  <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">
                    Hemat Rp {{ number_format($planData['price'] * 12 - $planData['price_year'], 0, ',', '.') }} dari harga normal
                  </div>
                </div>
              </div>
              <div class="text-right">
                <div class="font-extrabold text-blue-600 dark:text-blue-400 text-xl">
                  Rp {{ number_format($planData['price_year'], 0, ',', '.') }}
                </div>
                <div class="text-gray-400 dark:text-gray-500 text-xs">/ tahun</div>
              </div>
            </div>
          </div>
          @endif
        </div>

        {{-- Kode Promo di Step 1 --}}
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Promo (Opsional)</label>
          <div class="flex gap-2">
            <input type="text" x-model="promo" @keydown.enter="applyPromo()"
              placeholder="Contoh: SMARTKA10"
              class="flex-1 border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            <button @click="applyPromo()"
              class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
              Pakai
            </button>
          </div>
          <p x-show="promoApplied" class="mt-2 text-green-600 dark:text-green-400 text-xs font-medium">
            ✅ Promo berhasil! Diskon <span x-text="formatRp(promoDiscount)"></span> diterapkan.
          </p>
        </div>

        <button @click="step = 2"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition text-sm shadow-md">
          Lanjut ke Konfirmasi →
        </button>
      </div>

      {{-- STEP 2: Konfirmasi & Bayar via Midtrans --}}
      <div x-show="step === 2" x-transition
        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">

        <h3 class="font-bold text-gray-800 dark:text-white mb-1 text-lg" style="font-family:'Plus Jakarta Sans',sans-serif;">
          Konfirmasi Pesanan
        </h3>
        <p class="text-gray-400 dark:text-gray-500 text-sm mb-5">Pastikan detail pesananmu sudah benar.</p>

        {{-- Detail Pesanan --}}
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 space-y-3 mb-6 text-sm">
          <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Paket</span>
            <span class="font-semibold text-gray-800 dark:text-white">{{ $planData['name'] }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Periode</span>
            <span class="font-semibold text-gray-800 dark:text-white"
              x-text="period === 'yearly' ? '📅 Tahunan (12 bulan)' : '📅 Bulanan (1 bulan)'"></span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Harga</span>
            <span class="font-semibold text-gray-800 dark:text-white"
              x-text="period === 'yearly' ? formatRp(yearlyPrice) : formatRp(basePrice)"></span>
          </div>
          <div x-show="promoApplied" class="flex justify-between items-center text-green-600 dark:text-green-400">
            <span>Diskon Promo</span>
            <span class="font-semibold" x-text="'− ' + formatRp(promoDiscount)"></span>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-600 pt-3 flex justify-between items-center">
            <span class="font-bold text-gray-800 dark:text-white">Total Bayar</span>
            <span class="font-extrabold text-blue-600 dark:text-blue-400 text-xl" x-text="formatRp(finalPrice)"></span>
          </div>
        </div>

        {{-- Info Metode Bayar via Midtrans --}}
        <div class="rounded-2xl border-2 border-dashed border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/10 p-4 mb-6">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
              </svg>
            </div>
            <div>
              <div class="font-bold text-blue-800 dark:text-blue-300 text-sm">Pembayaran via Midtrans</div>
              <div class="text-blue-600 dark:text-blue-400 text-xs">Pilih metode pembayaran setelah klik tombol Bayar</div>
            </div>
          </div>
          <div class="grid grid-cols-4 gap-2">
            @foreach([
              ['🏦', 'Virtual Account', 'BCA · BNI · BRI · Mandiri'],
              ['📱', 'E-Wallet', 'GoPay · OVO · DANA'],
              ['📷', 'QRIS', 'Semua dompet digital'],
              ['💳', 'Kartu Kredit', 'Visa · Mastercard'],
            ] as [$icon, $name, $desc])
            <div class="bg-white dark:bg-gray-800 rounded-xl p-2.5 text-center border border-blue-100 dark:border-blue-800/50">
              <div class="text-lg mb-1">{{ $icon }}</div>
              <div class="text-xs font-semibold text-gray-700 dark:text-gray-300 leading-tight">{{ $name }}</div>
              <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 leading-tight">{{ $desc }}</div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3">
          <button type="button" @click="step = 1"
            class="w-1/3 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 font-semibold py-3.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
            ← Kembali
          </button>
          <button type="button" @click="bayar()"
            :disabled="loading"
            class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white font-bold py-3.5 rounded-xl transition text-sm shadow-md flex items-center justify-center gap-2">
            <span x-show="!loading" class="flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              Bayar Sekarang — <span x-text="formatRp(finalPrice)"></span>
            </span>
            <span x-show="loading" class="flex items-center gap-2">
              <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
              </svg>
              Membuka Pembayaran...
            </span>
          </button>
        </div>

        <p class="text-center text-gray-400 dark:text-gray-500 text-xs mt-3">
          🛡️ Pembayaran aman & terenkripsi via <strong class="text-gray-500 dark:text-gray-400">Midtrans</strong>
        </p>
      </div>
    </div>

    {{-- ── Kolom Kanan: Order Summary ──────────────────────── --}}
    <div class="space-y-4">
      <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 sticky top-24">

        <h4 class="font-bold text-gray-800 dark:text-white mb-4 text-sm">Ringkasan Pesanan</h4>

        {{-- Paket info --}}
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold">⭐</div>
          <div>
            <div class="font-semibold text-gray-800 dark:text-white text-sm">{{ $planData['name'] }}</div>
            <div class="text-gray-400 dark:text-gray-500 text-xs" x-text="period === 'yearly' ? 'Tahunan' : 'Bulanan'"></div>
          </div>
        </div>

        {{-- Harga --}}
        <div class="space-y-2 text-sm mb-4">
          <div class="flex justify-between text-gray-600 dark:text-gray-400">
            <span>Harga</span>
            <span x-text="period === 'yearly' ? formatRp(yearlyPrice) : formatRp(basePrice)"></span>
          </div>
          <div x-show="promoApplied" class="flex justify-between text-green-600 dark:text-green-400">
            <span>Diskon Promo</span>
            <span x-text="'− ' + formatRp(promoDiscount)"></span>
          </div>
          <div class="flex justify-between font-bold text-gray-800 dark:text-white pt-2 border-t border-gray-100 dark:border-gray-700">
            <span>Total</span>
            <span class="text-blue-600 dark:text-blue-400" x-text="formatRp(finalPrice)"></span>
          </div>
        </div>

        {{-- Metode pembayaran badge --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-3 mb-4 text-center">
          <div class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-1">Metode Pembayaran</div>
          <div class="flex justify-center flex-wrap gap-1.5 text-xs text-gray-500 dark:text-gray-400">
            <span class="bg-white dark:bg-gray-700 px-2 py-0.5 rounded-lg border border-gray-200 dark:border-gray-600">🏦 VA Bank</span>
            <span class="bg-white dark:bg-gray-700 px-2 py-0.5 rounded-lg border border-gray-200 dark:border-gray-600">📱 E-Wallet</span>
            <span class="bg-white dark:bg-gray-700 px-2 py-0.5 rounded-lg border border-gray-200 dark:border-gray-600">📷 QRIS</span>
            <span class="bg-white dark:bg-gray-700 px-2 py-0.5 rounded-lg border border-gray-200 dark:border-gray-600">💳 Kartu</span>
          </div>
          <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5">Pilih saat checkout berlangsung</div>
        </div>

        {{-- Benefits --}}
        <div class="space-y-2">
          @foreach([
            '✅ Aktif langsung setelah bayar',
            '🔒 Transaksi aman via Midtrans',
            '↩️ Garansi uang kembali 7 hari',
          ] as $b)
          <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
            {{ $b }}
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection