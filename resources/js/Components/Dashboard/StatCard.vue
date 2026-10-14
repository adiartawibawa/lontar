<script setup>
import { Eye, MousePointerClick, PenLine, TrendingDown, TrendingUp, UserRound } from '@lucide/vue'

// Kartu statistik tunggal. trend: 'up' | 'down'; delta ditulis tanpa tanda (+/-).
defineProps({
  label: { type: String, required: true },
  value: { type: String, required: true },
  delta: { type: String, required: true },
  trend: { type: String, default: 'up' },
  icon: { type: String, required: true },
  tone: { type: String, default: 'blue' },
})

// Kunci ikon dari data (string, agar bisa dikirim dari server) menjadi komponen Lucide
const icons = { eye: Eye, user: UserRound, click: MousePointerClick, pen: PenLine }

// Warna ikon per nada. Ditulis lengkap agar terdeteksi Tailwind.
const tones = {
  blue: 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
  indigo: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
  sky: 'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400',
  violet: 'bg-violet-50 text-violet-600 dark:bg-violet-500/15 dark:text-violet-400',
}

// Warna lencana perubahan: hijau jika naik, merah jika turun
const trends = {
  up: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
  down: 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-400',
}
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:p-5">
    <div class="flex items-center justify-between text-sm font-semibold text-slate-900 dark:text-white">
      <span>{{ label }}</span>
      <span class="grid h-8 w-8 place-items-center rounded-lg" :class="tones[tone] ?? tones.blue">
        <component :is="icons[icon] ?? Eye" class="h-4 w-4" aria-hidden="true" />
      </span>
    </div>

    <div class="mt-3 flex flex-wrap items-baseline gap-x-2 gap-y-1">
      <span class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">{{ value }}</span>
      <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-xs font-semibold" :class="trends[trend] ?? trends.up">
        <component :is="trend === 'down' ? TrendingDown : TrendingUp" class="h-3 w-3" aria-hidden="true" />
        {{ delta }}
      </span>
    </div>

    <p class="mt-1 text-xs text-slate-400">vs. periode lalu</p>
  </div>
</template>
