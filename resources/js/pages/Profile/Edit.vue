<script setup>
import { computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { AtSign, Check, KeyRound, Lock, Mail, Save, UserRound } from '@lucide/vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import PageHeader from '@/Components/ui/PageHeader.vue'
import TextField from '@/Components/ui/TextField.vue'

defineOptions({ layout: DashboardLayout })

// Data pengguna yang sedang login (dibagikan oleh HandleInertiaRequests)
const page = usePage()
const user = computed(() => page.props.auth.user)

// Form informasi profil, diisi dengan data pengguna saat ini
const profileForm = useForm({
  first_name: user.value.first_name,
  last_name: user.value.last_name,
  username: user.value.username,
  email: user.value.email,
})

/**
 * Mengirim perubahan profil ke PUT /user/profile-information (Fortify).
 * errorBag harus sama dengan nama bag di UpdateUserProfileInformation.
 */
function updateProfile() {
  profileForm.put('/user/profile-information', {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
  })
}

// Form ganti password
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

/**
 * Mengirim password baru ke PUT /user/password (Fortify).
 * Jika berhasil, seluruh isian dikosongkan.
 */
function updatePassword() {
  passwordForm.put('/user/password', {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
  })
}
</script>

<template>
  <Head title="Profil" />

  <div class="max-w-3xl space-y-6">
    <PageHeader title="Profil" description="Perbarui data akun dan password Anda." />

    <Card title="Informasi profil" description="Nama dan alamat email Anda." :icon="UserRound">
      <form class="space-y-4" @submit.prevent="updateProfile">
        <div class="grid gap-4 sm:grid-cols-2">
          <TextField v-model="profileForm.first_name" label="Nama depan" :error="profileForm.errors.first_name" autocomplete="given-name" required />
          <TextField v-model="profileForm.last_name" label="Nama belakang" :error="profileForm.errors.last_name" autocomplete="family-name" required />
        </div>

        <TextField v-model="profileForm.username" label="Username" :icon="AtSign" :error="profileForm.errors.username" autocomplete="username" required />

        <TextField
          v-model="profileForm.email"
          type="email"
          label="Email"
          :icon="Mail"
          :error="profileForm.errors.email"
          hint="Jika email diganti, Anda perlu memverifikasi alamat yang baru."
          autocomplete="email"
          required
        />

        <div class="flex items-center gap-4">
          <Button type="submit" size="lg" :icon="Save" :loading="profileForm.processing">Simpan perubahan</Button>

          <!-- recentlySuccessful bernilai true beberapa detik setelah berhasil -->
          <span v-if="profileForm.recentlySuccessful" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400" role="status">
            <Check class="h-4 w-4" aria-hidden="true" />Tersimpan
          </span>
        </div>
      </form>
    </Card>

    <Card title="Ganti password" description="Gunakan password yang kuat dan belum dipakai di tempat lain." :icon="KeyRound">
      <form class="space-y-4" @submit.prevent="updatePassword">
        <TextField
          v-model="passwordForm.current_password"
          type="password"
          label="Password saat ini"
          :icon="Lock"
          :error="passwordForm.errors.current_password"
          autocomplete="current-password"
          required
        />

        <div class="grid gap-4 sm:grid-cols-2">
          <TextField
            v-model="passwordForm.password"
            type="password"
            label="Password baru"
            :icon="Lock"
            :error="passwordForm.errors.password"
            hint="Minimal 8 karakter."
            autocomplete="new-password"
            required
          />
          <TextField
            v-model="passwordForm.password_confirmation"
            type="password"
            label="Ulangi password baru"
            :icon="Lock"
            autocomplete="new-password"
            required
          />
        </div>

        <div class="flex items-center gap-4">
          <Button type="submit" size="lg" :icon="KeyRound" :loading="passwordForm.processing">Ubah password</Button>

          <span v-if="passwordForm.recentlySuccessful" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400" role="status">
            <Check class="h-4 w-4" aria-hidden="true" />Password diubah
          </span>
        </div>
      </form>
    </Card>
  </div>
</template>
