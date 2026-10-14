<script setup>
import { Link } from '@inertiajs/vue3'

/*
 * Satu butir menu (tanpa anak).
 * item   : { label, icon?, href?, badge? }
 * active : menu sedang aktif (halaman ini)
 * nested : tampil sebagai submenu (lebih kecil, tanpa ikon)
 * Item tanpa href tampil redup dan tidak bisa diklik ("segera hadir").
 */
defineProps({
  item: { type: Object, required: true },
  active: { type: Boolean, default: false },
  nested: { type: Boolean, default: false },
})

// Gaya per keadaan (ditulis lengkap agar terdeteksi Tailwind)
const sizes = {
  top: 'h-10 gap-3 px-3',
  nested: 'h-9 gap-2 px-3 text-[13px]',
}
const activeClass = 'bg-blue-50 font-semibold text-brand dark:bg-blue-500/15 dark:text-blue-400'
const idleClass =
  'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white'
</script>

<template>
  <!-- Menu yang sudah punya halaman -->
  <Link
    v-if="item.href"
    :href="item.href"
    class="flex items-center rounded-xl transition-colors"
    :class="[nested ? sizes.nested : sizes.top, active ? activeClass : idleClass]"
    :aria-current="active ? 'page' : undefined"
  >
    <component :is="item.icon" v-if="item.icon && !nested" class="h-[18px] w-[18px] shrink-0" aria-hidden="true" />
    <span class="flex-1 truncate">{{ item.label }}</span>
    <span
      v-if="item.badge"
      class="rounded-md bg-blue-100 px-1.5 py-0.5 text-[11px] font-semibold text-blue-700 dark:bg-blue-500/20 dark:text-blue-300"
    >{{ item.badge }}</span>
  </Link>

  <!-- Menu yang fiturnya belum dibangun: redup dan tidak bisa diklik -->
  <span
    v-else
    class="flex cursor-not-allowed select-none items-center rounded-xl text-slate-600 opacity-50 dark:text-slate-400"
    :class="nested ? sizes.nested : sizes.top"
    aria-disabled="true"
    title="Segera hadir"
  >
    <component :is="item.icon" v-if="item.icon && !nested" class="h-[18px] w-[18px] shrink-0" aria-hidden="true" />
    <span class="flex-1 truncate">{{ item.label }}</span>
  </span>
</template>
