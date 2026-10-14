<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { LoaderCircle } from '@lucide/vue'

/*
 * Tombol serbaguna. Jika `href` diisi, dirender sebagai tautan Inertia.
 * variant : primary | secondary | danger | ghost
 * size    : md (tinggi 40px, untuk bilah alat) | lg (44px, sejajar dengan kolom isian)
 * icon    : komponen ikon Lucide; diganti spinner saat `loading`
 * trailing: tempatkan ikon di kanan teks
 */
const props = defineProps({
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  href: { type: String, default: null },
  type: { type: String, default: 'button' },
  icon: { type: [Object, Function], default: null },
  trailing: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  block: { type: Boolean, default: false },
})

// Gaya per varian (ditulis lengkap agar terdeteksi Tailwind)
const variants = {
  primary: 'bg-brand text-white hover:bg-blue-700',
  secondary:
    'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800',
  danger: 'bg-rose-600 text-white hover:bg-rose-700',
  ghost: 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
}

const sizes = {
  md: 'h-10 px-4',
  lg: 'h-11 px-5',
}

// Tautan memakai komponen Link Inertia; selain itu elemen <button> biasa
const tag = computed(() => (props.href ? Link : 'button'))

// Atribut khusus: tautan butuh href, tombol butuh type dan status disabled
const tagAttrs = computed(() =>
  props.href ? { href: props.href } : { type: props.type, disabled: props.disabled || props.loading },
)
</script>

<template>
  <component
    :is="tag"
    v-bind="tagAttrs"
    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold transition-colors disabled:cursor-not-allowed disabled:opacity-50"
    :class="[variants[variant] ?? variants.primary, sizes[size] ?? sizes.md, block ? 'w-full' : '']"
  >
    <!-- Spinner menggantikan ikon saat proses berjalan -->
    <LoaderCircle v-if="loading" class="h-4 w-4 animate-spin motion-reduce:animate-none" aria-hidden="true" />
    <component :is="icon" v-else-if="icon && !trailing" class="h-4 w-4" aria-hidden="true" />

    <slot />

    <component :is="icon" v-if="icon && trailing && !loading" class="h-4 w-4" aria-hidden="true" />
  </component>
</template>
