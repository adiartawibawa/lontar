<script setup>
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

// Layout area login
defineOptions({ layout: DashboardLayout })
</script>

<template>

    <Head title="Admin" />

    <h2 class="text-xl font-semibold text-gray-800">Area Admin</h2>
    <p class="mt-2 text-gray-600">Manajemen pengguna akan ditambahkan di sini.</p>
</template>
