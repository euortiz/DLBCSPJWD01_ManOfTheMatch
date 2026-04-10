import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  // Global CSS
  css: ['~/assets/css/main.css'],

  // Use Tailwind CSS v4 via Vite plugin
  vite: {
    plugins: [
      tailwindcss(),
    ],
  },

  // Runtime config — API base URL for Laravel backend
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost/api',
    },
  },

  // $fetch base URL for useFetch / $fetch calls
  nitro: {
    routeRules: {
      '/api/**': {
        cors: true,
      },
    },
  },
})
