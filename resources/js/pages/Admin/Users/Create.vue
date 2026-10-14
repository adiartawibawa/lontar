<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ArrowLeft, UserPlus } from '@lucide/vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import UserForm from '@/Components/Admin/UserForm.vue'
import Button from '@/Components/ui/Button.vue'
import PageHeader from '@/Components/ui/PageHeader.vue'

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
  <Head title="Tambah pengguna" />

  <div class="space-y-6">
    <PageHeader title="Tambah pengguna" description="Buat akun baru. Emailnya langsung dianggap terverifikasi.">
      <Button variant="secondary" href="/admin/users" :icon="ArrowLeft">Kembali</Button>
    </PageHeader>

    <UserForm :form="form" :roles="roles" password-required submit-label="Buat pengguna" :submit-icon="UserPlus" @submit="submit" />
  </div>
</template>
