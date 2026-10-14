<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Lock, LogIn, UserRound } from '@lucide/vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Alert from '@/Components/ui/Alert.vue'
import AuthHeading from '@/Components/ui/AuthHeading.vue'
import Button from '@/Components/ui/Button.vue'
import Checkbox from '@/Components/ui/Checkbox.vue'
import TextField from '@/Components/ui/TextField.vue'

// Halaman ini tampil di dalam layout tamu
defineOptions({ layout: GuestLayout })

// status: pesan dari server; canResetPassword/canRegister: tampilkan tautan atau tidak
defineProps({
  status: String,
  canResetPassword: Boolean,
  canRegister: Boolean,
})

// Form reaktif: `login` dapat berisi email atau username
const form = useForm({
  login: '',
  password: '',
  remember: false,
})

/**
 * Mengirim data login ke rute POST /login milik Fortify.
 * Setelah selesai (berhasil atau gagal), password dikosongkan demi keamanan.
 */
function submit() {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Masuk" />

  <AuthHeading :icon="LogIn" title="Masuk ke akun Anda" description="Gunakan email atau username yang terdaftar." />

  <!-- Pesan dari server, mis. password berhasil direset atau akun dinonaktifkan -->
  <Alert v-if="status" class="mb-4">{{ status }}</Alert>

  <form class="space-y-4" @submit.prevent="submit">
    <TextField
      v-model="form.login"
      label="Email atau username"
      :icon="UserRound"
      :error="form.errors.login"
      autocomplete="username"
      required
      autofocus
    />

    <TextField
      v-model="form.password"
      type="password"
      label="Password"
      :icon="Lock"
      :error="form.errors.password"
      autocomplete="current-password"
      required
    />

    <div class="flex items-center justify-between">
      <Checkbox v-model="form.remember">Ingat saya</Checkbox>

      <!-- Hanya tampil bila fitur reset password aktif -->
      <Link v-if="canResetPassword" href="/forgot-password" class="text-sm font-semibold text-brand hover:underline">
        Lupa password?
      </Link>
    </div>

    <Button type="submit" size="lg" block :icon="LogIn" :loading="form.processing">Masuk</Button>
  </form>

  <!-- Tampil hanya jika fitur registrasi aktif di config Fortify -->
  <p v-if="canRegister" class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
    Belum punya akun?
    <Link href="/register" class="font-semibold text-brand hover:underline">Daftar</Link>
  </p>
</template>
