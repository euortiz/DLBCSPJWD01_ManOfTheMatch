<template>
  <div class="min-h-screen flex flex-col">
    <!-- ═══ Header ════════════════════════════════════════════ -->
    <header class="border-b border-white/10 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
      <div class="max-w-5xl mx-auto px-6 py-4 flex items-center gap-3">
        <!-- Trophy icon -->
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center shadow-lg shadow-gold-500/30">
          <svg class="w-5 h-5 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2a1 1 0 0 1 1 1v1h4a1 1 0 0 1 1 1v3c0 2.97-1.81 5.5-4.42 6.57A5.002 5.002 0 0 1 8 19H7v2h4a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2H8v-2.17A6.001 6.001 0 0 1 3 11V7a1 1 0 0 1 1-1h4V3a1 1 0 0 1 1-1h3ZM5 8v3a4 4 0 0 0 3.29 3.92A7.007 7.007 0 0 1 7 11V8H5Zm14 3V8h-2v3c0 1.06-.24 2.06-.67 2.95A4.002 4.002 0 0 0 19 11Z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-bold leading-tight text-white">Man of the Match</h1>
          <p class="text-xs text-slate-400 leading-none">Live Player Voting</p>
        </div>
        <!-- Status badge -->
        <div class="ml-auto flex items-center gap-2">
          <span class="flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
          </span>
          <span class="text-xs text-slate-400">Live</span>
        </div>
      </div>
    </header>

    <!-- ═══ Main ═══════════════════════════════════════════════ -->
    <main class="flex-1 max-w-5xl mx-auto w-full px-6 py-12">

      <!-- Hero caption -->
      <div class="text-center mb-12">
        <span class="badge-gold mb-4">⚡ Voting Open</span>
        <h2 class="text-4xl sm:text-5xl font-extrabold text-white mt-3 mb-2 tracking-tight">
          Who was the best?
        </h2>
        <p class="text-slate-400 text-lg">Vote for tonight's standout performer.</p>
      </div>

      <!-- Loading state -->
      <div v-if="pending" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="n in 5" :key="n"
          class="card-glass h-48 animate-pulse"
        />
      </div>

      <!-- Error state -->
      <div v-else-if="error" class="text-center py-20">
        <p class="text-red-400 text-lg">⚠️ Could not connect to the API.</p>
        <p class="text-slate-500 text-sm mt-2">Make sure the Laravel backend is running on <code class="text-brand-400">http://localhost</code>.</p>
      </div>

      <!-- Players grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <article
          v-for="player in players"
          :key="player.id"
          class="card-glass p-6 flex flex-col items-center gap-4 hover:border-white/20 hover:bg-white/8 transition-all duration-300 group"
        >
          <!-- Avatar -->
          <div class="relative">
            <img
              v-if="player.photo_url"
              :src="player.photo_url"
              :alt="player.name"
              class="w-20 h-20 rounded-full object-cover border-2 border-white/10 group-hover:border-brand-500/50 transition-all duration-300"
            />
            <div
              v-else
              class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-2xl font-bold text-white"
            >
              {{ player.name[0] }}
            </div>
          </div>

          <!-- Info -->
          <div class="text-center">
            <p class="font-semibold text-white text-lg leading-tight">{{ player.name }}</p>
            <p class="text-slate-400 text-sm mt-1">{{ player.position }}</p>
          </div>

          <!-- Vote count -->
          <div class="flex items-center gap-2 bg-white/5 rounded-full px-4 py-1.5">
            <svg class="w-4 h-4 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
            <span class="text-sm font-semibold text-gold-400">{{ player.vote_count }}</span>
            <span class="text-xs text-slate-500">votes</span>
          </div>

          <!-- Vote button -->
          <button class="btn-primary w-full mt-1">
            Vote
          </button>
        </article>
      </div>

    </main>

    <!-- ═══ Footer ═══════════════════════════════════════════ -->
    <footer class="border-t border-white/5 py-6 text-center text-slate-600 text-sm">
      Man of the Match Live · Built with Laravel 11 + Nuxt 3
    </footer>
  </div>
</template>

<script setup lang="ts">
interface Player {
  id: number
  name: string
  position: string
  photo_url: string | null
  vote_count: number
}

// Pull API base from runtime config
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

// Fetch all players from the Laravel API
const { data, pending, error } = await useFetch<{ data: Player[] }>(
  `${apiBase}/players`,
  { key: 'players' }
)

const players = computed(() => data.value?.data ?? [])
</script>
