@extends('layouts.panel')

@section('title', 'Profil Saya')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil Saya</h3>
                <p class="text-subtitle text-muted">Kelola informasi akun dan kata sandi Anda.</p>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            @include('partials.panel._notification')
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="avatar avatar-2xl mx-auto">
                                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar">
                            </div>
                            <h4 class="mt-3">{{ $user->name }}</h4>
                            <p class="text-small text-muted">{{ $user->email }}</p>
                            <x-role-badge :role="$user->role" />
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Aktivitas Terbaru</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse ($logs as $log)
                                    <li class="list-group-item list-group-item-action px-0">
                                        <p class="mb-0">{{ $log->description }}</p>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center px-0">
                                        Belum ada aktivitas.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <form action="{{ route('profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Profil</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <input type="email" id="email" class="form-control" value="{{ $user->email }}"
                                        disabled readonly>
                                    <small class="text-muted">Email tidak dapat diubah.</small>
                                </div>

                                <hr class="my-4">

                                <h5 class="mb-3">Ubah Kata Sandi (Opsional)</h5>
                                <div class="form-group">
                                    <label for="password" class="form-label">Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Kosongkan jika tidak diubah">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="togglePasswordConfirmation">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function setupPasswordToggle(toggleButtonId, passwordInputId) {
            const toggleButton = document.getElementById(toggleButtonId);
            const passwordInput = document.getElementById(passwordInputId);

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                });
            }
        }
        setupPasswordToggle('togglePassword', 'password');
        setupPasswordToggle('togglePasswordConfirmation', 'password_confirmation');
    </script>
@endpush
