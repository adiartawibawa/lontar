<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import UserForm from '@/Components/Admin/UserForm.vue'

defineOptions({ layout: DashboardLayout })

// user: data pengguna yang diubah; roles: daftar peran
const props = defineProps({
    user: Object,
    roles: Array,
})

// Form diisi data pengguna saat ini; password dikosongkan (opsional)
const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    username: props.user.username,
    email: props.user.email,
    role: props.user.role,
    password: '',
    password_confirmation: '',
})

/**
 * Mengirim perubahan ke PUT /admin/users/{id}.
 */
function submit() {
    form.put(`/admin/users/${props.user.id}`, {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>

    <Head title="Ubah Pengguna" />

    <h2 class="mb-4 text-xl font-semibold text-gray-800">Ubah pengguna: {{ user.username }}</h2>

    <UserForm :form="form" :roles="roles" submit-label="Simpan perubahan" @submit="submit" />
</template>
