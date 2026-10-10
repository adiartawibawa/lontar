<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

// Data halaman aktif; `auth.user` dibagikan dari HandleInertiaRequests
const page = usePage()

// Pengguna yang sedang login
const user = computed(() => page.props.auth.user)

// Nama yang ditampilkan di header (gabungan nama depan dan belakang)
const displayName = computed(() => `${user.value.first_name} ${user.value.last_name}`)

/**
 * Keluar dari aplikasi lewat rute POST /logout milik Fortify.
 * Memakai POST (bukan link biasa) agar terlindung CSRF.
 */
function logout() {
    router.post('/logout')
}
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header atas: nama aplikasi di kiri, info pengguna dan tombol keluar di kanan -->
        <header class="bg-white shadow">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
                <Link href="/dashboard" class="text-lg font-semibold text-gray-800">Lontar</Link>

                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>{{ displayName }}</span>
                    <button type="button" class="text-red-600 hover:underline" @click="logout">
                        Keluar
                    </button>
                </div>
            </div>
        </header>

        <!-- Area konten utama: diisi oleh halaman yang memakai layout ini -->
        <main class="mx-auto max-w-7xl px-4 py-6">
            <slot />
        </main>
    </div>
</template>
