<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    {{-- Font Plus Jakarta Sans (sama dengan mockup) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{--
        Terapkan tema gelap yang tersimpan SEBELUM halaman digambar agar tidak berkedip.
        Sementara ini hanya mengikuti pilihan manual pengguna (tombol tema), bukan
        pengaturan sistem, karena sebagian halaman lama (login, profil, kelola pengguna)
        belum punya tampilan gelap. Setelah semuanya siap, ganti kondisi menjadi:
        t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)
    --}}
    <script>
        try {
            var t = localStorage.getItem('theme');
            // if (t === 'dark') document.documentElement.classList.add('dark');
            t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)
        } catch (e) {}
    </script>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <x-inertia::head />
</head>

<body class="font-sans text-slate-700 antialiased dark:text-slate-300">
    <x-inertia::app />
</body>

</html>
