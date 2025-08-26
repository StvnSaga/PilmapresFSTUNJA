{{-- File: resources/views/layouts/guest.blade.php (Versi Final) --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PILMAPRES FST</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Framework & Ikon CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- CSS Kustom Anda (yang berisi kelas .guest-layout) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app-style.css') }}"> {{-- Sesuaikan path jika perlu --}}

</head>
{{-- PERUBAHAN: Menambahkan kelas .guest-layout dan menghapus inline style --}}
<body class="guest-layout d-flex align-items-center justify-content-center vh-100">

    @yield('content')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>