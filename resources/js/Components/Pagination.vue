<script setup>
import { ChevronLeft, ChevronRight } from '@lucide/vue'
import Button from '@/Components/ui/Button.vue'

// Objek paginator dari Laravel (berisi from, to, total, prev_page_url, next_page_url)
defineProps({
  paginator: { type: Object, required: true },
})
</script>

<template>
  <div class="flex flex-col items-center justify-between gap-3 px-4 py-3 text-sm text-slate-500 dark:text-slate-400 sm:flex-row sm:px-5">
    <!-- Ringkasan jumlah data -->
    <p>Menampilkan {{ paginator.from ?? 0 }} sampai {{ paginator.to ?? 0 }} dari {{ paginator.total }}</p>

    <div class="flex gap-2">
      <!-- Tombol nonaktif jika tidak ada halaman sebelum/sesudahnya -->
      <Button v-if="paginator.prev_page_url" variant="secondary" :href="paginator.prev_page_url" :icon="ChevronLeft">Sebelumnya</Button>
      <Button v-else variant="secondary" disabled :icon="ChevronLeft">Sebelumnya</Button>

      <Button v-if="paginator.next_page_url" variant="secondary" :href="paginator.next_page_url" :icon="ChevronRight" trailing>Berikutnya</Button>
      <Button v-else variant="secondary" disabled :icon="ChevronRight" trailing>Berikutnya</Button>
    </div>
  </div>
</template>
