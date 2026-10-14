<script setup>
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from '@lucide/vue'
import BrandLogo from '@/Components/BrandLogo.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue'

// Layout halaman tamu (masuk, daftar, reset password, verifikasi email).
// Memakai latar garis kontur yang sama dengan hero landing page.
</script>

<template>
  <div class="topo flex min-h-screen flex-col bg-[#f3f5f9] dark:bg-[#080c16]">
    <header class="flex items-center justify-between px-4 py-4 sm:px-8">
      <Link href="/" aria-label="Ke beranda"><BrandLogo /></Link>
      <ThemeToggle />
    </header>

    <main class="flex flex-1 items-center justify-center px-4 pb-10 pt-2">
      <!-- Isi halaman (formulir) ditampilkan di dalam kartu -->
      <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900 sm:p-8">
        <slot />
      </div>
    </main>

    <footer class="pb-6 text-center text-sm">
      <Link href="/" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-brand dark:text-slate-400">
        <ArrowLeft class="h-4 w-4" aria-hidden="true" />
        Kembali ke beranda
      </Link>
    </footer>
  </div>
</template>
