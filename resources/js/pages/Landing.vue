<script setup>
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import Icon from '@/Components/Icon.vue'
import ArticleCard from '@/Components/Landing/ArticleCard.vue'
import NewsletterBox from '@/Components/Landing/NewsletterBox.vue'
import SectionHeading from '@/Components/Landing/SectionHeading.vue'
import SideList from '@/Components/Landing/SideList.vue'
import { sampleFeatured, sampleLatest, samplePosts } from '@/data/sampleLanding'

// Halaman ini tampil di dalam layout publik (navbar + footer)
defineOptions({ layout: PublicLayout })

/*
 * Data bisa dikirim dari controller lewat Inertia::render('Landing', [...]).
 * Selama belum dikirim, dipakai DATA CONTOH dari data/sampleLanding.js.
 */
const props = defineProps({
  posts: { type: Array, default: () => samplePosts },
  featured: { type: Array, default: () => sampleFeatured },
  latest: { type: Array, default: () => sampleLatest },
})

// Kata kunci pencarian dan jumlah artikel yang sedang ditampilkan
const query = ref('')
const shown = ref(6)

// Kata kunci yang sudah dirapikan (tanpa spasi tepi, huruf kecil)
const keyword = computed(() => query.value.trim().toLowerCase())

// Artikel yang cocok dengan kata kunci (mencari di kategori, judul, dan ringkasan)
const filtered = computed(() =>
  props.posts.filter(
    (p) => !keyword.value || `${p.category} ${p.title} ${p.excerpt}`.toLowerCase().includes(keyword.value),
  ),
)

// Saat mencari, tampilkan semua hasil; jika tidak, batasi sesuai tombol "Muat lebih banyak"
const visible = computed(() => (keyword.value ? filtered.value : filtered.value.slice(0, shown.value)))

// Tombol "Muat lebih banyak" hanya tampil bila tidak sedang mencari dan masih ada artikel
const canLoadMore = computed(() => !keyword.value && shown.value < props.posts.length)

/**
 * Menambah tiga artikel pada grid.
 */
function loadMore() {
  shown.value += 3
}
</script>

<template>
  <Head title="Kabar dan cerita perjalanan terbaru" />

  <!-- Hero -->
  <section class="topo">
    <div class="mx-auto max-w-3xl px-4 pb-12 pt-14 text-center sm:px-6 sm:pb-16 sm:pt-20">
      <span class="inline-block rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">Blog</span>
      <h1 class="mt-5 text-balance text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">Cerita perjalanan terbaru dari redaksi kami</h1>
      <p class="mx-auto mt-5 max-w-2xl text-base text-slate-600 dark:text-slate-400 sm:text-lg">Panduan, tips, dan catatan lapangan dari para penulis yang sudah lebih dulu sampai di tempat tujuan.</p>

      <!-- Pencarian langsung: hasil menyesuaikan setiap ketikan -->
      <form class="mx-auto mt-8 flex max-w-xl flex-col gap-3 sm:flex-row" role="search" @submit.prevent>
        <label class="flex h-11 flex-1 items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 text-sm dark:border-slate-700 dark:bg-slate-900">
          <Icon name="search" class="h-4 w-4 shrink-0 text-slate-400" />
          <input v-model="query" class="w-full bg-transparent outline-none placeholder:text-slate-400" placeholder="Cari artikel atau kategori" aria-label="Cari artikel" />
        </label>
        <button type="submit" class="h-11 rounded-xl bg-brand px-6 text-sm font-semibold text-white hover:bg-blue-700">Cari</button>
      </form>
    </div>
  </section>

  <!-- Konten -->
  <main id="artikel" class="mx-auto grid max-w-6xl grid-cols-1 gap-10 px-4 pb-16 sm:px-6 lg:grid-cols-[1fr_300px] lg:gap-8">
    <div>
      <SectionHeading large>Pilihan redaksi</SectionHeading>

      <div class="mt-6 grid grid-cols-1 gap-4 min-[520px]:grid-cols-2 xl:grid-cols-3">
        <ArticleCard v-for="post in visible" :key="post.title" :post="post" />
      </div>

      <p v-if="visible.length === 0" class="mt-10 text-center text-slate-500">Tidak ada artikel yang cocok. Coba kata kunci lain.</p>

      <div v-if="canLoadMore" class="mt-10 text-center">
        <button
          type="button"
          class="inline-flex h-11 items-center gap-2 rounded-xl bg-slate-100 px-6 text-sm font-semibold text-slate-800 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700"
          @click="loadMore"
        >
          <Icon name="refresh" class="h-4 w-4" />Muat lebih banyak
        </button>
      </div>
    </div>

    <aside class="space-y-8">
      <section>
        <SectionHeading>Unggulan</SectionHeading>
        <SideList :items="featured" />
      </section>

      <section>
        <SectionHeading>Terbaru</SectionHeading>
        <SideList :items="latest" />
      </section>

      <NewsletterBox />
    </aside>
  </main>
</template>
