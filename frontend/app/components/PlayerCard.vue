<template>
  <article class="card-glass p-6 flex flex-col items-center gap-4 hover:border-white/20 hover:bg-white/8 transition-all duration-300 group">
    <!-- Avatar -->
    <div class="relative">
      <img
        v-if="player.photo_url"
        :src="player.photo_url"
        :alt="player.name"
        class="w-20 h-20 rounded-full object-cover border-2 border-white/10 group-hover:border-brand-500/50 transition-all duration-300 bg-slate-800"
      />
      <div
        v-else
        class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-2xl font-bold text-white shadow-inner"
      >
        {{ player.name.charAt(0) }}
      </div>
      
      <!-- Rank Badge -->
      <div v-if="rank === 1" class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center shadow-lg transform rotate-12">
        <span class="text-sm font-bold text-slate-900">1</span>
      </div>
    </div>

    <!-- Info -->
    <div class="text-center w-full">
      <p class="font-bold text-white text-lg leading-tight truncate px-2" :title="player.name">{{ player.name }}</p>
      <p class="text-brand-400 text-xs font-medium uppercase tracking-wider mt-1">{{ player.position }}</p>
    </div>

    <!-- Vote count -->
    <div class="flex items-center justify-center gap-2 bg-slate-900/50 rounded-full px-5 py-2 w-full border border-white/5">
      <svg class="w-4 h-4 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
      </svg>
      <span class="text-base font-bold text-white">{{ player.vote_count }}</span>
      <span class="text-xs text-slate-400 font-medium tracking-wide">VOTES</span>
    </div>

    <!-- Vote button -->
    <button 
      @click="$emit('vote', player.id)"
      :disabled="hasVoted || isVoting"
      class="btn-primary w-full mt-2 relative overflow-hidden group/btn"
      :class="{'opacity-60 grayscale cursor-not-allowed': hasVoted}"
    >
      <span v-if="hasVoted" class="flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        Voted
      </span>
      <span v-else-if="isVoting" class="flex items-center gap-2 animate-pulse">
        Voting...
      </span>
      <span v-else class="flex items-center justify-center gap-2">
        Vote Now
      </span>
      
      <!-- Hover glint effect for active state -->
      <div v-if="!hasVoted" class="absolute inset-0 -translate-x-[150%] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover/btn:animate-[glint_1s_ease-in-out_infinite] skew-x-[-20deg]"></div>
    </button>
  </article>
</template>

<script setup lang="ts">
interface Player {
  id: number
  name: string
  position: string
  photo_url: string | null
  vote_count: number
}

defineProps<{
  player: Player
  rank: number
  hasVoted: boolean
  isVoting?: boolean
}>()

defineEmits(['vote'])
</script>

<style scoped>
@keyframes glint {
  100% {
    transform: translateX(150%);
  }
}
</style>
