<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ArrowLeft, Save } from '@lucide/vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import UserForm from '@/Components/Admin/UserForm.vue'
import Button from '@/Components/ui/Button.vue'
import PageHeader from '@/Components/ui/PageHeader.vue'

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
  <Head title="Ubah pengguna" />

  <div class="space-y-6">
    <PageHeader title="Ubah pengguna" :description="`Mengubah akun @${user.username}.`">
      <Button variant="secondary" href="/admin/users" :icon="ArrowLeft">Kembali</Button>
    </PageHeader>

    <UserForm :form="form" :roles="roles" submit-label="Simpan perubahan" :submit-icon="Save" @submit="submit" />
  </div>
</template>
