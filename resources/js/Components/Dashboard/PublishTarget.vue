<script setup>
// Kartu "Target publikasi bulanan" berbentuk busur setengah lingkaran.
// percent: 0-100; done dan goal: jumlah artikel terbit dan targetnya.
defineProps({
  percent: { type: Number, required: true },
  done: { type: Number, required: true },
  goal: { type: Number, required: true },
  note: { type: String, default: '' },
})
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:p-5">
    <h2 class="font-bold text-slate-900 dark:text-white">Target publikasi bulanan</h2>

    <div class="relative mx-auto mt-3 w-full max-w-[240px]">
      <!-- pathLength=100 membuat stroke-dasharray bisa langsung memakai persen -->
      <svg viewBox="0 0 200 110" class="w-full" role="img" :aria-label="`Target tercapai ${percent} persen`">
        <path d="M12 100A88 88 0 0 1 188 100" pathLength="100" fill="none" stroke-width="14" stroke-linecap="round" class="stroke-slate-100 dark:stroke-slate-800" />
        <path d="M12 100A88 88 0 0 1 188 100" pathLength="100" fill="none" stroke-width="14" stroke-linecap="round" stroke="#2563eb" :stroke-dasharray="`${percent} 100`" />
      </svg>

      <div class="absolute inset-x-0 bottom-0 text-center">
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ percent }}%</p>
        <p class="text-xs text-slate-400">{{ done }} dari {{ goal }} artikel</p>
      </div>
    </div>

    <p v-if="note" class="mt-3 text-center text-xs text-slate-500">{{ note }}</p>
  </div>
</template>
