<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilmapres FST</title>

    {{-- Google Fonts - Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS & Icons (dari CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- CSS Kustom Anda --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app-style.css') }}">

</head>
<body>
    
    @include('partials.front.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.front.footer')

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JavaScript Kustom Anda --}}
    <script src="{{ asset('assets/js/app-script.js') }}"></script>

    {{-- Untuk script spesifik per halaman --}}
    @stack('scripts')
    
</body>
</html>