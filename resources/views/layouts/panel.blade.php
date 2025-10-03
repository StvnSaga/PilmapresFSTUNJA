<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Pilmapres FST UNJA</title>
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/panel-style.css') }}">
</head>
<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        @include('partials.panel.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-xl-none d-block"><i class="bi bi-justify fs-3"></i></a>
            </header>
            <div class="page-content">
                @yield('content')
            </div>
            @include('partials.panel.footer')
        </div>
    </div>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('submit', function (event) {
                const form = event.target;

                if (form.classList.contains('form-delete')) {
                    event.preventDefault();
                    const confirmText = form.dataset.confirmText || 'Data yang dihapus tidak dapat dikembalikan!';
                    const confirmTitle = form.dataset.confirmTitle || 'Apakah Anda Yakin?';
                    Swal.fire({
                        title: confirmTitle,
                        text: confirmText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
                
                // Logika baru untuk konfirmasi umum
                if (form.classList.contains('form-confirm')) {
                    event.preventDefault();
                    const confirmTitle = form.dataset.confirmTitle || 'Apakah Anda Yakin?';
                    const confirmText = form.dataset.confirmText || 'Tindakan ini tidak dapat dibatalkan.';
                    
                    Swal.fire({
                        title: confirmTitle,
                        text: confirmText,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#435ebe',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            document.body.addEventListener('click', function (event) {
                const button = event.target.closest('.btn-finalize');
                if (button) {
                    event.preventDefault();
                    const form = button.closest('form');
                    const confirmText = button.dataset.confirmText || 'Anda tidak akan bisa mengubahnya lagi!';
                    const confirmTitle = button.dataset.confirmTitle || 'Anda Yakin Ingin Mengunci Nilai?';
                    
                    Swal.fire({
                        title: confirmTitle,
                        text: confirmText,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Kunci Nilai!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = button.name;
                            hiddenInput.value = button.value;
                            form.appendChild(hiddenInput);
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>