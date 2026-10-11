<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

// Halaman ini tampil di dalam layout tamu
defineOptions({ layout: GuestLayout })

// Pesan status dari server (misalnya setelah reset password)
defineProps({
    status: String,
    canResetPassword: Boolean,
})

// Form reaktif: menyimpan input, error validasi, dan status pengiriman
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

    <form class="space-y-4" @submit.prevent="submit">
        <p v-if="status" class="text-sm text-green-600">{{ status }}</p>

        <div>
            <label for="login" class="block text-sm font-medium text-gray-700">Email atau username</label>
            <input id="login" v-model="form.login" type="text" autocomplete="username" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <!-- Error dari server (kredensial salah) muncul pada key `login` -->
            <p v-if="form.errors.login" class="mt-1 text-sm text-red-600">{{ form.errors.login }}</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" v-model="form.password" type="password" autocomplete="current-password" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
                <input v-model="form.remember" type="checkbox" /> Ingat saya
            </label>

            <!-- Hanya tampil bila fitur reset password aktif -->
            <Link v-if="canResetPassword" href="/forgot-password" class="text-gray-600 hover:underline">
            Lupa password?
            </Link>
        </div>

        <button type="submit" :disabled="form.processing"
            class="w-full rounded bg-gray-800 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
            Masuk
        </button>
    </form>
</template>
