<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import UserForm from '@/Components/Admin/UserForm.vue'

defineOptions({ layout: DashboardLayout })

// Daftar peran dari server
defineProps({ roles: Array })

// Form pengguna baru; peran awal `user`
const form = useForm({
    first_name: '',
    last_name: '',
    username: '',
    email: '',
    role: 'user',
    password: '',
    password_confirmation: '',
})

/**
 * Mengirim pengguna baru ke POST /admin/users.
 */
function submit() {
    form.post('/admin/users', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>

    <Head title="Tambah Pengguna" />

    <h2 class="mb-4 text-xl font-semibold text-gray-800">Tambah pengguna</h2>

    <UserForm :form="form" :roles="roles" password-required submit-label="Buat pengguna" @submit="submit" />
</template>
