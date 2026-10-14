<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

/*
 * Tombol berisi ikon saja (mis. aksi di baris tabel).
 * `label` wajib: dipakai sebagai nama aksesibel dan tooltip.
 * tone: default | danger | success (warna saat disorot)
 */
const props = defineProps({
  label: { type: String, required: true },
  icon: { type: [Object, Function], required: true },
  href: { type: String, default: null },
  tone: { type: String, default: 'default' },
})

const tones = {
  default: 'hover:bg-slate-100 hover:text-brand dark:hover:bg-slate-800',
  danger: 'hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10',
  success: 'hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-500/10',
}

const tag = computed(() => (props.href ? Link : 'button'))
const tagAttrs = computed(() => (props.href ? { href: props.href } : { type: 'button' }))
</script>

<template>
  <component
    :is="tag"
    v-bind="tagAttrs"
    class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 transition-colors dark:text-slate-400"
    :class="tones[tone] ?? tones.default"
    :aria-label="label"
    :title="label"
  >
    <component :is="icon" class="h-4 w-4" aria-hidden="true" />
  </component>
</template>
