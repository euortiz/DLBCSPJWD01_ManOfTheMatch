<template>
  <div class="min-h-screen flex flex-col">
    <!-- ═══ Header ════════════════════════════════════════════ -->
    <header class="border-b border-white/10 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center gap-3">
        <!-- Logo -->
        <img src="/motm_logo.png" alt="MOTM Logo" class="h-10 w-auto object-contain drop-shadow-md rounded-lg" />
        <div>
          <h1 class="text-lg font-bold leading-tight text-white">Man of the Match</h1>
          <p class="text-xs text-slate-400 leading-none">Live Player Voting</p>
        </div>
        <!-- Status badge -->
        <div class="ml-auto flex items-center gap-2">
          <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
          </span>
          <span class="text-xs font-medium text-slate-300">Live</span>
        </div>
      </div>
    </header>

    <!-- ═══ Main ═══════════════════════════════════════════════ -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-10 lg:py-16">

      <!-- Hero caption -->
      <div class="text-center mb-12 sm:mb-16">
        <span class="badge-gold mb-4 shadow-lg shadow-gold-500/10">⚡ Voting is Open</span>
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mt-4 mb-4 tracking-tight drop-shadow-sm">
          Who was the best?
        </h2>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">
          Cast your vote for tonight's standout performer. The chart updates in real-time as fans across the globe cast their votes.
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        
        <!-- Players Grid (Left Side) -->
        <div class="lg:col-span-8 order-2 lg:order-1">
          <!-- Loading state -->
          <div v-if="pending && !players.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="n in 5" :key="n"
              class="card-glass h-[320px] animate-pulse bg-white/5"
            />
          </div>

          <!-- Error state -->
          <div v-else-if="error" class="text-center py-20 card-glass border-red-500/20 bg-red-500/5">
            <p class="text-red-400 text-lg font-bold">⚠️ Could not connect to the API.</p>
            <p class="text-slate-400 text-sm mt-2">Make sure your Laravel backend is running and the database is seeded.</p>
          </div>

          <!-- The List -->
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <PlayerCard
              v-for="(player, index) in players"
              :key="player.id"
              :player="player"
              :rank="index + 1"
              :has-voted="(votedPlayerId !== null && votedPlayerId !== undefined)"
              :is-voting="votingId === player.id"
              @vote="castVote"
            />
          </div>
        </div>

        <!-- Sidebar / Real-time Chart (Right Side) -->
        <div class="lg:col-span-4 order-1 lg:order-2 lg:sticky lg:top-28">
           <VoteChart :players="players" />
        </div>

      </div>
    </main>

    <!-- ═══ Footer ═══════════════════════════════════════════ -->
    <footer class="border-t border-white/5 py-8 mt-12 text-center">
      <p class="text-slate-500 text-sm font-medium">
        Man of the Match Live · Built with <span class="text-red-400 hover:text-red-300 transition-colors">Laravel 11</span> & <span class="text-emerald-400 hover:text-emerald-300 transition-colors">Nuxt 3</span> by Gustavo Ortiz
      </p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'

interface Player {
  id: number
  name: string
  position: string
  photo_url: string | null
  vote_count: number
}

// Config & State
const config = useRuntimeConfig()
const apiBase = config.public.apiBase

const votedPlayerId = ref<number | null>(null)
const votingId = ref<number | null>(null)
let pollInterval: ReturnType<typeof setInterval> | null = null

// Server/Client Fetch Data
const { data, pending, error, refresh } = await useFetch<{ data: Player[] }>(
  `${apiBase}/players`,
  { key: 'players' }
)

const players = computed(() => data.value?.data ?? [])

// On Mount: Check LocalStorage & start polling
onMounted(() => {
  const savedVote = localStorage.getItem('motm_vote')
  if (savedVote) {
    votedPlayerId.value = parseInt(savedVote)
  }

  // Poll for live votes every 5 seconds without hard reloading the UI
  pollInterval = setInterval(async () => {
    await refresh()
  }, 5000)
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})

// Voting Logic
const castVote = async (playerId: number) => {
  // Prevent double-clicking or voting if already voted
  if (votedPlayerId.value !== null || votingId.value !== null) return

  votingId.value = playerId

  try {
    await $fetch(`${apiBase}/players/${playerId}/vote`, {
      method: 'POST',
      headers: { 'Accept': 'application/json' }
    })
    
    // Save state on success
    votedPlayerId.value = playerId
    localStorage.setItem('motm_vote', playerId.toString())
    
    // Immediately refresh the graph and vote counts
    await refresh()
  } catch (err: any) {
    console.error('Vote failed:', err)
    
    // In Sprint 2, the Laravel API emits 429 if IP already voted.
    if (err.response?.status === 429) {
      alert(err.response._data?.message || 'You have already voted from this IP in the last hour.')
      // Since the backend rejected it, they did indeed vote. We lock them out locally too.
      votedPlayerId.value = playerId
      localStorage.setItem('motm_vote', playerId.toString())
    } else {
      alert('Network error. Please try again later.')
    }
  } finally {
    votingId.value = null
  }
}
</script>
