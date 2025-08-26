<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex flex-column justify-content-between align-items-center">
                <div class="logo">
                    @auth
                        @php $role = auth()->user()->role; @endphp
                        <a href="{{ route($role . '.dashboard') }}">
                            <img src="{{ asset('images/logo-text-dark.png') }}" alt="Logo" class="logo-dark">
                            <img src="{{ asset('images/logo-text-light.png') }}" alt="Logo" class="logo-light">
                        </a>
                    @endauth
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--system-uicons" width="20" height="20"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                            opacity=".3"></path>
                        <g transform="translate(-210 -1)">
                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                            <circle cx="220.5" cy="11.5" r="4"></circle>
                            <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                        </g>
                    </g>
                </svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                    </path>
                </svg>
            </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ request()->is('panel/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-grid-fill me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                    <li class="sidebar-item {{ request()->is('panel/users*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                            <i class="bi bi-person-check-fill"></i>
                            <span>Manajemen Role & User</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('panel/tahun-seleksi*') ? 'active' : '' }}">
                        <a href="{{ route('admin.tahun-seleksi.index') }}" class="sidebar-link">
                            <i class="bi bi-calendar-event-fill"></i>
                            <span>Manajemen Tahun Seleksi</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('rekam-jejak.*') ? 'active' : '' }}">
                        <a href="{{ route('rekam-jejak.index') }}" class='sidebar-link'>
                            <i class="bi bi-award-fill"></i>
                            <span>Manajemen Rekam Jejak</span>
                        </a>
                    </li>
                    {{-- <li class="sidebar-item {{ request()->routeIs('admin.riwayat.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.riwayat.index') }}" class='sidebar-link'>
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Periode</span>
                        </a>
                    </li> --}}
                @endif

                @if($role === 'panitia')
                    <li class="sidebar-item {{ request()->routeIs('panel.peserta.*') ? 'active' : '' }}">
                        <a href="{{ route('panel.peserta.index') }}" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>Manajemen Peserta</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('panel/penilaian-cu*') ? 'active' : '' }}">
                        <a href="{{ route('panel.penilaian.capaian-unggulan') }}" class="sidebar-link d-flex align-items-center">
                            <i class="bi bi-award me-2"></i>
                            <span>Penilaian CU</span>
                        </a>
                    </li>
                    {{-- PERBAIKAN: Memecah menu laporan menjadi dua dengan kondisi yang lebih spesifik --}}

                    {{-- 1. Menu untuk Rekap Nilai --}}
                    {{-- Akan aktif di halaman rekap-nilai DAN detail-rekap --}}
                    <li class="sidebar-item {{ request()->routeIs('panel.laporan.rekap-nilai', 'panel.laporan.detail-rekap') ? 'active' : '' }}">
                        <a href="{{ route('panel.laporan.rekap-nilai') }}" class='sidebar-link'>
                            <i class="bi bi-clipboard-data-fill"></i>
                            <span>Rekap Nilai</span>
                        </a>
                    </li>

                    {{-- 2. Menu untuk Live Ranking --}}
                    {{-- Hanya akan aktif di halaman live-ranking --}}
                    <li class="sidebar-item {{ request()->routeIs('panel.laporan.live-ranking') ? 'active' : '' }}">
                        <a href="{{ route('panel.laporan.live-ranking') }}" class='sidebar-link'>
                            <i class="bi bi-bar-chart-line-fill"></i>
                            <span>Live Ranking</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('panel/panduan-penilaian*') ? 'active' : '' }}">
                        <a href="{{ route('panel.panduan') }}" class="sidebar-link">
                            <i class="bi bi-book-half"></i>
                            <span>Panduan Penilaian</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('panel/riwayat*') ? 'active' : '' }}">
                        <a href="{{ route('panitia.riwayat.index') }}" class="sidebar-link">
                            <i class="bi bi-clock-history me-2"></i>
                            <span>Riwayat Periode</span>
                        </a>
                    </li>
                @endif

                @if($role === 'juri')
                    <li class="sidebar-item has-sub {{ request()->is('panel/penilaian/gagasan-kreatif*') || request()->is('panel/penilaian/bahasa-inggris*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link d-flex align-items-center">
                            <i class="bi bi-card-checklist me-2"></i>
                            <span>Penilaian</span>
                        </a>
                        {{-- PERUBAHAN UTAMA ADA DI BARIS DI BAWAH INI --}}
                        <ul class="submenu {{ request()->is('panel/penilaian/gagasan-kreatif*') || request()->is('panel/penilaian/bahasa-inggris*') ? 'active' : '' }}">
                            <li class="submenu-item {{ request()->is('panel/penilaian/gagasan-kreatif*') ? 'active' : '' }}">
                                <a href="{{ route('juri.penilaian.gk') }}" class="submenu-link">Gagasan Kreatif</a>
                            </li>
                            <li class="submenu-item {{ request()->is('panel/penilaian/bahasa-inggris*') ? 'active' : '' }}">
                                <a href="{{ route('juri.penilaian.bi') }}" class="submenu-link">Bahasa Inggris</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item {{ request()->is('panel/panduan-penilaian*') ? 'active' : '' }}">
                        <a href="{{ route('panel.panduan') }}" class="sidebar-link">
                            <i class="bi bi-book-half"></i>
                            <span>Panduan Penilaian</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-title">Akun</li>
                <li class="sidebar-item {{ request()->is('panel/profil*') ? 'active' : '' }}">
                    <a href="{{ route('profil.show') }}" class="sidebar-link d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-link btn btn-link text-danger d-flex align-items-center" style="width: 100%; text-align: left;">
                            <i class="bi bi-box-arrow-right me-2 text-danger"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
