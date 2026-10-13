<script setup>
import { computed } from 'vue'

/*
 * Grafik batang "Hari paling aktif".
 * days: [{ label, height }] dengan height 0-100 (persen dari batang tertinggi).
 * peakValue: angka yang ditampilkan di atas batang tertinggi.
 */
const props = defineProps({
  days: { type: Array, required: true },
  peakValue: { type: String, default: '' },
})

// Tinggi tertinggi menentukan batang yang disorot
const peak = computed(() => Math.max(...props.days.map((d) => d.height)))
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:p-5">
    <h2 class="font-bold text-slate-900 dark:text-white">Hari paling aktif</h2>

    <div class="mt-4 flex h-52 items-end justify-between gap-2">
      <div
        v-for="day in days"
        :key="day.label"
        class="flex h-full flex-1 flex-col items-center justify-end gap-2"
      >
        <span v-if="day.height === peak" class="text-xs font-bold text-slate-900 dark:text-white">{{ peakValue }}</span>

        <!-- Tinggi batang diskalakan 75% agar ada ruang untuk angka dan label -->
        <div
          class="bar-grow w-full rounded-lg"
          :class="day.height === peak ? 'bg-brand' : 'bg-slate-100 dark:bg-slate-800'"
          :style="{ height: `${day.height * 0.75}%` }"
        ></div>

        <span
          class="text-[11px]"
          :class="day.height === peak ? 'font-bold text-brand' : 'text-slate-400'"
        >{{ day.label }}</span>
      </div>
    </div>
  </div>
</template>
