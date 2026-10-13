<script setup>
// Tabel "Konten terpopuler". rows: [{ title, author, reads, comments, status }]
defineProps({
  rows: { type: Array, required: true },
})

// Warna lencana per status; kunci harus sama dengan nilai `status` pada data
const statusClass = {
  Terbit: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
  Dijadwalkan: 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
  Draf: 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
}
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:p-5">
    <div class="flex items-center justify-between">
      <h2 class="font-bold text-slate-900 dark:text-white">Konten terpopuler</h2>
      <!-- Tujuan diisi saat halaman daftar artikel tersedia -->
      <a href="#" class="text-sm font-semibold text-brand hover:underline">Lihat semua</a>
    </div>

    <!-- Tabel bisa digeser ke samping di layar sempit -->
    <div class="-mx-4 mt-3 overflow-x-auto sm:mx-0">
      <table class="w-full min-w-[520px] text-sm">
        <thead class="border-b border-slate-100 text-left text-xs text-slate-400 dark:border-slate-800">
          <tr>
            <th scope="col" class="py-2 pl-4 font-medium sm:pl-0">Judul</th>
            <th scope="col" class="hidden font-medium md:table-cell">Penulis</th>
            <th scope="col" class="text-right font-medium">Dibaca</th>
            <th scope="col" class="text-right font-medium">Komentar</th>
            <th scope="col" class="pr-4 text-right font-medium sm:pr-0">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          <tr v-for="row in rows" :key="row.title">
            <td class="max-w-[260px] py-3 pl-4 pr-3 sm:pl-0">
              <span class="block truncate font-medium text-slate-900 dark:text-slate-100">{{ row.title }}</span>
            </td>
            <td class="hidden text-slate-500 md:table-cell">{{ row.author }}</td>
            <td class="text-right tabular-nums">{{ row.reads }}</td>
            <td class="text-right tabular-nums">{{ row.comments }}</td>
            <td class="pr-4 text-right sm:pr-0">
              <span class="rounded-md px-2 py-0.5 text-xs font-semibold" :class="statusClass[row.status] ?? statusClass.Draf">{{ row.status }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
