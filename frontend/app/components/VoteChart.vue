<template>
  <div class="card-glass p-6 w-full h-full min-h-[300px] flex flex-col justify-center">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-lg font-bold text-white flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
        Live Poll Distribution
      </h3>
      <span class="text-xs font-medium text-slate-400 bg-slate-800/50 px-2.5 py-1 rounded-full border border-white/5">
        Auto-updating
      </span>
    </div>
    
    <div class="relative flex-1 w-full flex items-center justify-center min-h-[250px]">
      <Doughnut v-if="hasData" :data="chartData" :options="chartOptions" />
      <div v-else class="absolute inset-0 flex flex-col items-center justify-center text-slate-500">
        <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
        <p class="text-sm">Awaiting first vote...</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

interface Player {
  id: number
  name: string
  vote_count: number
}

const props = defineProps<{
  players: Player[]
}>()

// Use the brand and gold hexes for the graph to match the aesthetic
const palette = [
  '#0ea5e9', // Brand 500
  '#fbbf24', // Gold 400
  '#ec4899', // Pink 500
  '#10b981', // Emerald 500
  '#8b5cf6', // Violet 500
  '#64748b'  // Slate 500 fallback
]

const hasData = computed(() => {
  return props.players.reduce((sum, p) => sum + p.vote_count, 0) > 0
})

const chartData = computed(() => {
  return {
    labels: props.players.map(p => p.name),
    datasets: [
      {
        backgroundColor: palette.slice(0, props.players.length),
        borderColor: '#020617', // Match slate-950 body bg
        borderWidth: 2,
        hoverOffset: 4,
        data: props.players.map(p => p.vote_count)
      }
    ]
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '70%',
  plugins: {
    legend: {
      position: 'right' as const,
      labels: {
        color: '#f1f5f9', // slate-100
        padding: 20,
        font: {
          family: "'Inter', sans-serif",
          size: 13,
          weight: 500
        },
        usePointStyle: true,
        pointStyle: 'circle'
      }
    },
    tooltip: {
      backgroundColor: 'rgba(15, 23, 42, 0.9)', // slate-900
      titleFont: { family: "'Inter', sans-serif", size: 14, weight: 600 },
      bodyFont: { family: "'Inter', sans-serif", size: 13 },
      padding: 12,
      cornerRadius: 8,
      displayColors: true,
      boxPadding: 4
    }
  }
}
</script>
