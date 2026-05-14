@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Kelola semua akun siswa')

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div>
      <label class="block text-xs text-gray-500 mb-1">Status</label>
      <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua</option>
        <option value="premium" {{ request('status') === 'premium' ? 'selected' : '' }}>Premium</option>
        <option value="free"    {{ request('status') === 'free'    ? 'selected' : '' }}>Free</option>
      </select>
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Jenjang</label>
      <select name="class_level" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua</option>
        <option value="6"  {{ request('class_level') === '6'  ? 'selected' : '' }}>Kelas 6</option>
        <option value="9"  {{ request('class_level') === '9'  ? 'selected' : '' }}>Kelas 9</option>
        <option value="12" {{ request('class_level') === '12' ? 'selected' : '' }}>Kelas 12</option>
      </select>
    </div>
    <div class="flex-1 min-w-48">
      <label class="block text-xs text-gray-500 mb-1">Cari Pengguna</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
      Cari
    </button>
  </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex justify-between">
    <h3 class="font-bold text-gray-800">Daftar Pengguna</h3>
    <span class="text-xs text-gray-400">{{ $users->total() }} pengguna</span>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
          <th class="text-left px-5 py-3 text-gray-500 font-medium">Pengguna</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Jenjang</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Status</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Bergabung</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Terakhir Aktif</th>
          <th class="text-center px-4 py-3 text-gray-500 font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($users as $u)
        <tr class="hover:bg-gray-50 transition">
          <td class="px-5 py-3.5">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-sm">🧑‍🎓</div>
              <div>
                <div class="font-semibold text-gray-800">{{ $u->name }}</div>
                <div class="text-gray-400 text-xs">{{ $u->email }}</div>
              </div>
            </div>
          </td>
          <td class="px-4 py-3.5 text-gray-600 text-xs">
            {{ $u->classLevelLabel }}
          </td>
          <td class="px-4 py-3.5">
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold
              {{ $u->isPremium() ? 'bg-yellow-100 text-yellow-700' :
                ($u->email_verified_at ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600') }}">
              {{ $u->isPremium() ? '⭐ '.ucfirst(str_replace('_',' ',$u->subscription_status)) :
                ($u->email_verified_at ? 'Aktif' : 'Suspended') }}
            </span>
          </td>
          <td class="px-4 py-3.5 text-gray-500 text-xs">
            {{ $u->created_at->format('d M Y') }}
          </td>
          <td class="px-4 py-3.5 text-gray-500 text-xs">
            {{ $u->updated_at->diffForHumans() }}
          </td>
          <td class="px-4 py-3.5">
            <div class="flex items-center justify-center gap-2">
              <a href="{{ route('admin.pengguna.show', $u) }}"
                class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition">
                Detail
              </a>
              <form method="POST" action="{{ route('admin.pengguna.suspend', $u) }}">
                @csrf
                <button class="text-xs {{ $u->email_verified_at ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} px-3 py-1.5 rounded-lg font-medium transition">
                  {{ $u->email_verified_at ? 'Suspend' : 'Aktifkan' }}
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-6 py-12 text-center text-gray-400">
            <div class="text-4xl mb-2">👥</div>
            <div>Tidak ada pengguna ditemukan</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">
    {{ $users->links() }}
  </div>
</div>
@endsection