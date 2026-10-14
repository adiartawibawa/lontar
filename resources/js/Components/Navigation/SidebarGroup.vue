<script setup>
import { computed, ref, useId, watch } from 'vue'
import { ChevronRight } from '@lucide/vue'
import SidebarItem from '@/Components/Navigation/SidebarItem.vue'
import { hasActiveChild, isActive, isEnabled } from '@/lib/menu'

/*
 * Menu bertingkat: tombol induk yang membuka/menutup daftar submenu.
 * item : { label, icon, children: [...] }
 * path : path halaman saat ini (untuk menentukan anak yang aktif)
 *
 * Perilaku:
 * - Terbuka otomatis jika salah satu anak sedang aktif.
 * - Induk diberi penanda (ikon biru, teks tebal) saat salah satu anaknya aktif.
 * - Jika semua anak belum tersedia, induk tampil redup dan tidak bisa dibuka.
 */
const props = defineProps({
  item: { type: Object, required: true },
  path: { type: String, required: true },
})

const panelId = `submenu-${useId()}`

// Keadaan turunan dari data menu
const enabled = computed(() => isEnabled(props.item))
const childActive = computed(() => hasActiveChild(props.item, props.path))

// Terbuka sejak awal bila ada anak yang aktif
const open = ref(childActive.value)

// Saat berpindah halaman ke salah satu anaknya, buka grup ini otomatis
watch(childActive, (value) => {
  if (value) open.value = true
})

/**
 * Membuka atau menutup submenu.
 */
function toggle() {
  open.value = !open.value
}
</script>

<template>
  <!-- Grup yang belum tersedia -->
  <span
    v-if="!enabled"
    class="flex h-10 cursor-not-allowed select-none items-center gap-3 rounded-xl px-3 text-slate-600 opacity-50 dark:text-slate-400"
    aria-disabled="true"
    title="Segera hadir"
  >
    <component :is="item.icon" class="h-[18px] w-[18px] shrink-0" aria-hidden="true" />
    <span class="flex-1 truncate">{{ item.label }}</span>
  </span>

  <div v-else>
    <button
      type="button"
      class="flex h-10 w-full items-center gap-3 rounded-xl px-3 text-left transition-colors hover:bg-slate-100 dark:hover:bg-slate-800"
      :class="childActive ? 'font-semibold text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400'"
      :aria-expanded="open"
      :aria-controls="panelId"
      @click="toggle"
    >
      <component
        :is="item.icon"
        class="h-[18px] w-[18px] shrink-0"
        :class="childActive ? 'text-brand dark:text-blue-400' : ''"
        aria-hidden="true"
      />
      <span class="flex-1 truncate">{{ item.label }}</span>
      <ChevronRight
        class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200 motion-reduce:transition-none"
        :class="open ? 'rotate-90' : ''"
        aria-hidden="true"
      />
    </button>

    <!--
      Panel submenu. Tinggi dianimasikan lewat grid-template-rows (0fr -> 1fr);
      `inert` saat tertutup mencegah submenu yang tersembunyi bisa difokus keyboard.
    -->
    <div
      :id="panelId"
      class="grid transition-[grid-template-rows] duration-200 motion-reduce:transition-none"
      :class="open ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
      :inert="open ? undefined : true"
    >
      <ul class="min-h-0 overflow-hidden">
        <!-- Garis penuntun di sisi kiri submenu, sejajar dengan kolom ikon -->
        <li class="ml-[21px] space-y-0.5 border-l border-slate-200 py-1 pl-2 dark:border-slate-800">
          <SidebarItem
            v-for="child in item.children"
            :key="child.key"
            :item="child"
            :active="isActive(child, path)"
            nested
          />
        </li>
      </ul>
    </div>
  </div>
</template>
