<script setup>
// Kartu "Asisten penulis": hanya tampilan. Kolom input belum terhubung ke fitur apa pun.
</script>

<template>
  <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900 sm:p-5">
    <h2 class="font-bold text-slate-900 dark:text-white">Asisten penulis</h2>

    <!-- Bola bergradasi sebagai penanda visual -->
    <div class="grid flex-1 place-items-center py-5">
      <div class="h-20 w-20 rounded-full bg-[radial-gradient(circle_at_30%_30%,#93c5fd,#2563eb_55%,#1e3a8a)] shadow-[0_12px_32px_-6px_rgba(37,99,235,.6)]"></div>
    </div>

    <label class="flex h-11 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-1.5 text-sm dark:border-slate-700 dark:bg-slate-800/60">
      <input class="min-w-0 flex-1 bg-transparent outline-none placeholder:text-slate-400" placeholder="Minta ide judul atau ringkasan…" />
      <button type="button" class="grid h-8 w-8 place-items-center rounded-lg bg-brand text-white" aria-label="Kirim">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7" /></svg>
      </button>
    </label>
  </div>
</template>
