<script setup>
import { computed, ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { LayoutDashboard, LogIn, Menu, UserPlus, X } from '@lucide/vue'
import BrandLogo from '@/Components/BrandLogo.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue'

/*
 * Layout halaman publik: navbar di atas, isi halaman di tengah, footer di bawah.
 * Dipakai oleh Landing page dan halaman publik lain (blog, tentang, dll.).
 */
const page = usePage()

// Pengguna yang sedang login (null untuk tamu) dan status fitur registrasi
const user = computed(() => page.props.auth?.user ?? null)
const canRegister = computed(() => page.props.canRegister ?? false)
const appName = computed(() => page.props.appName ?? 'Lontar')
const year = new Date().getFullYear()

// Status menu di layar kecil; otomatis menutup setelah pindah halaman
const menuOpen = ref(false)
watch(() => page.url, () => (menuOpen.value = false))

/*
 * Tautan navbar. `inertia: true` memakai navigasi Inertia (tanpa muat ulang);
 * lainnya berupa anchor biasa. Tautan dengan href '#' adalah penampung
 * sampai halamannya dibuat.
 */
const links = [
  { label: 'Beranda', href: '/', inertia: true },
  { label: 'Blog', href: '/#artikel' },
  { label: 'Layanan', href: '#' },
  { label: 'Tentang', href: '#' },
  { label: 'Kontak', href: '/#kontak' },
]

// Tautan "Beranda" ditandai aktif saat berada di halaman depan
const isCurrent = (link) => link.href === '/' && page.url.split('?')[0] === '/'

// Tautan footer (penampung sampai halamannya tersedia)
const companyLinks = ['Syarat & ketentuan', 'Kebijakan privasi', 'Perlindungan proyek', 'Tanya jawab']
const helpLinks = ['Mulai menulis', 'Kirim masukan', 'Program referal', 'Status layanan']
const socials = [
  { label: 'Facebook', text: 'f' },
  { label: 'X', text: 'X' },
  { label: 'LinkedIn', text: 'in' },
  { label: 'WhatsApp', text: 'WA' },
]
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-[#080c16]">
    <!-- Navbar -->
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/85 backdrop-blur dark:border-slate-800 dark:bg-slate-950/85">
      <div class="mx-auto flex h-16 max-w-6xl items-center gap-6 px-4 sm:px-6">
        <Link href="/"><BrandLogo /></Link>

        <nav class="hidden items-center gap-6 text-sm font-semibold md:flex" aria-label="Menu utama">
          <component
            :is="link.inertia ? Link : 'a'"
            v-for="link in links"
            :key="link.label"
            :href="link.href"
            :class="isCurrent(link) ? 'text-brand' : 'hover:text-brand'"
            :aria-current="isCurrent(link) ? 'page' : undefined"
          >{{ link.label }}</component>
        </nav>

        <div class="ml-auto flex items-center gap-2">
          <ThemeToggle />

          <!-- Tamu: tombol Masuk dan Daftar. Pengguna login: tombol ke Dashboard -->
          <template v-if="!user">
            <Link href="/login" class="hidden h-9 items-center gap-2 rounded-lg bg-blue-50 px-4 text-sm font-semibold text-brand dark:bg-blue-500/15 dark:text-blue-400 sm:inline-flex">
              <LogIn class="h-4 w-4" aria-hidden="true" />Masuk
            </Link>
            <Link v-if="canRegister" href="/register" class="hidden h-9 items-center gap-2 rounded-lg bg-brand px-4 text-sm font-semibold text-white hover:bg-blue-700 sm:inline-flex">
              <UserPlus class="h-4 w-4" aria-hidden="true" />Daftar
            </Link>
          </template>
          <Link v-else href="/dashboard" class="hidden h-9 items-center gap-2 rounded-lg bg-brand px-4 text-sm font-semibold text-white hover:bg-blue-700 sm:inline-flex">
            <LayoutDashboard class="h-4 w-4" aria-hidden="true" />Dashboard
          </Link>

          <button
            type="button"
            class="grid h-10 w-10 place-items-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 md:hidden"
            :aria-label="menuOpen ? 'Tutup menu' : 'Buka menu'"
            :aria-expanded="menuOpen"
            @click="menuOpen = !menuOpen"
          >
            <component :is="menuOpen ? X : Menu" class="h-5 w-5" aria-hidden="true" />
          </button>
        </div>
      </div>

      <!-- Menu layar kecil -->
      <div v-show="menuOpen" class="space-y-1 border-t border-slate-200 px-4 py-3 text-sm font-semibold dark:border-slate-800 md:hidden">
        <component
          :is="link.inertia ? Link : 'a'"
          v-for="link in links"
          :key="link.label"
          :href="link.href"
          class="block rounded-lg px-3 py-2"
          :class="isCurrent(link) ? 'bg-blue-50 text-brand dark:bg-blue-500/10' : ''"
        >{{ link.label }}</component>

        <div class="flex gap-2 pt-2">
          <template v-if="!user">
            <Link href="/login" class="flex-1 rounded-lg bg-blue-50 py-2 text-center text-brand dark:bg-blue-500/15">Masuk</Link>
            <Link v-if="canRegister" href="/register" class="flex-1 rounded-lg bg-brand py-2 text-center text-white">Daftar</Link>
          </template>
          <Link v-else href="/dashboard" class="flex-1 rounded-lg bg-brand py-2 text-center text-white">Dashboard</Link>
        </div>
      </div>
    </header>

    <!-- Isi halaman -->
    <slot />

    <!-- Footer -->
    <footer id="kontak" class="border-t border-slate-200 dark:border-slate-800">
      <div class="mx-auto grid max-w-6xl grid-cols-2 gap-8 px-4 py-12 text-sm sm:px-6 md:grid-cols-4">
        <div class="col-span-2 md:col-span-1">
          <Link href="/"><BrandLogo /></Link>
          <p class="mt-3 max-w-xs text-slate-500 dark:text-slate-400">Platform konten yang membantu tim redaksi menerbitkan cerita dengan lebih cepat dan rapi.</p>
        </div>

        <div>
          <h3 class="font-bold text-slate-900 dark:text-white">Perusahaan</h3>
          <ul class="mt-3 space-y-2 text-slate-500 dark:text-slate-400">
            <li v-for="label in companyLinks" :key="label"><a href="#" class="hover:text-brand">{{ label }}</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-bold text-slate-900 dark:text-white">Bantuan</h3>
          <ul class="mt-3 space-y-2 text-slate-500 dark:text-slate-400">
            <li v-for="label in helpLinks" :key="label"><a href="#" class="hover:text-brand">{{ label }}</a></li>
          </ul>
        </div>

        <div class="col-span-2 md:col-span-1">
          <h3 class="font-bold text-slate-900 dark:text-white">Surel</h3>
          <!-- Formulir kontak: hanya tampilan, belum mengirim apa pun -->
          <form class="mt-3 flex gap-2" @submit.prevent>
            <input type="email" class="field-ring h-10 min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-3 outline-none placeholder:text-slate-400 focus:border-brand focus:ring-2 focus:ring-blue-500/25 dark:border-slate-700 dark:bg-slate-950" placeholder="nama@email.com" aria-label="Surel" />
            <button type="submit" class="h-10 rounded-lg bg-brand px-4 font-semibold text-white">Kirim</button>
          </form>
          <div class="mt-4 flex gap-2 text-xs font-bold">
            <a
              v-for="s in socials"
              :key="s.label"
              href="#"
              :aria-label="s.label"
              class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 dark:bg-slate-800"
            >{{ s.text }}</a>
          </div>
        </div>
      </div>

      <p class="border-t border-slate-200 py-6 text-center text-xs text-slate-500 dark:border-slate-800">© {{ year }} {{ appName }}. Hak cipta dilindungi.</p>
    </footer>
  </div>
</template>
