<script setup>
/*
 * Kartu konten bergaya sama dengan kartu Dashboard.
 * title/description/icon : kepala kartu (opsional)
 * flush                  : isi tanpa padding (untuk tabel selebar kartu)
 * slot `actions`         : tombol di kanan kepala kartu
 */
defineProps({
  title: { type: String, default: '' },
  description: { type: String, default: '' },
  icon: { type: [Object, Function], default: null },
  flush: { type: Boolean, default: false },
})
</script>

<template>
  <section class="rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
    <header
      v-if="title || $slots.actions"
      class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-4 dark:border-slate-800 sm:px-5"
    >
      <div class="flex items-start gap-3">
        <span
          v-if="icon"
          class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-blue-50 text-brand dark:bg-blue-500/15 dark:text-blue-400"
        >
          <component :is="icon" class="h-[18px] w-[18px]" aria-hidden="true" />
        </span>
        <div>
          <h2 class="font-bold text-slate-900 dark:text-white">{{ title }}</h2>
          <p v-if="description" class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ description }}</p>
        </div>
      </div>
      <slot name="actions" />
    </header>

    <div :class="flush ? '' : 'p-4 sm:p-5'">
      <slot />
    </div>
  </section>
</template>
