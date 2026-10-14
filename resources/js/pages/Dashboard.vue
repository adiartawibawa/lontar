<script setup>
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { CalendarDays, Download, Plus } from '@lucide/vue'
import Button from '@/Components/ui/Button.vue'
import PageHeader from '@/Components/ui/PageHeader.vue'
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
    <PageHeader title="Dashboard" description="Ringkasan performa konten dan aktivitas redaksi.">
      <Button variant="secondary" :icon="CalendarDays">{{ period.range }}</Button>
      <Button variant="secondary">{{ period.preset }}</Button>
      <Button variant="secondary" :icon="Plus">Widget</Button>
      <Button :icon="Download">Ekspor</Button>
    </PageHeader>

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
