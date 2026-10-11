<script setup>
import { computed } from 'vue'
import { useForm, router, Head } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

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
 * Keluar dari akun (pengguna belum terverifikasi tetap bisa logout).
 */
function logout() {
    router.post('/logout')
}
</script>

<template>

    <Head title="Verifikasi Email" />

    <div class="space-y-4">
        <p class="text-sm text-gray-600">
            Kami telah mengirim tautan verifikasi ke email Anda. Klik tautan tersebut
            untuk mengaktifkan akun. Belum menerima emailnya? Kami dapat mengirim ulang.
        </p>

        <p v-if="linkSent" class="text-sm text-green-600">
            Tautan verifikasi baru telah dikirim ke email Anda.
        </p>

        <div class="flex items-center justify-between">
            <button type="button" :disabled="form.processing"
                class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700 disabled:opacity-50" @click="resend">
                Kirim ulang tautan
            </button>

            <button type="button" class="text-sm text-gray-600 hover:underline" @click="logout">
                Keluar
            </button>
        </div>
    </div>
</template>
