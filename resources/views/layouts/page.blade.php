    {{-- File: resources/views/layouts/page.blade.php --}}
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title') - PILMAPRES FST</title>

        {{-- Aset CSS & Fonts --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
 
       <link rel="stylesheet" href="{{ asset('assets/css/app-style.css') }}">
</head>
    </head>
    <body>

        {{-- 1. Memanggil Navbar khusus untuk halaman ini --}}
        @include('partials.front.navbar-page')

        <main>
            {{-- 2. Konten halaman akan disisipkan di sini --}}
            @yield('content')
        </main>

        {{-- 3. Memanggil Footer utama --}}
        @include('partials.front.footer')

        {{-- Aset JavaScript --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
    </html>