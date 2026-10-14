<script setup>
import { Moon, Sun } from '@lucide/vue'

/**
 * Mengganti tema terang/gelap.
 * Kelas `dark` pada <html> diatur di sini lalu disimpan di localStorage;
 * skrip di resources/views/app.blade.php membacanya kembali saat halaman dimuat.
 */
function toggleTheme() {
  const dark = document.documentElement.classList.toggle('dark')

  try {
    localStorage.setItem('theme', dark ? 'dark' : 'light')
  } catch (e) {
    // Penyimpanan tidak tersedia (mis. mode privat): tema tetap berganti untuk sesi ini
  }
}
</script>

<template>
  <!-- Gaya tambahan (mis. border) dikirim pemanggil lewat atribut class -->
  <button
    type="button"
    class="grid h-10 w-10 place-items-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800"
    aria-label="Ganti tema"
    @click="toggleTheme"
  >
    <!-- Ikon bulan: tampil di tema terang -->
    <Moon class="h-[18px] w-[18px] dark:hidden" aria-hidden="true" />
    <!-- Ikon matahari: tampil di tema gelap -->
    <Sun class="hidden h-[18px] w-[18px] dark:block" aria-hidden="true" />
  </button>
</template>
