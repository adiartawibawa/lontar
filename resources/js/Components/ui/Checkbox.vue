<script setup>
import { useId } from 'vue'

// Kotak centang dengan label (isi slot). Mendukung v-model bertipe boolean.
const model = defineModel({ type: Boolean, default: false })
const id = useId()
</script>

<template>
  <label :for="id" class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
    <input
      :id="id"
      v-model="model"
      type="checkbox"
      class="h-4 w-4 rounded border-slate-300 accent-blue-600 dark:border-slate-600"
    />
    <slot />
  </label>
</template>
