<script setup>
import { ref } from 'vue'

// Kotak berlangganan surel. Saat ini HANYA tampilan: data belum dikirim ke server.
const email = ref('')
const agreed = ref(false)
const sent = ref(false)

/**
 * Menangani pengiriman formulir. Menampilkan pesan terima kasih lalu mengosongkan isian.
 * TODO: kirim `email` ke endpoint penyimpanan pelanggan saat fiturnya dibuat.
 */
function submit() {
  sent.value = true
  email.value = ''
  agreed.value = false
}
</script>

<template>
  <section class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900">
    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Satu surel seminggu, tanpa spam</h2>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Dapatkan artikel terbaru langsung di kotak masuk Anda.</p>

    <form class="mt-4 space-y-3" @submit.prevent="submit">
      <input
        v-model="email"
        type="email"
        required
        class="h-10 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm outline-none placeholder:text-slate-400 dark:border-slate-700 dark:bg-slate-950"
        placeholder="nama@email.com"
        aria-label="Alamat surel"
      />
      <button type="submit" class="h-10 w-full rounded-lg bg-brand text-sm font-semibold text-white hover:bg-blue-700">Berlangganan</button>
      <label class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-400">
        <input v-model="agreed" type="checkbox" required class="mt-0.5 accent-blue-600" />
        Saya menyetujui kebijakan privasi
      </label>
      <p v-if="sent" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400" role="status">Terima kasih, Anda sudah terdaftar.</p>
    </form>
  </section>
</template>
