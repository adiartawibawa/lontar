<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

// Halaman ini tampil di dalam layout tamu
defineOptions({ layout: GuestLayout })

// Pesan status dari server (misalnya setelah reset password)
defineProps({ status: String })

// Form reaktif: menyimpan input, error validasi, dan status pengiriman
const form = useForm({
    email: '',
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

    <form class="space-y-4" @submit.prevent="submit">
        <p v-if="status" class="text-sm text-green-600">{{ status }}</p>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" v-model="form.email" type="email" autocomplete="username" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <!-- Error validasi dari server -->
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" v-model="form.password" type="password" autocomplete="current-password" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input v-model="form.remember" type="checkbox" /> Ingat saya
        </label>

        <!-- Dinonaktifkan selama request berjalan agar tidak terkirim ganda -->
        <button type="submit" :disabled="form.processing"
            class="w-full rounded bg-gray-800 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
            Masuk
        </button>
    </form>
</template>
