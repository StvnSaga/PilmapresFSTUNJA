@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="container">
    <div class="card shadow-sm" style="border-radius: 15px; position: relative; overflow: hidden; max-width: 1000px; margin: auto; border: 3px solid #00135E;">
        
        {{-- Dekorasi ikon --}}
        <img src="{{ asset('images/icon-trophy.png') }}" alt="Trophy" style="position: absolute; top: 20px; right: 20px; width: 100px; opacity: 0.8;">
        <img src="{{ asset('images/icon-ribbon.png') }}" alt="Ribbon" style="position: absolute; bottom: 20px; right: 20px; width: 100px; opacity: 0.8;">

        <div class="px-4 py-3">
            <h6 class="fw-bold mb-0" style="color: #00135E;">PILMAPRES FST</h6>
        </div>

        <div class="card-body p-md-2">
            <div class="row align-items-center justify-content-center">

                {{-- Ilustrasi kiri --}}
                <div class="col-md-5 d-none d-md-block">
                    <img src="{{ asset('images/login-illustration.png') }}" alt="Ilustrasi Login" class="img-fluid px-1 py-4">
                </div>

                {{-- Form login --}}
                <div class="col-md-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-11">
                            <h2 class="fw-bold mb-1" style="color: #00135E;">Masuk</h2>
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">Selamat datang di Sistem Informasi PILMAPRES FST Universitas Jambi</p>

                            {{-- Tampilkan error jika ada --}}
                            @if ($errors->any())
                                <div class="alert alert-danger p-2 mt-3 small">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('login.authenticate') }}" method="POST" class="text-start mt-3">
                                @csrf

                                <div class="mb-1">
                                    <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                                </div>

                                <div class="mb-2">
                                    <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-grid mt-4 w-50">
                                    <button type="submit" class="btn btn-primary" style="border-radius: 15px; background-color: #00135E; border-color: #00135E;">Masuk</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle tampil/sembunyi password
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    }
</script>
@endpush