<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

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

    <Head title="Reset Password" />

    <form class="space-y-4" @submit.prevent="submit">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <!-- Email dikunci karena sudah ditentukan oleh tautan reset -->
            <input id="email" v-model="form.email" type="email" readonly
                class="mt-1 w-full rounded border border-gray-300 bg-gray-50 px-3 py-2" />
            <!-- Error token kedaluwarsa atau tidak valid juga muncul pada key `email` -->
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password baru</label>
            <input id="password" v-model="form.password" type="password" autocomplete="new-password" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                Konfirmasi password baru
            </label>
            <input id="password_confirmation" v-model="form.password_confirmation" type="password"
                autocomplete="new-password" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
        </div>

        <button type="submit" :disabled="form.processing"
            class="w-full rounded bg-gray-800 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
            Simpan password baru
        </button>
    </form>
</template>
