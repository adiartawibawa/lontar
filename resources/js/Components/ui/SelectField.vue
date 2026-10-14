<script setup>
import { useId } from 'vue'
import { ChevronDown, CircleAlert } from '@lucide/vue'

/*
 * Kolom pilihan (dropdown) dengan label dan pesan error. Mendukung v-model.
 * options: [{ value, label }]
 */
const model = defineModel({ type: String, default: '' })

defineProps({
  label: { type: String, required: true },
  options: { type: Array, required: true },
  error: { type: String, default: '' },
  required: { type: Boolean, default: false },
})

const id = useId()
</script>

<template>
  <div>
    <label :for="id" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ label }}</label>

    <div class="relative">
      <select
        :id="id"
        v-model="model"
        :required="required"
        class="field-ring h-11 w-full appearance-none rounded-xl border bg-white bg-none pl-3.5 pr-10 text-sm text-slate-900 outline-none transition-colors focus:ring-2 dark:bg-slate-900 dark:text-slate-100"
        :class="error
          ? 'border-rose-400 focus:ring-rose-500/25 dark:border-rose-500/70'
          : 'border-slate-300 focus:border-brand focus:ring-blue-500/25 dark:border-slate-700'"
        :aria-invalid="error ? 'true' : undefined"
      >
        <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
      </select>
      <ChevronDown class="pointer-events-none absolute right-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" aria-hidden="true" />
    </div>

    <p v-if="error" class="mt-1.5 flex items-start gap-1.5 text-sm text-rose-600 dark:text-rose-400" role="alert">
      <CircleAlert class="mt-0.5 h-4 w-4 shrink-0" aria-hidden="true" />
      {{ error }}
    </p>
  </div>
</template>
