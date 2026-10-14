<script setup>
// Judul halaman area login. Tombol aksi diisi lewat slot bawaan.
defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
})
</script>

<template>
  <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ title }}</h1>
      <p v-if="description" class="text-sm text-slate-500 dark:text-slate-400">{{ description }}</p>
    </div>

    <div v-if="$slots.default" class="flex flex-wrap items-center gap-2">
      <slot />
    </div>
  </div>
</template>
