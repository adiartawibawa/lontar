<script setup>
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

// Menetapkan layout untuk halaman ini (fitur defineOptions milik Vue 3.3+)
defineOptions({ layout: DashboardLayout })
</script>

<template>

    <Head title="Dashboard" />

    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    <p class="mt-2 text-gray-600">Selamat datang di Lontar.</p>
</template>
