<script setup>
// Lencana kecil untuk status/peran. tone: green | amber | rose | blue | slate. dot: titik penanda.
defineProps({
  tone: { type: String, default: 'slate' },
  dot: { type: Boolean, default: false },
})

const tones = {
  green: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
  amber: 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
  rose: 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-400',
  blue: 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
  slate: 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
}
</script>

<template>
  <span
    class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-xs font-semibold"
    :class="tones[tone] ?? tones.slate"
  >
    <span v-if="dot" class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
    <slot />
  </span>
</template>
