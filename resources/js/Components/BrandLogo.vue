<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

// Nama aplikasi dibagikan ke semua halaman oleh HandleInertiaRequests (dari APP_NAME)
const page = usePage()
const appName = computed(() => page.props.appName ?? 'Lontar')
</script>

<template>
  <!-- Logo = ikon dokumen + nama aplikasi. Bungkus dengan <Link> atau <a> bila perlu tautan. -->
  <span class="flex items-center gap-2 text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
    <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand text-white">
      <svg
        viewBox="0 0 24 24"
        class="h-4 w-4"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
      >
        <path d="M6 4h9l3 3v13H6zM9 12h6M9 16h6" />
      </svg>
    </span>
    {{ appName }}
  </span>
</template>
