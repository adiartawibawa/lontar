<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

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

    <Head title="Lupa Password" />

    <form class="space-y-4" @submit.prevent="submit">
        <p class="text-sm text-gray-600">
            Masukkan email akun Anda. Kami akan mengirim tautan untuk membuat password baru.
        </p>

        <p v-if="status" class="text-sm text-green-600">{{ status }}</p>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" v-model="form.email" type="email" autocomplete="email" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <button type="submit" :disabled="form.processing"
            class="w-full rounded bg-gray-800 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
            Kirim tautan reset
        </button>

        <div class="text-center text-sm">
            <Link href="/login" class="text-gray-600 hover:underline">Kembali ke halaman masuk</Link>
        </div>
    </form>
</template>
