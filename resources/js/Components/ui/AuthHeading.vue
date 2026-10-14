<script setup>
// Kepala halaman otentikasi: ikon konteks, judul, dan penjelasan singkat.
defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
  icon: { type: [Object, Function], default: null },
})
</script>

<template>
  <div class="mb-6">
    <span
      v-if="icon"
      class="mb-4 grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-brand dark:bg-blue-500/15 dark:text-blue-400"
    >
      <component :is="icon" class="h-5 w-5" aria-hidden="true" />
    </span>

    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ title }}</h1>
    <p v-if="description" class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">{{ description }}</p>
  </div>
</template>
