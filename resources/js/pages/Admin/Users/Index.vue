<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { BadgeCheck, Pencil, Plus, Search, UserCheck, UserX, Users } from '@lucide/vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import Alert from '@/Components/ui/Alert.vue'
import Badge from '@/Components/ui/Badge.vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import IconButton from '@/Components/ui/IconButton.vue'
import PageHeader from '@/Components/ui/PageHeader.vue'
import TextField from '@/Components/ui/TextField.vue'
import { roleLabel } from '@/lib/roles'

defineOptions({ layout: DashboardLayout })

const props = defineProps({
  users: Object, // hasil paginate dari server (data + info halaman)
  filters: Object, // { search }
})

const page = usePage()

// Pengguna yang sedang login, dipakai untuk menyembunyikan aksi pada baris sendiri
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

// Batalkan pencarian yang tertunda saat halaman ditinggalkan
onBeforeUnmount(() => clearTimeout(timer))

/**
 * Inisial dua huruf untuk avatar di tabel.
 */
function initials(user) {
  return `${user.first_name?.[0] ?? ''}${user.last_name?.[0] ?? ''}`.toUpperCase()
}

/**
 * Menonaktifkan atau mengaktifkan akun setelah konfirmasi.
 * Aturan keamanan (mis. tidak boleh menonaktifkan diri sendiri) ditegakkan di server.
 */
function toggleActive(user) {
  const aksi = user.is_active ? 'deactivate' : 'activate'
  const pesan = user.is_active
    ? `Nonaktifkan akun ${user.username}? Pengguna ini tidak akan bisa masuk sampai diaktifkan kembali.`
    : `Aktifkan kembali akun ${user.username}?`

  if (!window.confirm(pesan)) return

  router.patch(`/admin/users/${user.id}/${aksi}`, {}, { preserveScroll: true })
}
</script>

<template>
  <Head title="Pengguna" />

  <div class="space-y-6">
    <PageHeader title="Pengguna" description="Kelola akun, peran, dan status pengguna.">
      <Button href="/admin/users/create" :icon="Plus">Tambah pengguna</Button>
    </PageHeader>

    <!-- Pesan error dari aturan server (mis. menonaktifkan akun sendiri) -->
    <Alert v-if="page.props.errors.user" variant="error">{{ page.props.errors.user }}</Alert>

    <Card flush>
      <div class="p-4 sm:p-5">
        <TextField
          v-model="search"
          type="search"
          label="Cari pengguna"
          hide-label
          :icon="Search"
          placeholder="Cari nama, username, atau email"
          class="max-w-sm"
        />
      </div>

      <!-- Tabel bisa digeser ke samping di layar sempit -->
      <div class="overflow-x-auto border-t border-slate-100 dark:border-slate-800">
        <table class="w-full min-w-[640px] text-sm">
          <thead class="border-b border-slate-100 text-left text-xs text-slate-400 dark:border-slate-800">
            <tr>
              <th scope="col" class="py-3 pl-4 font-medium sm:pl-5">Pengguna</th>
              <th scope="col" class="hidden font-medium md:table-cell">Username</th>
              <th scope="col" class="font-medium">Peran</th>
              <th scope="col" class="font-medium">Status</th>
              <th scope="col" class="hidden font-medium lg:table-cell">Terdaftar</th>
              <th scope="col" class="py-3 pr-4 text-right font-medium sm:pr-5"><span class="sr-only">Aksi</span></th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/30">
              <td class="py-3 pl-4 pr-3 sm:pl-5">
                <div class="flex items-center gap-3">
                  <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gradient-to-br from-amber-300 to-rose-400 text-xs font-bold text-white" aria-hidden="true">{{ initials(user) }}</span>
                  <div class="min-w-0">
                    <p class="truncate font-semibold text-slate-900 dark:text-slate-100">{{ user.first_name }} {{ user.last_name }}</p>
                    <p class="flex items-center gap-1 truncate text-xs text-slate-500 dark:text-slate-400">
                      {{ user.email }}
                      <BadgeCheck v-if="user.email_verified" class="h-3.5 w-3.5 shrink-0 text-emerald-500" aria-label="Email terverifikasi" />
                    </p>
                  </div>
                </div>
              </td>
              <td class="hidden text-slate-600 dark:text-slate-400 md:table-cell">{{ user.username }}</td>
              <td><Badge :tone="user.role === 'admin' ? 'blue' : 'slate'">{{ roleLabel(user.role) }}</Badge></td>
              <td><Badge :tone="user.is_active ? 'green' : 'rose'" dot>{{ user.is_active ? 'Aktif' : 'Nonaktif' }}</Badge></td>
              <td class="hidden text-slate-500 dark:text-slate-400 lg:table-cell">{{ user.created_at }}</td>
              <td class="py-3 pr-4 sm:pr-5">
                <div class="flex justify-end gap-1">
                  <IconButton :href="`/admin/users/${user.id}/edit`" :icon="Pencil" :label="`Ubah ${user.username}`" />

                  <!-- Tidak ada aksi nonaktifkan pada baris akun sendiri -->
                  <IconButton
                    v-if="user.id !== me.id"
                    :icon="user.is_active ? UserX : UserCheck"
                    :tone="user.is_active ? 'danger' : 'success'"
                    :label="user.is_active ? `Nonaktifkan ${user.username}` : `Aktifkan ${user.username}`"
                    @click="toggleActive(user)"
                  />
                </div>
              </td>
            </tr>

            <!-- Keadaan kosong: jelaskan penyebab dan langkah berikutnya -->
            <tr v-if="users.data.length === 0">
              <td colspan="6" class="px-4 py-12 text-center">
                <span class="mx-auto grid h-11 w-11 place-items-center rounded-xl bg-slate-100 text-slate-400 dark:bg-slate-800">
                  <Users class="h-5 w-5" aria-hidden="true" />
                </span>
                <p class="mt-3 font-semibold text-slate-900 dark:text-slate-100">Tidak ada pengguna yang cocok</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Coba kata kunci lain, atau kosongkan kolom pencarian.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="border-t border-slate-100 dark:border-slate-800">
        <Pagination :paginator="users" />
      </div>
    </Card>
  </div>
</template>
