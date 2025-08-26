@extends('layouts.panel')

@section('title', 'Manajemen Role & User')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Role & User</h3>
                    <p class="text-subtitle text-muted">Kelola pengguna dan hak akses sistem.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manajemen User</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

        <div class="page-content">
        <section class="section">
            @include('partials.panel._notification')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Pengguna</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                        <i class="bi bi-plus-circle"></i> Tambah User
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-users">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><x-role-badge :role="$user->role" /></td>
                                        <td>
                                            {{-- !! PERBAIKAN DI SINI !! --}}
                                            {{-- Tombol hanya muncul jika user ID bukan 1 --}}
                                            @if ($user->id !== 1)
                                                <button class="btn btn-sm btn-warning edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                        data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline form-delete" data-confirm-text="Yakin ingin menghapus user '{{ $user->name }}'?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @else
                                                <span class="badge bg-light-secondary">Super Admin</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Data pengguna tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Memanggil modal dari file partial --}}
    @include('panel.manajemen-user._tambah-user-modal')
    @include('panel.manajemen-user._edit-user-modal')
@endsection

@push('scripts')
    {{-- Jangan lupa pindahkan script ke file eksternal jika belum --}}
    {{-- <script src="{{ asset('assets/js/manajemen-user.js') }}"></script> --}}

    {{-- Atau biarkan di sini jika Anda lebih suka --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script untuk alert
            const alerts = document.querySelectorAll('.auto-dismiss');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Script untuk modal edit
            const editUserModal = document.getElementById('editUserModal');
            if (editUserModal) {
                editUserModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const email = button.getAttribute('data-email');
                    const role = button.getAttribute('data-role');
                    const form = editUserModal.querySelector('#form-edit-user');
                    form.action = `/panel/users/${id}`; // Pastikan path ini benar
                    form.querySelector('#edit_name').value = name;
                    form.querySelector('#edit_email').value = email;
                    form.querySelector('#edit_role').value = role;
                });
            }
        });
    </script>
@endpush