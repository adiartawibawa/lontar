<script setup>
import { computed } from 'vue'
import { AtSign, Lock, Mail, UserRound } from '@lucide/vue'
import Button from '@/Components/ui/Button.vue'
import Card from '@/Components/ui/Card.vue'
import SelectField from '@/Components/ui/SelectField.vue'
import TextField from '@/Components/ui/TextField.vue'
import { roleLabel } from '@/lib/roles'

/*
 * Formulir pengguna yang dipakai bersama oleh halaman tambah dan ubah.
 * form: objek useForm milik halaman induk (field dan error ada di sini).
 * Catatan: v-model di bawah mengubah properti objek `form`; ini disengaja
 * karena objek tersebut memang dimiliki dan dikendalikan halaman induk.
 */
const props = defineProps({
  form: { type: Object, required: true },
  roles: { type: Array, required: true }, // daftar nama peran dari server
  passwordRequired: { type: Boolean, default: false }, // wajib saat tambah, opsional saat ubah
  submitLabel: { type: String, default: 'Simpan' },
  submitIcon: { type: [Object, Function], default: null },
})

// Halaman induk menangani pengiriman data
defineEmits(['submit'])

// Pilihan peran dengan label bahasa Indonesia
const roleOptions = computed(() => props.roles.map((name) => ({ value: name, label: roleLabel(name) })))
</script>

<template>
  <form class="max-w-2xl" @submit.prevent="$emit('submit')">
    <Card>
      <div class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2">
          <TextField v-model="form.first_name" label="Nama depan" :icon="UserRound" :error="form.errors.first_name" required />
          <TextField v-model="form.last_name" label="Nama belakang" :error="form.errors.last_name" required />
        </div>

        <TextField
          v-model="form.username"
          label="Username"
          :icon="AtSign"
          :error="form.errors.username"
          hint="Huruf, angka, strip, dan underscore; tanpa karakter @."
          required
        />

        <TextField v-model="form.email" type="email" label="Email" :icon="Mail" :error="form.errors.email" required />

        <SelectField v-model="form.role" label="Peran" :options="roleOptions" :error="form.errors.role" required />

        <div class="grid gap-4 sm:grid-cols-2">
          <TextField
            v-model="form.password"
            type="password"
            :label="passwordRequired ? 'Password' : 'Password baru'"
            :icon="Lock"
            :error="form.errors.password"
            :hint="passwordRequired ? 'Minimal 8 karakter.' : 'Kosongkan jika tidak ingin mengganti.'"
            autocomplete="new-password"
            :required="passwordRequired"
          />
          <TextField
            v-model="form.password_confirmation"
            type="password"
            label="Ulangi password"
            :icon="Lock"
            autocomplete="new-password"
            :required="passwordRequired"
          />
        </div>
      </div>

      <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
        <Button type="submit" size="lg" :icon="submitIcon" :loading="form.processing">{{ submitLabel }}</Button>
        <Button variant="secondary" size="lg" href="/admin/users">Batal</Button>
      </div>
    </Card>
  </form>
</template>
