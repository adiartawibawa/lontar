<script setup>
import { computed } from 'vue'
import { CircleAlert, CircleCheck, Info, TriangleAlert } from '@lucide/vue'

// Kotak pemberitahuan. variant: success | error | warning | info. Isi pesan lewat slot.
const props = defineProps({
  variant: { type: String, default: 'info' },
})

// Ikon, warna, dan peran aksesibilitas per varian
const config = {
  success: { icon: CircleCheck, role: 'status', box: 'bg-emerald-50 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300' },
  error: { icon: CircleAlert, role: 'alert', box: 'bg-rose-50 text-rose-800 dark:bg-rose-500/10 dark:text-rose-300' },
  warning: { icon: TriangleAlert, role: 'alert', box: 'bg-amber-50 text-amber-800 dark:bg-amber-500/10 dark:text-amber-300' },
  info: { icon: Info, role: 'status', box: 'bg-blue-50 text-blue-800 dark:bg-blue-500/10 dark:text-blue-300' },
}

const current = computed(() => config[props.variant] ?? config.info)
</script>

<template>
  <div class="flex items-start gap-2.5 rounded-xl px-4 py-3 text-sm" :class="current.box" :role="current.role">
    <component :is="current.icon" class="mt-0.5 h-4 w-4 shrink-0" aria-hidden="true" />
    <div class="min-w-0"><slot /></div>
  </div>
</template>
