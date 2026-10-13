<script setup>
import { Link } from '@inertiajs/vue3'

// Objek paginator dari Laravel (berisi from, to, total, prev_page_url, next_page_url)
defineProps({ paginator: { type: Object, required: true } })
</script>

<template>
    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
        <!-- Ringkasan jumlah data -->
        <p>Menampilkan {{ paginator.from ?? 0 }} sampai {{ paginator.to ?? 0 }} dari {{ paginator.total }}</p>

        <div class="flex gap-2">
            <!-- Tombol nonaktif jika tidak ada halaman sebelum/sesudahnya -->
            <Link v-if="paginator.prev_page_url" :href="paginator.prev_page_url"
                class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-100">Sebelumnya</Link>
            <span v-else class="rounded border border-gray-200 px-3 py-1 text-gray-400">Sebelumnya</span>

            <Link v-if="paginator.next_page_url" :href="paginator.next_page_url"
                class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-100">Berikutnya</Link>
            <span v-else class="rounded border border-gray-200 px-3 py-1 text-gray-400">Berikutnya</span>
        </div>
    </div>
</template>
