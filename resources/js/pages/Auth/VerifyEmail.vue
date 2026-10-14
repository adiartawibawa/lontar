<script setup>
import { computed } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { LogOut, MailCheck, Send } from '@lucide/vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Alert from '@/Components/ui/Alert.vue'
import AuthHeading from '@/Components/ui/AuthHeading.vue'
import Button from '@/Components/ui/Button.vue'

defineOptions({ layout: GuestLayout })

// status bernilai 'verification-link-sent' setelah tautan dikirim ulang
const props = defineProps({ status: String })

// Form kosong, hanya dipakai untuk status pengiriman (processing)
const form = useForm({})

// Tampilkan pesan sukses jika tautan baru baru saja dikirim
const linkSent = computed(() => props.status === 'verification-link-sent')

/**
 * Meminta tautan verifikasi baru lewat POST /email/verification-notification.
 */
function resend() {
  form.post('/email/verification-notification')
}

/**
 * Keluar dari akun (pengguna belum terverifikasi tetap bisa keluar).
 */
function logout() {
  router.post('/logout')
}
</script>

<template>
  <Head title="Verifikasi email" />

  <AuthHeading
    :icon="MailCheck"
    title="Periksa email Anda"
    description="Kami mengirim tautan aktivasi ke email Anda. Klik tautan itu untuk mengaktifkan akun."
  />

  <Alert v-if="linkSent" variant="success" class="mb-4">Tautan aktivasi baru sudah dikirim ke email Anda.</Alert>

  <p class="text-sm text-slate-500 dark:text-slate-400">Belum menerima emailnya? Periksa folder spam, atau minta tautan baru.</p>

  <div class="mt-5 flex items-center justify-between gap-3">
    <Button size="lg" :icon="Send" :loading="form.processing" @click="resend">Kirim ulang tautan</Button>
    <Button variant="ghost" size="lg" :icon="LogOut" @click="logout">Keluar</Button>
  </div>
</template>
