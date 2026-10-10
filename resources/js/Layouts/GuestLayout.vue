<!--
  Layout untuk halaman tamu (belum login): login, lupa password, dll.
  Menampilkan isi halaman (slot) di tengah layar dalam sebuah kartu.
-->
<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md">
            <h1 class="mb-6 text-center text-2xl font-semibold text-gray-800">Lontar</h1>

            <div class="rounded-lg bg-white p-6 shadow">
                <!-- Konten halaman yang memakai layout ini -->
                <slot />
            </div>
        </div>
    </div>
</template>
