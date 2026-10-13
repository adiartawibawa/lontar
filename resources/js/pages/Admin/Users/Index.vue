<script setup>
import { ref, watch, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Pagination from '@/Components/Pagination.vue'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
    users: Object, // hasil paginate dari server (data + info halaman)
    filters: Object, // { search }
})

const page = usePage()

// Pengguna yang sedang login, dipakai untuk menyembunyikan tombol pada baris sendiri
const me = computed(() => page.props.auth.user)

// Kata kunci pencarian; nilai awal dari URL
const search = ref(props.filters.search ?? '')

// Pencarian otomatis 300 ms setelah berhenti mengetik (debounce sederhana)
let timer = null
watch(search, (value) => {
    clearTimeout(timer)
    timer = setTimeout(() => {
        router.get('/admin/users', { search: value || undefined }, {
            preserveState: true, // fokus input tidak hilang
            replace: true, // tidak menambah riwayat browser tiap ketikan
        })
    }, 300)
})

/**
 * Menonaktifkan atau mengaktifkan akun setelah konfirmasi.
 * Aturan keamanan (mis. tidak boleh menonaktifkan diri sendiri) ditegakkan di server.
 */
function toggleActive(user) {
    const aksi = user.is_active ? 'deactivate' : 'activate'
    const pesan = user.is_active
        ? `Nonaktifkan akun ${user.username}?`
        : `Aktifkan kembali akun ${user.username}?`

    if (!window.confirm(pesan)) return

    router.patch(`/admin/users/${user.id}/${aksi}`, {}, { preserveScroll: true })
}
</script>

<template>

    <Head title="Pengguna" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Pengguna</h2>
        <Link href="/admin/users/create" class="rounded bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-700">
            Tambah pengguna
        </Link>
    </div>

    <!-- Pesan error dari aturan server (mis. menonaktifkan akun sendiri) -->
    <p v-if="page.props.errors.user" class="mt-4 rounded bg-red-50 px-4 py-2 text-sm text-red-700">
        {{ page.props.errors.user }}
    </p>

    <input v-model="search" type="search" placeholder="Cari nama, username, atau email"
        class="mt-4 w-full max-w-sm rounded border border-gray-300 px-3 py-2" />

    <div class="mt-4 overflow-x-auto rounded-lg bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Peran</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Terdaftar</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users.data" :key="user.id" class="border-b last:border-0">
                    <td class="px-4 py-3">{{ user.first_name }} {{ user.last_name }}</td>
                    <td class="px-4 py-3">{{ user.username }}</td>
                    <td class="px-4 py-3">{{ user.email }}</td>
                    <td class="px-4 py-3 capitalize">{{ user.role ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span :class="user.is_active ? 'text-green-700' : 'text-red-700'">
                            {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ user.created_at }}</td>
                    <td class="space-x-3 whitespace-nowrap px-4 py-3 text-right">
                        <Link :href="`/admin/users/${user.id}/edit`" class="text-gray-700 hover:underline">Ubah</Link>
                        <!-- Tidak ada tombol nonaktifkan pada baris akun sendiri -->
                        <button v-if="user.id !== me.id" type="button"
                            :class="user.is_active ? 'text-red-600' : 'text-green-700'" class="hover:underline"
                            @click="toggleActive(user)">
                            {{ user.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </td>
                </tr>
                <tr v-if="users.data.length === 0">
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Tidak ada pengguna ditemukan.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :paginator="users" />
</template>
