<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Sekolah</title>
    {{-- 👇 TAMBAHKAN KODE INI 👇 --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-aba.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/logo-aba.png') }}">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- Tailwind CDN --}}

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 
    <style>
        .sidebar {
            transition: 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex">

    {{-- Sidebar --}}
    @include('layout.sidebar')

    <div class="flex-1 min-h-screen">

        {{-- Navbar --}}
        @include('layout.navbar')

        {{-- Konten Utama --}}
        <main class="p-6">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
