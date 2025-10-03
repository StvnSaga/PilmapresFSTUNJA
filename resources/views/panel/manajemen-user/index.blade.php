@extends('layouts.panel')

@section('title', 'Manajemen Role & User')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Role & User</h3>
                <p class="text-subtitle text-muted">Kelola pengguna dan hak akses sistem.</p>
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
                        <i class="bi bi-plus-circle"></i> Tambah Pengguna
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
                                    <th>Role & Spesialisasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <x-role-specialization-badge :role="$user->role" :specialization="$user->jenis_juri" />
                                        </td>
                                        <td>
                                            @if ($user->id !== 1)
                                                <button class="btn btn-sm btn-warning edit-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}"
                                                        data-email="{{ $user->email }}"
                                                        data-role="{{ $user->role }}"
                                                        data-jenis_juri="{{ $user->jenis_juri }}">
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

    @include('panel.manajemen-user._tambah-user-modal')
    @include('panel.manajemen-user._edit-user-modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataTable = new simpleDatatables.DataTable(document.getElementById('table-users'));

            const tambahUserModal = document.getElementById('tambahUserModal');
            if (tambahUserModal) {
                tambahUserModal.addEventListener('show.bs.modal', function () {
                    const form = this.querySelector('#form-tambah-user');
                    if (form) {
                        form.reset();
                    }
                    initializeJuriSpecializationToggle('tambah');
                });
            }

            const editUserModal = document.getElementById('editUserModal');
            if (editUserModal) {
                editUserModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const form = editUserModal.querySelector('#form-edit-user');
                    
                    form.action = `/panel/users/${button.dataset.id}`;
                    form.querySelector('#edit_name').value = button.dataset.name;
                    form.querySelector('#edit_email').value = button.dataset.email;
                    form.querySelector('#edit_role').value = button.dataset.role;
                    form.querySelector('#edit_jenis_juri').value = button.dataset.jenis_juri || '';

                    form.querySelector('#edit_role').dispatchEvent(new Event('change'));
                });
            }

            function initializeJuriSpecializationToggle(prefix) {
                const roleSelect = document.getElementById(`${prefix}_role`);
                const jenisJuriContainer = document.getElementById(`${prefix}_jenis_juri_container`);
                const jenisJuriSelect = document.getElementById(`${prefix}_jenis_juri`);

                if (!roleSelect || !jenisJuriContainer) return;

                const toggleVisibility = () => {
                    if (roleSelect.value === 'juri') {
                        jenisJuriContainer.style.display = 'block';
                        jenisJuriSelect.required = true;
                    } else {
                        jenisJuriContainer.style.display = 'none';
                        jenisJuriSelect.required = false;
                        jenisJuriSelect.value = '';
                    }
                };
                
                roleSelect.addEventListener('change', toggleVisibility);
                toggleVisibility();
            }

            initializeJuriSpecializationToggle('tambah');
            initializeJuriSpecializationToggle('edit');
        });
    </script>
@endpush