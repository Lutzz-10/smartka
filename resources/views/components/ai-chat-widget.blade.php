<div x-data="floatingChat()" x-init="init()" class="fixed bottom-6 right-6 z-50">

  {{-- ── TOMBOL FLOATING ─────────────────────────── --}}
  <div x-show="!open" x-transition>
    <button @click="open = true"
      class="flex items-center gap-2.5 bg-blue-600 hover:bg-blue-700 text-white pl-4 pr-5 py-3 rounded-full shadow-2xl transition hover:shadow-blue-300/50 hover:scale-105 active:scale-95">

      {{-- Dot animasi --}}
      <span class="relative flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
      </span>

      <span class="text-lg">🤖</span>
      <span class="font-semibold text-sm">Tanya AI</span>

      {{-- Badge kuota merah jika hampir habis --}}
      @if(!auth()->user()->isPremium() && auth()->user()->todayAiQuota() <= 1)
      <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
        !
      </span>
      @endif
    </button>
  </div>

  {{-- ── CHAT WINDOW ──────────────────────────────── --}}
  <div x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
    class="w-80 md:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col"
    style="height: 500px;">

    {{-- Header --}}
    <div class="bg-blue-600 px-4 py-3 flex items-center gap-3">
      <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">🤖</div>
      <div class="flex-1">
        <div class="text-white font-bold text-sm">Smartka AI</div>
        <div class="text-blue-200 text-xs flex items-center gap-1">
          <span class="w-1.5 h-1.5 bg-green-400 rounded-full inline-block"></span> Online 24/7
        </div>
      </div>

      {{-- Kuota bar tipis --}}
      @if(!auth()->user()->isPremium())
      <div class="text-xs text-blue-200 text-right">
        <span x-text="quota"></span>/5
      </div>
      @endif

      {{-- Fullscreen & Close --}}
      <a href="{{ route('ai.tutor') }}" class="text-white/70 hover:text-white text-xs mr-1" title="Buka fullscreen">⛶</a>
      <button @click="open = false" class="text-white/70 hover:text-white text-lg leading-none">✕</button>
    </div>

    {{-- Kuota progress bar --}}
    @if(!auth()->user()->isPremium())
    <div class="h-0.5 bg-gray-100">
      <div class="h-full bg-green-500 transition-all"
        :style="'width:' + (quota/5*100) + '%'"
        :class="quota <= 1 ? 'bg-red-500' : 'bg-green-500'">
      </div>
    </div>
    @endif

    {{-- Pesan --}}
    <div class="flex-1 overflow-y-auto p-4 space-y-3" id="widget-messages" x-ref="widgetMessages">

      {{-- Welcome message --}}
      <template x-if="messages.length === 0">
        <div>
          <div class="flex gap-2">
            <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-xs flex-shrink-0">🤖</div>
            <div class="bg-gray-100 text-gray-700 text-xs rounded-2xl rounded-tl-sm px-3 py-2.5 leading-relaxed max-w-xs">
              Halo <strong>{{ auth()->user()->name }}</strong>! Saya Smartka AI 👋<br>
              Ada soal yang sulit atau materi yang belum dipahami? Yuk tanya aja!
            </div>
          </div>

          {{-- Quick chips --}}
          <div class="flex flex-wrap gap-1.5 mt-3 ml-9">
            @foreach([
              'Bantu jelaskan soal ini',
              'Tips Try Out',
              'Analisis nilai terakhir',
            ] as $chip)
            <button @click="sendQuick('{{ $chip }}')"
              class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full border border-blue-200 transition">
              {{ $chip }}
            </button>
            @endforeach
          </div>
        </div>
      </template>

      {{-- Daftar pesan --}}
      <template x-for="(msg, i) in messages" :key="i">
        <div>
          {{-- User --}}
          <template x-if="msg.role === 'user'">
            <div class="flex justify-end">
              <div class="bg-blue-600 text-white text-xs rounded-2xl rounded-tr-sm px-3 py-2.5 max-w-xs leading-relaxed"
                x-text="msg.content"></div>
            </div>
          </template>

          {{-- AI --}}
          <template x-if="msg.role === 'model'">
            <div class="flex gap-2">
              <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-xs flex-shrink-0">🤖</div>
              <div class="bg-gray-100 text-gray-700 text-xs rounded-2xl rounded-tl-sm px-3 py-2.5 max-w-xs leading-relaxed"
                x-html="msg.html || msg.content"></div>
            </div>
          </template>
        </div>
      </template>

      {{-- Typing indicator --}}
      <template x-if="loading">
        <div class="flex gap-2">
          <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-xs">🤖</div>
          <div class="bg-gray-100 rounded-2xl rounded-tl-sm px-3 py-2.5 flex items-center gap-1">
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.15s"></div>
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.3s"></div>
          </div>
        </div>
      </template>

      {{-- Limit reached --}}
      <template x-if="limitReached">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs">
          <div class="font-bold text-amber-800 mb-1">Kuota habis hari ini 😢</div>
          <p class="text-amber-700 mb-2">Upgrade Premium untuk tanya tanpa batas!</p>
          <a href="#" class="block text-center bg-blue-600 text-white py-1.5 rounded-lg font-semibold">
            Upgrade → Rp 79K/bulan
          </a>
        </div>
      </template>
    </div>

    {{-- Input --}}
    <div class="border-t border-gray-100 p-3">
      @if(!auth()->user()->isPremium())
      <div class="text-center text-xs mb-2"
        :class="quota <= 0 ? 'text-red-500 font-semibold' : 'text-gray-400'"
        x-text="quota + '/5 pertanyaan gratis tersisa hari ini'">
      </div>
      @endif

      <div class="flex items-center gap-2">
        <input
          x-model="input"
          @keydown.enter="sendMessage()"
          :disabled="loading || (quota <= 0 && !isPremium)"
          type="text"
          placeholder="Tanya apapun..."
          class="flex-1 border border-gray-200 rounded-full px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-50 disabled:text-gray-400"
        >
        <button @click="sendMessage()"
          :disabled="!input.trim() || loading || (quota <= 0 && !isPremium)"
          class="w-8 h-8 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white rounded-full flex items-center justify-center transition">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function floatingChat() {
  return {
    open:         false,
    messages:     [],
    input:        '',
    loading:      false,
    sessionId:    null,
    limitReached: false,
    quota:        {{ auth()->user()->todayAiQuota() }},
    isPremium:    {{ auth()->user()->isPremium() ? 'true' : 'false' }},

    init() {},

    sendQuick(text) {
      this.input = text;
      this.sendMessage();
    },

    async sendMessage() {
      if (!this.input.trim() || this.loading) return;
      if (!this.isPremium && this.quota <= 0) {
        this.limitReached = true;
        return;
      }

      const text = this.input.trim();
      const now  = new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});

      this.messages.push({ role: 'user', content: text, time: now });
      this.input   = '';
      this.loading = true;
      this.$nextTick(() => this.scrollBottom());

      const form = new FormData();
      form.append('message', text);
      form.append('_token',  '{{ csrf_token() }}');
      if (this.sessionId) form.append('session_id', this.sessionId);

      try {
        const res  = await fetch('{{ route('ai.send') }}', { method: 'POST', body: form });
        const data = await res.json();

        if (!res.ok) {
          if (data.error === 'limit_reached') {
            this.limitReached = true;
            this.quota        = 0;
          } else {
            this.messages.push({ role:'model', content: data.message || 'Terjadi kesalahan.', html: data.message || 'Terjadi kesalahan.', time: now });
          }
          return;
        }

        if (!this.sessionId) this.sessionId = data.session_id;
        if (data.remaining !== null && data.remaining !== undefined) this.quota = data.remaining;

        this.messages.push({
          role:    'model',
          content: data.reply,
          html:    this.md(data.reply),
          time:    new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}),
        });

      } catch(e) {
        this.messages.push({ role:'model', content:'⚠️ Koneksi bermasalah.', html:'⚠️ Koneksi bermasalah.', time: now });
      } finally {
        this.loading = false;
        this.$nextTick(() => this.scrollBottom());
      }
    },

    scrollBottom() {
      const el = document.getElementById('widget-messages');
      if (el) el.scrollTop = el.scrollHeight;
    },

    md(text) {
      if (!text) return '';
      return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`([^`]+)`/g, '<code class="bg-gray-200 px-1 rounded">$1</code>')
        .replace(/^[-•] (.*$)/gm, '<li class="ml-3 list-disc">$1</li>')
        .replace(/\n\n/g, '<br><br>')
        .replace(/\n/g, '<br>');
    },
  }
}
</script>