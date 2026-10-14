<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeft, KeyRound, Mail, Send } from '@lucide/vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Alert from '@/Components/ui/Alert.vue'
import AuthHeading from '@/Components/ui/AuthHeading.vue'
import Button from '@/Components/ui/Button.vue'
import TextField from '@/Components/ui/TextField.vue'

defineOptions({ layout: GuestLayout })

// Pesan sukses dari server ("Kami telah mengirim tautan reset...")
defineProps({ status: String })

// Hanya butuh email, karena tautan reset dikirim lewat email
const form = useForm({ email: '' })

/**
 * Meminta tautan reset ke rute POST /forgot-password milik Fortify.
 */
function submit() {
  form.post('/forgot-password')
}
</script>

<template>
  <Head title="Lupa password" />

  <AuthHeading
    :icon="KeyRound"
    title="Atur ulang password"
    description="Masukkan email akun Anda. Kami akan mengirim tautan untuk membuat password baru."
  />

  <Alert v-if="status" variant="success" class="mb-4">{{ status }}</Alert>

  <form class="space-y-4" @submit.prevent="submit">
    <TextField
      v-model="form.email"
      type="email"
      label="Email"
      :icon="Mail"
      :error="form.errors.email"
      autocomplete="email"
      required
      autofocus
    />

    <Button type="submit" size="lg" block :icon="Send" :loading="form.processing">Kirim tautan reset</Button>
  </form>

  <p class="mt-6 text-center text-sm">
    <Link href="/login" class="inline-flex items-center gap-1.5 font-semibold text-brand hover:underline">
      <ArrowLeft class="h-4 w-4" aria-hidden="true" />
      Kembali ke halaman masuk
    </Link>
  </p>
</template>
