<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { AtSign, Lock, Mail, UserPlus, UserRound } from '@lucide/vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthHeading from '@/Components/ui/AuthHeading.vue'
import Button from '@/Components/ui/Button.vue'
import TextField from '@/Components/ui/TextField.vue'

defineOptions({ layout: GuestLayout })

// Semua field yang dibutuhkan CreateNewUser
const form = useForm({
  first_name: '',
  last_name: '',
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
})

/**
 * Mengirim data pendaftaran ke POST /register (Fortify).
 * Jika berhasil, pengguna otomatis login dan diarahkan ke halaman verifikasi email.
 */
function submit() {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Daftar" />

  <AuthHeading :icon="UserPlus" title="Buat akun baru" description="Kami akan mengirim tautan aktivasi ke email Anda." />

  <form class="space-y-4" @submit.prevent="submit">
    <div class="grid gap-4 sm:grid-cols-2">
      <TextField v-model="form.first_name" label="Nama depan" :icon="UserRound" :error="form.errors.first_name" autocomplete="given-name" required />
      <TextField v-model="form.last_name" label="Nama belakang" :error="form.errors.last_name" autocomplete="family-name" required />
    </div>

    <TextField
      v-model="form.username"
      label="Username"
      :icon="AtSign"
      :error="form.errors.username"
      hint="Huruf, angka, strip, dan underscore; tanpa karakter @."
      autocomplete="username"
      required
    />

    <TextField v-model="form.email" type="email" label="Email" :icon="Mail" :error="form.errors.email" autocomplete="email" required />

    <TextField
      v-model="form.password"
      type="password"
      label="Password"
      :icon="Lock"
      :error="form.errors.password"
      hint="Minimal 8 karakter."
      autocomplete="new-password"
      required
    />

    <TextField
      v-model="form.password_confirmation"
      type="password"
      label="Ulangi password"
      :icon="Lock"
      autocomplete="new-password"
      required
    />

    <Button type="submit" size="lg" block :icon="UserPlus" :loading="form.processing">Daftar</Button>
  </form>

  <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
    Sudah punya akun?
    <Link href="/login" class="font-semibold text-brand hover:underline">Masuk</Link>
  </p>
</template>
