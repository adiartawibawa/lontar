<script setup>
import { useId } from 'vue'
import SidebarGroup from '@/Components/Navigation/SidebarGroup.vue'
import SidebarItem from '@/Components/Navigation/SidebarItem.vue'
import { isActive, isGroup } from '@/lib/menu'

/*
 * Satu bagian menu: judul bagian (header name) dan daftar item di bawahnya.
 * heading : judul bagian; bila kosong, daftar tampil tanpa judul.
 * items   : item biasa atau grup (item dengan `children`).
 */
defineProps({
  heading: { type: String, default: '' },
  items: { type: Array, required: true },
  path: { type: String, required: true },
})

// Menghubungkan judul dengan daftarnya untuk pembaca layar
const headingId = `menu-heading-${useId()}`
</script>

<template>
  <div>
    <p
      v-if="heading"
      :id="headingId"
      class="px-3 pb-1.5 pt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500"
    >{{ heading }}</p>

    <ul class="space-y-0.5" :aria-labelledby="heading ? headingId : undefined">
      <li v-for="item in items" :key="item.key">
        <SidebarGroup v-if="isGroup(item)" :item="item" :path="path" />
        <SidebarItem v-else :item="item" :active="isActive(item, path)" />
      </li>
    </ul>
  </div>
</template>
