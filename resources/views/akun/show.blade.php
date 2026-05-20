@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola informasi profil, keamanan kata sandi, dan preferensi belajarmu.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ tab: 'profil' }">

  {{-- ── LEFT COLUMN: STUDENT PROFILE PROFILE CARD ────────────────── --}}
  <div class="lg:col-span-1 space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center shadow-sm dark:bg-gray-800 dark:border-gray-700">
      
      {{-- Avatar Preview Container --}}
      <div class="relative w-28 h-28 mx-auto group">
        @if($user->avatar)
          <img src="{{ asset('storage/' . $user->avatar) }}" 
               alt="{{ $user->name }}" 
               class="w-full h-full object-cover rounded-2xl border-4 border-gray-50 dark:border-gray-700 shadow-sm transition group-hover:scale-105">
        @else
          <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-4xl text-white shadow-inner font-bold select-none transition group-hover:scale-105">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
        @endif
        
        <div class="absolute -bottom-1.5 -right-1.5 bg-blue-600 text-white p-1.5 rounded-lg text-xs shadow-md border border-white dark:border-gray-800 cursor-pointer" 
             @click="tab = 'profil'; $nextTick(() => $refs.avatarInput.focus())" title="Ganti Foto">
          📷
        </div>
      </div>

      {{-- Name and Badges --}}
      <h3 class="font-bold text-lg text-gray-900 mt-4 dark:text-white">{{ $user->name }}</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>

      <div class="flex flex-wrap items-center justify-center gap-2 mt-3">
        <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full dark:bg-blue-950/40 dark:text-blue-300">
          Kelas {{ $user->class_level }}
        </span>
        @if($user->isPremium())
          <span class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow-sm">
            👑 PREMIUM
          </span>
        @else
          <span class="bg-gray-100 text-gray-500 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-gray-700 dark:text-gray-400">
            🆓 AKUN GRATIS
          </span>
        @endif
      </div>

      {{-- Student Statistics Grid --}}
      <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
        <div class="bg-gray-50 rounded-xl p-3 text-center dark:bg-gray-700/50">
          <div class="text-xs text-gray-400 dark:text-gray-500">Rata-rata Nilai</div>
          <div class="text-xl font-extrabold text-blue-600 dark:text-blue-400 mt-0.5">
            {{ number_format($user->getAverageScore(), 1) }}
          </div>
        </div>
        <div class="bg-gray-50 rounded-xl p-3 text-center dark:bg-gray-700/50">
          <div class="text-xs text-gray-400 dark:text-gray-500">Soal Dikerjakan</div>
          <div class="text-xl font-extrabold text-green-600 dark:text-green-400 mt-0.5">
            {{ $user->getTotalAnswered() }}
          </div>
        </div>
      </div>

      @if(!$user->isPremium())
        <div class="mt-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-4 text-left dark:from-gray-700 dark:to-gray-800 dark:border-gray-600">
          <h4 class="font-bold text-blue-900 text-xs flex items-center gap-1.5 dark:text-blue-400">
            ⭐ Upgrade SMARTKA Premium
          </h4>
          <p class="text-[11px] text-gray-600 dark:text-gray-400 mt-1">
            Dapatkan bank soal lengkap, AI Tutor unlimited, dan pembahasan detail per paket!
          </p>
          <a href="{{ route('premium') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded-lg mt-3 transition shadow-sm">
            Upgrade Sekarang
          </a>
        </div>
      @else
        <div class="mt-6 text-xs text-gray-400 dark:text-gray-500 bg-gray-50 rounded-xl py-3 dark:bg-gray-700/30">
          Langganan aktif hingga: <strong class="text-gray-600 dark:text-gray-300">{{ $user->subscription_ends_at ? $user->subscription_ends_at->translatedFormat('d F Y') : 'Selamanya' }}</strong>
        </div>
      @endif

    </div>
  </div>

  {{-- ── RIGHT COLUMN: SETTINGS TABS & FORMS ────────────────── --}}
  <div class="lg:col-span-2 space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
      
      {{-- Tab Navigation Bar --}}
      <div class="flex border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        <button @click="tab = 'profil'"
          class="flex-1 py-4 px-6 font-bold text-sm text-center border-b-2 transition"
          :class="tab === 'profil' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-white'">
          🧑‍🎓 Profil Siswa
        </button>
        <button @click="tab = 'password'"
          class="flex-1 py-4 px-6 font-bold text-sm text-center border-b-2 transition"
          :class="tab === 'password' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-white'">
          🔒 Kata Sandi
        </button>
        <button @click="tab = 'preferences'"
          class="flex-1 py-4 px-6 font-bold text-sm text-center border-b-2 transition"
          :class="tab === 'preferences' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-white'">
          ⚙️ Preferensi
        </button>
      </div>

      {{-- Tab Body --}}
      <div class="p-6">
        
        {{-- ── TAB 1: PROFILE FORM ── --}}
        <div x-show="tab === 'profil'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2">
          <form action="{{ route('akun.update-profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              {{-- Full Name --}}
              <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-400 @enderror"
                  placeholder="Masukkan nama lengkap">
                @error('name')
                  <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              {{-- Email --}}
              <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-400 @enderror"
                  placeholder="name@student.id">
                @error('email')
                  <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              {{-- Phone Number --}}
              <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('phone') border-red-400 @enderror"
                  placeholder="Contoh: 081234567890">
                @error('phone')
                  <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              {{-- Avatar Upload --}}
              <div>
                <label for="avatar" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Ganti Foto Profil (Avatar)</label>
                <input type="file" name="avatar" id="avatar" x-ref="avatarInput"
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-600 cursor-pointer dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-950 dark:file:text-blue-300"
                  accept="image/jpeg,image/png,image/jpg,image/webp">
                <span class="block text-[11px] text-gray-400 dark:text-gray-500 mt-1.5">Format: JPG, PNG, WEBP. Maksimum berkas 2 MB.</span>
                @error('avatar')
                  <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
              <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                💾 Simpan Profil
              </button>
            </div>
          </form>
        </div>

        {{-- ── TAB 2: PASSWORD FORM ── --}}
        <div x-show="tab === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display:none;">
          <form action="{{ route('akun.update-password') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="max-w-xl space-y-6">
              {{-- Current Password --}}
              <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" id="current_password"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('current_password') border-red-400 @enderror"
                  placeholder="Masukkan kata sandi lama Anda">
                @error('current_password')
                  <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              {{-- New Password --}}
              <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kata Sandi Baru</label>
                <input type="password" name="password" id="password"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-400 @enderror"
                  placeholder="Kata sandi baru (minimal 8 karakter)">
                @error('password')
                  <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
              </div>

              {{-- Confirm Password --}}
              <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  placeholder="Masukkan kembali kata sandi baru Anda">
              </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
              <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm">
                🔒 Ganti Kata Sandi
              </button>
            </div>
          </form>
        </div>

        {{-- ── TAB 3: PREFERENCES & DARK MODE SYNC ── --}}
        <div x-show="tab === 'preferences'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" style="display:none;"
          x-data="{
            theme: localStorage.getItem('theme') || 'light',
            emailNotif: true,
            pushNotif: false,
            aiRecommend: true,
            toggleTheme() {
              this.theme = this.theme === 'light' ? 'dark' : 'light';
              localStorage.setItem('theme', this.theme);
              if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
              } else {
                document.documentElement.classList.remove('dark');
              }
            }
          }">
          <div class="space-y-6">
            
            {{-- Dark Mode Toggle Row --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 dark:bg-gray-700/50 dark:border-gray-700">
              <div class="flex items-center gap-3">
                <span class="text-2xl">🌓</span>
                <div>
                  <h4 class="font-bold text-gray-800 dark:text-white text-sm">Mode Tampilan Aplikasi (Dark Mode)</h4>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Aktifkan tema gelap untuk kenyamanan membaca di malam hari.</p>
                </div>
              </div>
              <div>
                <button type="button" @click="toggleTheme()"
                  class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                  :class="theme === 'dark' ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                  <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    :class="theme === 'dark' ? 'translate-x-5' : 'translate-x-0'"></span>
                </button>
              </div>
            </div>

            {{-- Notification Switches --}}
            <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
              <h3 class="font-bold text-gray-800 dark:text-white text-sm mb-4">Pengaturan Notifikasi Belajar</h3>
              
              <div class="space-y-4">
                {{-- Switch 1 --}}
                <div class="flex items-center justify-between py-2">
                  <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Pengingat Latihan Soal Harian</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Dapatkan notifikasi dorongan belajar setiap pagi agar konsisten.</p>
                  </div>
                  <div>
                    <button type="button" @click="emailNotif = !emailNotif"
                      class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                      :class="emailNotif ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                      <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        :class="emailNotif ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                  </div>
                </div>

                {{-- Switch 2 --}}
                <div class="flex items-center justify-between py-2">
                  <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Laporan Prestasi via WhatsApp</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kirim salinan ringkasan kemajuan mingguan langsung ke WhatsApp orang tua.</p>
                  </div>
                  <div>
                    <button type="button" @click="pushNotif = !pushNotif"
                      class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                      :class="pushNotif ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                      <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        :class="pushNotif ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                  </div>
                </div>

                {{-- Switch 3 --}}
                <div class="flex items-center justify-between py-2">
                  <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white text-sm">Rekomendasi Pembahasan AI SMARTKA</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ijinkan AI menganalisa bab kelemahanmu secara otomatis dan menyusun modul belajar.</p>
                  </div>
                  <div>
                    <button type="button" @click="aiRecommend = !aiRecommend"
                      class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                      :class="aiRecommend ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                      <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                        :class="aiRecommend ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
              <button type="button" @click="tab = 'profil'"
                class="bg-blue-50 text-blue-700 font-semibold px-6 py-3 rounded-xl transition hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-300">
                ⬅️ Kembali ke Profil
              </button>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
