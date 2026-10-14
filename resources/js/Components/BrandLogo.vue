<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { FileText } from '@lucide/vue'

// Nama aplikasi dibagikan ke semua halaman oleh HandleInertiaRequests (dari APP_NAME)
const page = usePage()
const appName = computed(() => page.props.appName ?? 'Lontar')
</script>

<template>
  <!-- Logo = ikon dokumen + nama aplikasi. Bungkus dengan <Link> atau <a> bila perlu tautan. -->
  <span class="flex items-center gap-2 text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
    <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand text-white">
      <FileText class="h-4 w-4" :stroke-width="2.5" aria-hidden="true" />
    </span>
    {{ appName }}
  </span>
</template>
