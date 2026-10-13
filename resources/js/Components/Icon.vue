<script setup>
import { computed } from 'vue'
import { icons } from '@/lib/icons'

// name: kunci ikon di lib/icons.js. Ukuran diatur lewat class dari pemanggil
// (misalnya class="h-4 w-4"), warna mengikuti warna teks (currentColor).
const props = defineProps({
  name: { type: String, required: true },
})

// Path SVG untuk ikon yang diminta; kosong jika namanya tidak dikenal
const path = computed(() => icons[props.name] ?? '')
</script>

<template>
  <svg
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="1.9"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
  >
    <path :d="path" />
  </svg>
</template>
