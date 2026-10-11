<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

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

    <form class="space-y-4" @submit.prevent="submit">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">Nama depan</label>
                <input id="first_name" v-model="form.first_name" type="text" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="form.errors.first_name" class="mt-1 text-sm text-red-600">{{ form.errors.first_name }}</p>
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Nama belakang</label>
                <input id="last_name" v-model="form.last_name" type="text" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="form.errors.last_name" class="mt-1 text-sm text-red-600">{{ form.errors.last_name }}</p>
            </div>
        </div>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input id="username" v-model="form.username" type="text" autocomplete="username" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.username" class="mt-1 text-sm text-red-600">{{ form.errors.username }}</p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" v-model="form.email" type="email" autocomplete="email" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" v-model="form.password" type="password" autocomplete="new-password" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                password</label>
            <input id="password_confirmation" v-model="form.password_confirmation" type="password"
                autocomplete="new-password" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
        </div>

        <button type="submit" :disabled="form.processing"
            class="w-full rounded bg-gray-800 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
            Daftar
        </button>

        <div class="text-center text-sm">
            <Link href="/login" class="text-gray-600 hover:underline">Sudah punya akun? Masuk</Link>
        </div>
    </form>
</template>
