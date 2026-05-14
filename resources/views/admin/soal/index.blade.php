@extends('layouts.admin')
@section('title', 'Bank Soal')
@section('page-title', 'Bank Soal')
@section('page-subtitle', 'Kelola semua soal latihan')

@section('content')

{{-- Filter + Tambah --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div>
      <label class="block text-xs text-gray-500 mb-1">Jenjang</label>
      <select name="class_level" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Jenjang</option>
        @foreach(['6' => 'Kelas 6 SD', '9' => 'Kelas 9 SMP', '12' => 'Kelas 12 SMA'] as $val => $label)
        <option value="{{ $val }}" {{ request('class_level') == $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Mata Pelajaran</label>
      <select name="subject_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Mapel</option>
        @foreach($subjects as $s)
        <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
          {{ $s->name }} (Kelas {{ $s->class_level }})
        </option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Tingkat</label>
      <select name="difficulty" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua</option>
        <option value="easy"   {{ request('difficulty') === 'easy'   ? 'selected' : '' }}>Mudah</option>
        <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Sedang</option>
        <option value="hard"   {{ request('difficulty') === 'hard'   ? 'selected' : '' }}>Sulit</option>
      </select>
    </div>
    <div>
      <label class="block text-xs text-gray-500 mb-1">Status</label>
      <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua</option>
        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
        <option value="draft"    {{ request('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Arsip</option>
      </select>
    </div>
    <div class="flex-1 min-w-48">
      <label class="block text-xs text-gray-500 mb-1">Cari Soal</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..."
        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
      Filter
    </button>
    <a href="{{ route('admin.soal.create') }}"
      class="bg-green-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-green-700 transition flex items-center gap-2">
      + Tambah Soal
    </a>
  </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
    <h3 class="font-bold text-gray-800">Daftar Soal</h3>
    <span class="text-xs text-gray-400">{{ $questions->total() }} soal ditemukan</span>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
          <th class="text-left px-5 py-3 text-gray-500 font-medium w-8">No</th>
          <th class="text-left px-5 py-3 text-gray-500 font-medium">Soal</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Mapel</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Level</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Jawaban</th>
          <th class="text-left px-4 py-3 text-gray-500 font-medium">Status</th>
          <th class="text-center px-4 py-3 text-gray-500 font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($questions as $i => $q)
        <tr class="hover:bg-gray-50 transition">
          <td class="px-5 py-3.5 text-gray-400 text-xs">
            {{ ($questions->currentPage() - 1) * $questions->perPage() + $i + 1 }}
          </td>
          <td class="px-5 py-3.5 max-w-xs">
            <div class="text-gray-800 text-sm line-clamp-2">
              {{ Str::limit(strip_tags($q->question_text), 80) }}
            </div>
            <div class="text-gray-400 text-xs mt-0.5">{{ $q->topic?->name }}</div>
          </td>
          <td class="px-4 py-3.5">
            <div class="text-gray-700 text-xs font-medium">{{ $q->subject?->name }}</div>
            <div class="text-gray-400 text-xs">Kelas {{ $q->class_level }}</div>
          </td>
          <td class="px-4 py-3.5">
            <span class="text-xs px-2 py-1 rounded-full font-semibold
              {{ $q->difficulty === 'easy'   ? 'bg-green-100 text-green-700' :
                ($q->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                               'bg-red-100 text-red-700') }}">
              {{ ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'][$q->difficulty] }}
            </span>
          </td>
          <td class="px-4 py-3.5">
            <span class="text-sm font-bold text-blue-600 uppercase">{{ $q->correct_answer }}</span>
          </td>
          <td class="px-4 py-3.5">
            <span class="text-xs px-2 py-1 rounded-full font-semibold
              {{ $q->status === 'active'   ? 'bg-green-100 text-green-700'  :
                ($q->status === 'draft'    ? 'bg-gray-100 text-gray-600'    :
                                             'bg-red-100 text-red-600') }}">
              {{ ucfirst($q->status) }}
            </span>
          </td>
          <td class="px-4 py-3.5">
            <div class="flex items-center justify-center gap-2">
              <a href="{{ route('admin.soal.edit', $q) }}"
                class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg transition font-medium">
                Edit
              </a>
              <form method="POST" action="{{ route('admin.soal.destroy', $q) }}"
                onsubmit="return confirm('Hapus soal ini?')">
                @csrf @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg transition font-medium">
                  Hapus
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-6 py-12 text-center text-gray-400">
            <div class="text-4xl mb-2">📭</div>
            <div>Belum ada soal. <a href="{{ route('admin.soal.create') }}" class="text-blue-600">Tambah soal pertama →</a></div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 border-t border-gray-100">
    {{ $questions->links() }}
  </div>
</div>
@endsection