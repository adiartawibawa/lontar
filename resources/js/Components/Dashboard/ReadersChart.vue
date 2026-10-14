<script setup>
import { computed } from 'vue'
import { TrendingUp } from '@lucide/vue'

/*
 * Kartu "Total pembaca": angka total, grafik area, dan tiga ringkasan.
 * points: deret angka untuk grafik; xLabels: label sumbu X di bawah grafik;
 * mini: [{ label, value, tone }] dengan tone blue | emerald | amber.
 */
const props = defineProps({
  total: { type: String, required: true },
  delta: { type: String, required: true },
  points: { type: Array, required: true },
  xLabels: { type: Array, default: () => [] },
  mini: { type: Array, default: () => [] },
  periodLabel: { type: String, default: '30 hari terakhir' },
})

// Ukuran kanvas SVG (sama dengan atribut viewBox pada template)
const W = 600
const H = 180

// Nilai tertinggi + 10% ruang atas agar garis tidak menempel di tepi
const max = computed(() => Math.max(...props.points) * 1.1)

// Koordinat [x, y] tiap titik; sumbu Y dibalik karena SVG menghitung dari atas
const coords = computed(() => {
  const steps = Math.max(props.points.length - 1, 1)

  return props.points.map((v, i) => [(i * W) / steps, H - (v / max.value) * H])
})

// Garis grafik, dan bentuk area di bawahnya (garis + menutup ke dasar)
const linePath = computed(() =>
  coords.value.map(([x, y], i) => `${i ? 'L' : 'M'}${x.toFixed(1)} ${y.toFixed(1)}`).join(''),
)
const areaPath = computed(() => `${linePath.value}L${W} ${H}L0 ${H}Z`)

// Warna garis bawah kartu ringkasan per nada
const miniTones = {
  blue: 'border-b-blue-500 dark:border-b-blue-500',
  emerald: 'border-b-emerald-500 dark:border-b-emerald-500',
  amber: 'border-b-amber-500 dark:border-b-amber-500',
}
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:p-5">
    <div class="flex items-start justify-between">
      <h2 class="font-bold text-slate-900 dark:text-white">Total pembaca</h2>
      <span class="text-xs text-slate-400">{{ periodLabel }}</span>
    </div>

    <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:gap-6">
      <div class="shrink-0 sm:w-40">
        <p class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">{{ total }}</p>
        <span class="mt-2 inline-flex items-center gap-1 rounded-md bg-emerald-100 px-1.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">
          <TrendingUp class="h-3 w-3" aria-hidden="true" />{{ delta }}
        </span>
        <span class="ml-1 text-xs text-slate-400">vs. periode lalu</span>
      </div>

      <div class="min-w-0 flex-1">
        <svg
          viewBox="0 0 600 180"
          preserveAspectRatio="none"
          class="h-40 w-full overflow-visible sm:h-44"
          role="img"
          :aria-label="`Grafik total pembaca ${periodLabel}`"
        >
          <defs>
            <linearGradient id="readers-fill" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" stop-color="#2563eb" stop-opacity=".25" />
              <stop offset="1" stop-color="#2563eb" stop-opacity="0" />
            </linearGradient>
          </defs>

          <!-- Garis bantu horizontal -->
          <line
            v-for="i in 4"
            :key="i"
            x1="0"
            :x2="W"
            :y1="((i - 1) * H) / 3"
            :y2="((i - 1) * H) / 3"
            stroke="currentColor"
            class="text-slate-100 dark:text-slate-800"
            stroke-dasharray="3 4"
            vector-effect="non-scaling-stroke"
          />

          <path :d="areaPath" fill="url(#readers-fill)" />
          <path :d="linePath" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
        </svg>

        <div class="mt-1 flex justify-between text-[11px] text-slate-400">
          <span v-for="label in xLabels" :key="label">{{ label }}</span>
        </div>
      </div>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-2 sm:gap-3">
      <div
        v-for="item in mini"
        :key="item.label"
        class="rounded-xl border border-b-4 border-slate-200 p-3 dark:border-slate-800"
        :class="miniTones[item.tone] ?? miniTones.blue"
      >
        <p class="text-lg font-extrabold text-slate-900 dark:text-white sm:text-xl">{{ item.value }}</p>
        <p class="text-xs text-slate-400">{{ item.label }}</p>
      </div>
    </div>
  </div>
</template>
