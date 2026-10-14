<script setup>
import { computed, ref, useAttrs, useId } from 'vue'
import { CircleAlert, Eye, EyeOff } from '@lucide/vue'

/*
 * Kolom isian teks lengkap: label, ikon, pesan bantuan, dan pesan error.
 * Mendukung v-model. Untuk type="password" tersedia tombol tampilkan/sembunyikan.
 * Atribut class/style diterapkan ke pembungkus; atribut lain (required,
 * autocomplete, placeholder, readonly, dst.) diteruskan ke <input>.
 */
defineOptions({ inheritAttrs: false })

const model = defineModel({ type: String, default: '' })

const props = defineProps({
  label: { type: String, required: true },
  type: { type: String, default: 'text' },
  icon: { type: [Object, Function], default: null }, // komponen ikon Lucide
  error: { type: String, default: '' },
  hint: { type: String, default: '' },
  hideLabel: { type: Boolean, default: false }, // label hanya untuk pembaca layar
})

const attrs = useAttrs()
const id = useId()

// Atribut untuk <input> (tanpa class dan style yang milik pembungkus)
const inputAttrs = computed(() => {
  const { class: _class, style: _style, ...rest } = attrs

  return rest
})

// Tampilkan isi password atau tidak
const revealed = ref(false)
const isPassword = computed(() => props.type === 'password')
const inputType = computed(() => (isPassword.value && revealed.value ? 'text' : props.type))

// Menghubungkan input dengan pesan error/bantuan untuk pembaca layar
const describedBy = computed(() => {
  if (props.error) return `${id}-error`

  return props.hint ? `${id}-hint` : undefined
})
</script>

<template>
  <div :class="attrs.class" :style="attrs.style">
    <label
      :for="id"
      class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
      :class="{ 'sr-only': hideLabel }"
    >{{ label }}</label>

    <!-- Kotak isian: cincin fokus muncul saat input di dalamnya aktif -->
    <div
      class="flex h-11 items-center gap-2.5 rounded-xl border bg-white px-3.5 text-sm transition-colors focus-within:ring-2 has-[input:read-only]:bg-slate-50 dark:bg-slate-900 dark:has-[input:read-only]:bg-slate-800/60"
      :class="error
        ? 'border-rose-400 focus-within:ring-rose-500/25 dark:border-rose-500/70'
        : 'border-slate-300 focus-within:border-brand focus-within:ring-blue-500/25 dark:border-slate-700'"
    >
      <component :is="icon" v-if="icon" class="h-4 w-4 shrink-0 text-slate-400" aria-hidden="true" />

      <input
        :id="id"
        v-model="model"
        v-bind="inputAttrs"
        :type="inputType"
        class="field-input h-full min-w-0 flex-1 text-slate-900 placeholder:text-slate-400 dark:text-slate-100"
        :aria-invalid="error ? 'true' : undefined"
        :aria-describedby="describedBy"
      />

      <button
        v-if="isPassword"
        type="button"
        class="-mr-1 grid h-8 w-8 shrink-0 place-items-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300"
        :aria-label="revealed ? 'Sembunyikan password' : 'Tampilkan password'"
        :aria-pressed="revealed"
        @click="revealed = !revealed"
      >
        <component :is="revealed ? EyeOff : Eye" class="h-4 w-4" aria-hidden="true" />
      </button>
    </div>

    <p v-if="hint && !error" :id="`${id}-hint`" class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">{{ hint }}</p>

    <p v-if="error" :id="`${id}-error`" class="mt-1.5 flex items-start gap-1.5 text-sm text-rose-600 dark:text-rose-400" role="alert">
      <CircleAlert class="mt-0.5 h-4 w-4 shrink-0" aria-hidden="true" />
      {{ error }}
    </p>
  </div>
</template>
