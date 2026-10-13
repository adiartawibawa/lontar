<script setup>

/*
 * Daftar artikel ringkas di sidebar (Unggulan, Terbaru).
 * items: [{ date, title, href, image?, gradient? }]
 */
defineProps({
  items: { type: Array, required: true },
})

// Gaya thumbnail: gambar bila ada, jika tidak gradien pengganti
const thumbStyle = (item) =>
  item.image
    ? { backgroundImage: `url(${item.image})`, backgroundSize: 'cover', backgroundPosition: 'center' }
    : { background: item.gradient }
</script>

<template>
  <div class="mt-4 space-y-4">
    <a v-for="item in items" :key="item.title" :href="item.href ?? '#'" class="group flex items-center gap-4">
      <span class="h-20 w-20 shrink-0 rounded-2xl" :style="thumbStyle(item)"></span>
      <span>
        <span class="block text-xs text-slate-400">{{ item.date }}</span>
        <span class="mt-1 block text-sm font-bold leading-snug text-slate-900 group-hover:text-brand dark:text-slate-100">{{ item.title }}</span>
      </span>
    </a>
  </div>
</template>
