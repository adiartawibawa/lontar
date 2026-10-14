<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { Lock, Mail, ShieldCheck } from '@lucide/vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthHeading from '@/Components/ui/AuthHeading.vue'
import Button from '@/Components/ui/Button.vue'
import TextField from '@/Components/ui/TextField.vue'

defineOptions({ layout: GuestLayout })

// token dan email berasal dari tautan di email reset
const props = defineProps({
  token: String,
  email: String,
})

// Semua field yang dibutuhkan endpoint POST /reset-password milik Fortify
const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

/**
 * Mengirim password baru. Field password dikosongkan setelah selesai.
 * Jika berhasil, Fortify mengarahkan pengguna ke halaman login.
 */
function submit() {
  form.post('/reset-password', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Buat password baru" />

  <AuthHeading :icon="ShieldCheck" title="Buat password baru" description="Pilih password baru yang belum pernah Anda pakai di tempat lain." />

  <form class="space-y-4" @submit.prevent="submit">
    <!-- Email dikunci karena sudah ditentukan oleh tautan reset; token kedaluwarsa juga dilaporkan di sini -->
    <TextField v-model="form.email" type="email" label="Email" :icon="Mail" :error="form.errors.email" readonly />

    <TextField
      v-model="form.password"
      type="password"
      label="Password baru"
      :icon="Lock"
      :error="form.errors.password"
      hint="Minimal 8 karakter."
      autocomplete="new-password"
      required
      autofocus
    />

    <TextField
      v-model="form.password_confirmation"
      type="password"
      label="Ulangi password baru"
      :icon="Lock"
      autocomplete="new-password"
      required
    />

    <Button type="submit" size="lg" block :icon="ShieldCheck" :loading="form.processing">Simpan password baru</Button>
  </form>
</template>
