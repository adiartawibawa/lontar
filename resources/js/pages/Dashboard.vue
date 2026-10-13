<script setup>
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Icon from '@/Components/Icon.vue'
import StatCard from '@/Components/Dashboard/StatCard.vue'
import ReadersChart from '@/Components/Dashboard/ReadersChart.vue'
import ActiveDaysChart from '@/Components/Dashboard/ActiveDaysChart.vue'
import PopularContentTable from '@/Components/Dashboard/PopularContentTable.vue'
import PublishTarget from '@/Components/Dashboard/PublishTarget.vue'
import WritingAssistant from '@/Components/Dashboard/WritingAssistant.vue'
import { sampleDashboard } from '@/data/sampleDashboard'

// Halaman ini tampil di dalam layout area login
defineOptions({ layout: DashboardLayout })

/*
 * Semua data bisa dikirim dari controller lewat Inertia::render('Dashboard', [...]).
 * Selama belum dikirim, dipakai DATA CONTOH dari data/sampleDashboard.js.
 */
defineProps({
  period: { type: Object, default: () => sampleDashboard.period },
  stats: { type: Array, default: () => sampleDashboard.stats },
  readers: { type: Object, default: () => sampleDashboard.readers },
  activeDays: { type: Object, default: () => sampleDashboard.activeDays },
  popular: { type: Array, default: () => sampleDashboard.popular },
  target: { type: Object, default: () => sampleDashboard.target },
})
</script>

<template>
  <Head title="Dashboard" />

  <div class="space-y-4 sm:space-y-6">
    <!-- Judul dan kontrol periode (tombolnya belum berfungsi) -->
    <div class="flex flex-col justify-between gap-3 md:flex-row md:items-center">
      <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Dashboard</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Ringkasan performa konten dan aktivitas redaksi.</p>
      </div>

      <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
        <button type="button" class="flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 dark:border-slate-700 dark:bg-slate-900">
          <Icon name="calendar" class="h-4 w-4 text-slate-400" />{{ period.range }}
        </button>
        <button type="button" class="h-10 rounded-xl border border-slate-200 bg-white px-3 dark:border-slate-700 dark:bg-slate-900">{{ period.preset }}</button>
        <button type="button" class="h-10 rounded-xl border border-slate-200 bg-white px-3 dark:border-slate-700 dark:bg-slate-900">+ Widget</button>
        <button type="button" class="h-10 rounded-xl bg-brand px-4 font-semibold text-white hover:bg-blue-700">Ekspor</button>
      </div>
    </div>

    <!-- Kartu statistik -->
    <section class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
      <StatCard v-for="stat in stats" :key="stat.label" v-bind="stat" />
    </section>

    <!-- Grafik pembaca dan hari paling aktif -->
    <section class="grid grid-cols-1 gap-4 sm:gap-6 xl:grid-cols-3">
      <ReadersChart class="xl:col-span-2" v-bind="readers" :period-label="period.preset" />
      <ActiveDaysChart v-bind="activeDays" />
    </section>

    <!-- Tabel konten dan kartu samping -->
    <section class="grid grid-cols-1 gap-4 sm:gap-6 xl:grid-cols-3">
      <PopularContentTable class="xl:col-span-2" :rows="popular" />

      <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 xl:grid-cols-1">
        <PublishTarget v-bind="target" />
        <WritingAssistant />
      </div>
    </section>
  </div>
</template>
