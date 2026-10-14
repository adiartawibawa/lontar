<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    {{-- Font Plus Jakarta Sans (sama dengan mockup) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{--
        Terapkan tema gelap SEBELUM halaman digambar agar tidak berkedip.
        Urutan: pilihan manual pengguna (tombol tema, tersimpan di localStorage),
        jika belum ada, ikuti pengaturan tema sistem/peramban.
    --}}
    <script>
        try {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    </script>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <x-inertia::head />
</head>

<body class="font-sans text-slate-700 antialiased dark:text-slate-300">
    <x-inertia::app />
</body>

</html>
