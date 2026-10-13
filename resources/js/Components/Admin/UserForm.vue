<script setup>
import { Link } from '@inertiajs/vue3'

/*
 * form: objek useForm milik halaman induk (field dan error ada di sini).
 * Catatan: v-model di bawah mengubah properti objek `form`; ini disengaja
 * karena objek tersebut memang dimiliki dan dikendalikan halaman induk.
 */
defineProps({
    form: { type: Object, required: true },
    roles: { type: Array, required: true }, // daftar nama peran
    passwordRequired: { type: Boolean, default: false }, // wajib saat tambah, opsional saat ubah
    submitLabel: { type: String, default: 'Simpan' },
})

// Halaman induk menangani pengiriman data
defineEmits(['submit'])
</script>

<template>
    <form class="max-w-xl space-y-4 rounded-lg bg-white p-6 shadow" @submit.prevent="$emit('submit')">
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
            <input id="username" v-model="form.username" type="text" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.username" class="mt-1 text-sm text-red-600">{{ form.errors.username }}</p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" v-model="form.email" type="email" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700">Peran</label>
            <select id="role" v-model="form.role" required
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2 capitalize">
                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
            </select>
            <p v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password {{ passwordRequired ? '' : '(kosongkan jika tidak diganti)' }}
            </label>
            <input id="password" v-model="form.password" type="password" autocomplete="new-password"
                :required="passwordRequired" class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                password</label>
            <input id="password_confirmation" v-model="form.password_confirmation" type="password"
                autocomplete="new-password" :required="passwordRequired"
                class="mt-1 w-full rounded border border-gray-300 px-3 py-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" :disabled="form.processing"
                class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-700 disabled:opacity-50">
                {{ submitLabel }}
            </button>
            <Link href="/admin/users" class="text-sm text-gray-600 hover:underline">Batal</Link>
        </div>
    </form>
</template>
