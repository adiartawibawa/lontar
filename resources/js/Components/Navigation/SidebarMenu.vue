<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SidebarSection from '@/Components/Navigation/SidebarSection.vue'
import { buildMenu, pathOf } from '@/lib/menu'

/*
 * Menu navigasi sidebar lengkap.
 * sections : konfigurasi menu (lihat lib/navigation.js).
 * Menyaring menu sesuai permission pengguna dan menandai menu aktif
 * berdasarkan URL halaman saat ini.
 */
const props = defineProps({
  sections: { type: Array, required: true },
})

const page = usePage()

// Path halaman saat ini, tanpa query dan hash
const path = computed(() => pathOf(page.url))

// Menu yang boleh dilihat pengguna. Ini hanya pengaturan tampilan;
// akses sebenarnya tetap dijaga middleware di server.
const visible = computed(() => buildMenu(props.sections, page.props.auth?.permissions ?? []))
</script>

<template>
  <nav class="flex-1 overflow-y-auto pb-4 text-sm font-medium" aria-label="Navigasi utama">
    <SidebarSection
      v-for="(section, index) in visible"
      :key="section.heading ?? `section-${index}`"
      :heading="section.heading"
      :items="section.items"
      :path="path"
    />
  </nav>
</template>
