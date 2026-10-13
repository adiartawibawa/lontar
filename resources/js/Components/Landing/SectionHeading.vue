<script setup>
// Judul bagian dengan garis tipis memanjang di sebelah kanan.
// large: ukuran lebih besar untuk judul kolom utama (sidebar memakai ukuran biasa).
defineProps({
  large: { type: Boolean, default: false },
})
</script>

<template>
  <div class="flex items-center gap-4">
    <h2
      class="font-bold text-slate-900 dark:text-white"
      :class="large ? 'text-xl sm:text-2xl' : 'text-lg'"
    >
      <slot />
    </h2>
    <span class="h-px flex-1 bg-slate-200 dark:bg-slate-800"></span>
  </div>
</template>
