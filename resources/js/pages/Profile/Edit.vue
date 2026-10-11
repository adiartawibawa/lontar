<script setup>
import { computed } from 'vue'
import { useForm, usePage, Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })

// Data pengguna yang sedang login (dibagikan oleh HandleInertiaRequests)
const page = usePage()
const user = computed(() => page.props.auth.user)

// Form informasi profil, diisi dengan data pengguna saat ini
const profileForm = useForm({
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    username: user.value.username,
    email: user.value.email,
})

/**
 * Mengirim perubahan profil ke PUT /user/profile-information (Fortify).
 * errorBag harus sama dengan nama bag di UpdateUserProfileInformation.
 */
function updateProfile() {
    profileForm.put('/user/profile-information', {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    })
}

// Form ganti password
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

/**
 * Mengirim password baru ke PUT /user/password (Fortify).
 * Jika berhasil, seluruh isian dikosongkan.
 */
function updatePassword() {
    passwordForm.put('/user/password', {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    })
}
</script>

<template>

    <Head title="Profil" />

    <div class="max-w-xl space-y-8">
        <!-- Bagian informasi profil -->
        <form class="space-y-4 rounded-lg bg-white p-6 shadow" @submit.prevent="updateProfile">
            <h2 class="text-lg font-semibold text-gray-800">Informasi profil</h2>

            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">Nama depan</label>
                <input id="first_name" v-model="profileForm.first_name" type="text" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="profileForm.errors.first_name" class="mt-1 text-sm text-red-600">{{
                    profileForm.errors.first_name }}</p>
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Nama belakang</label>
                <input id="last_name" v-model="profileForm.last_name" type="text" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="profileForm.errors.last_name" class="mt-1 text-sm text-red-600">{{ profileForm.errors.last_name
                }}</p>
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" v-model="profileForm.username" type="text" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="profileForm.errors.username" class="mt-1 text-sm text-red-600">{{ profileForm.errors.username
                }}</p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" v-model="profileForm.email" type="email" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">{{ profileForm.errors.email }}</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="profileForm.processing"
                    class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
                    Simpan
                </button>
                <!-- recentlySuccessful bernilai true beberapa detik setelah berhasil -->
                <span v-if="profileForm.recentlySuccessful" class="text-sm text-green-600">Tersimpan.</span>
            </div>
        </form>

        <!-- Bagian ganti password -->
        <form class="space-y-4 rounded-lg bg-white p-6 shadow" @submit.prevent="updatePassword">
            <h2 class="text-lg font-semibold text-gray-800">Ganti password</h2>

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Password saat ini</label>
                <input id="current_password" v-model="passwordForm.current_password" type="password"
                    autocomplete="current-password" required
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">{{
                    passwordForm.errors.current_password }}</p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password baru</label>
                <input id="password" v-model="passwordForm.password" type="password" autocomplete="new-password"
                    required class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
                <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.password
                }}</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi password
                    baru</label>
                <input id="password_confirmation" v-model="passwordForm.password_confirmation" type="password"
                    autocomplete="new-password" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="passwordForm.processing"
                    class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
                    Ubah password
                </button>
                <span v-if="passwordForm.recentlySuccessful" class="text-sm text-green-600">Password diubah.</span>
            </div>
        </form>
    </div>
</template>
