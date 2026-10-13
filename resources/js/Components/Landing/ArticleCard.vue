<script setup>
import { computed } from 'vue'

/*
 * Kartu artikel pada grid "Pilihan redaksi".
 * post: { category, title, excerpt, href, image?, gradient? }
 * Sampul memakai `image` (URL) bila ada, jika tidak memakai `gradient` sementara.
 */
const props = defineProps({
  post: { type: Object, required: true },
})

// Gaya latar sampul: gambar penuh atau gradien pengganti
const coverStyle = computed(() =>
  props.post.image
    ? { backgroundImage: `url(${props.post.image})`, backgroundSize: 'cover', backgroundPosition: 'center' }
    : { background: props.post.gradient },
)
</script>

<template>
  <a
    :href="post.href ?? '#'"
    class="group relative isolate block aspect-[3/4] overflow-hidden rounded-2xl sm:aspect-[3/4.2]"
  >
    <!-- Sampul; membesar sedikit saat disorot (dimatikan bila pengguna mengurangi animasi) -->
    <div
      class="absolute inset-0 transition-transform duration-500 ease-[cubic-bezier(.2,.8,.2,1)] group-hover:scale-[1.04] motion-reduce:transition-none motion-reduce:group-hover:scale-100"
      :style="coverStyle"
    ></div>

    <!-- Lapisan gelap di bawah agar teks putih terbaca -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent"></div>

    <div class="absolute inset-x-0 bottom-0 p-4 text-white">
      <span class="inline-block rounded-md bg-white px-2 py-0.5 text-[11px] font-semibold text-slate-900">{{ post.category }}</span>
      <h3 class="mt-2 text-lg font-bold leading-snug">{{ post.title }}</h3>
      <p class="mt-1.5 line-clamp-3 text-[13px] text-white/85">{{ post.excerpt }}</p>
    </div>
  </a>
</template>
