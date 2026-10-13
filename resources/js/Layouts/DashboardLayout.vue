<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import BrandLogo from '@/Components/BrandLogo.vue'
import Icon from '@/Components/Icon.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue'
import { navigation } from '@/lib/navigation'

/*
 * Layout area login: sidebar di kiri, bilah atas, dan isi halaman.
 * Di layar kecil sidebar menjadi laci yang dibuka lewat tombol menu.
 */
const page = usePage()

// Pengguna yang login dan permission miliknya (dibagikan HandleInertiaRequests)
const user = computed(() => page.props.auth.user)
const permissions = computed(() => page.props.auth.permissions ?? [])

// Nama lengkap dan inisial untuk avatar
const fullName = computed(() => `${user.value.first_name} ${user.value.last_name}`)
const initials = computed(
    () => `${user.value.first_name?.[0] ?? ''}${user.value.last_name?.[0] ?? ''}`.toUpperCase(),
)

// Menu yang ditampilkan: item ber-permission hanya untuk pengguna yang berhak.
// Ini hanya pengaturan tampilan; akses tetap dijaga middleware di server.
const menu = computed(() =>
    navigation.filter((item) => !item.permission || permissions.value.includes(item.permission)),
)

/**
 * Menentukan apakah sebuah menu sedang aktif berdasarkan URL halaman.
 */
function isActive(item) {
    if (!item.href) return false

    const path = page.url.split('?')[0]

    return item.exact ? path === item.href : path.startsWith(item.href)
}

// Gaya menu: dasar, aktif, dan tidak aktif (ditulis lengkap agar terdeteksi Tailwind)
const itemBase = 'flex h-10 items-center gap-3 rounded-xl px-3'
const itemActive = 'bg-blue-50 text-brand dark:bg-blue-500/15 dark:text-blue-400'
const itemIdle = 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'

// Status laci sidebar (layar kecil) dan menu pengguna
const sidebarOpen = ref(false)
const userMenuOpen = ref(false)
const userMenuRef = ref(null)

// Tutup laci dan menu setiap pindah halaman
watch(() => page.url, () => {
    sidebarOpen.value = false
    userMenuOpen.value = false
})

/**
 * Menutup menu pengguna saat mengklik di luar area menu tersebut.
 */
function closeOnOutsideClick(event) {
    if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
        userMenuOpen.value = false
    }
}

/**
 * Menutup laci sidebar dan menu pengguna dengan tombol Escape.
 */
function closeOnEscape(event) {
    if (event.key === 'Escape') {
        sidebarOpen.value = false
        userMenuOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', closeOnOutsideClick)
    document.addEventListener('keydown', closeOnEscape)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', closeOnOutsideClick)
    document.removeEventListener('keydown', closeOnEscape)
})

/**
 * Keluar dari aplikasi lewat rute POST /logout milik Fortify (terlindung CSRF).
 */
function logout() {
    router.post('/logout')
}
</script>

<template>
    <div class="min-h-screen bg-[#f3f5f9] dark:bg-[#080c16]">
        <!-- Latar gelap di belakang laci sidebar (layar kecil) -->
        <div v-show="sidebarOpen" class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden" @click="sidebarOpen = false">
        </div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white p-4 transition-transform duration-200 dark:border-slate-800 dark:bg-slate-900 lg:translate-x-0"
            :class="{ '-translate-x-full': !sidebarOpen }" aria-label="Navigasi utama">
            <div class="flex h-10 items-center justify-between px-2">
                <Link href="/dashboard">
                    <BrandLogo />
                </Link>
                <button type="button" class="rounded-lg p-2 hover:bg-slate-100 dark:hover:bg-slate-800 lg:hidden"
                    aria-label="Tutup menu" @click="sidebarOpen = false">
                    <Icon name="close" class="h-5 w-5" />
                </button>
            </div>

            <nav class="mt-6 flex-1 space-y-1 overflow-y-auto text-sm font-medium">
                <template v-for="item in menu" :key="item.key">
                    <!-- Menu yang sudah punya halaman -->
                    <Link v-if="item.href" :href="item.href" :class="[itemBase, isActive(item) ? itemActive : itemIdle]"
                        :aria-current="isActive(item) ? 'page' : undefined">
                        <Icon :name="item.icon" class="h-[18px] w-[18px]" />
                        <span class="flex-1">{{ item.label }}</span>
                    </Link>

                    <!-- Menu yang fiturnya belum dibangun: tampil redup dan tidak bisa diklik -->
                    <span v-else :class="[itemBase, 'cursor-not-allowed text-slate-600 opacity-50 dark:text-slate-400']"
                        aria-disabled="true" title="Segera hadir">
                        <Icon :name="item.icon" class="h-[18px] w-[18px]" />
                        <span class="flex-1">{{ item.label }}</span>
                    </span>
                </template>
            </nav>
        </aside>

        <div class="min-h-screen lg:pl-64">
            <!-- Bilah atas -->
            <header
                class="sticky top-0 z-20 flex h-16 items-center gap-2 border-b border-slate-200 bg-white/80 px-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 sm:gap-3 sm:px-6">
                <button type="button" class="-ml-2 rounded-lg p-2 hover:bg-slate-100 dark:hover:bg-slate-800 lg:hidden"
                    aria-label="Buka menu" @click="sidebarOpen = true">
                    <Icon name="menu" class="h-5 w-5" />
                </button>

                <!-- Kolom pencarian: hanya tampilan, belum terhubung ke fitur pencarian -->
                <label
                    class="flex h-10 max-w-xs flex-1 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm dark:border-slate-700 dark:bg-slate-800/60">
                    <Icon name="search" class="h-4 w-4 shrink-0 text-slate-400" />
                    <input class="w-full bg-transparent outline-none placeholder:text-slate-400"
                        placeholder="Cari artikel, halaman, penulis…" />
                </label>

                <div class="ml-auto flex items-center gap-1.5 sm:gap-2">
                    <ThemeToggle class="border border-slate-200 dark:border-slate-700" />

                    <button type="button"
                        class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800"
                        aria-label="Notifikasi">
                        <Icon name="bell" class="h-[18px] w-[18px]" />
                    </button>

                    <!-- Avatar + menu pengguna (Profil dan Keluar) -->
                    <div ref="userMenuRef" class="relative">
                        <button type="button"
                            class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-amber-300 to-rose-400 text-sm font-bold text-white"
                            :title="fullName" aria-haspopup="menu" :aria-expanded="userMenuOpen"
                            @click="userMenuOpen = !userMenuOpen">{{ initials }}</button>

                        <div v-if="userMenuOpen"
                            class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-1 text-sm shadow-lg dark:border-slate-700 dark:bg-slate-900"
                            role="menu">
                            <div class="px-3 py-2">
                                <p class="truncate font-semibold text-slate-900 dark:text-white">{{ fullName }}</p>
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ user.email }}</p>
                            </div>
                            <Link href="/profile" role="menuitem"
                                class="block rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-800">Profil
                            </Link>
                            <button type="button" role="menuitem"
                                class="block w-full rounded-lg px-3 py-2 text-left text-rose-600 hover:bg-slate-100 dark:text-rose-400 dark:hover:bg-slate-800"
                                @click="logout">Keluar</button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-[1400px] p-4 sm:p-6">
                <!-- Pesan sukses dari server (flash), mis. setelah menyimpan data -->
                <p v-if="page.props.flash?.success"
                    class="mb-4 rounded-xl bg-green-50 px-4 py-2 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">
                    {{ page.props.flash.success }}</p>

                <slot />
            </main>
        </div>
    </div>
</template>
