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
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        {{-- SVG Path for Sun Icon --}}
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        {{-- SVG Path for Moon Icon --}}
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
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Menu untuk Admin --}}
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
                @endif

                {{-- Menu untuk Panitia --}}
                @if(auth()->user()->role === 'panitia')
                    <li class="sidebar-item {{ request()->routeIs('panel.peserta.*') ? 'active' : '' }}">
                        <a href="{{ route('panel.peserta.index') }}" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>Manajemen Peserta</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('panel/penilaian-cu*') ? 'active' : '' }}">
                        <a href="{{ route('panel.penilaian.capaian-unggulan') }}" class="sidebar-link d-flex align-items-center">
                            <i class="bi bi-award"></i>
                            <span>Penilaian Capaian Unggulan</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('panel.laporan.rekap-nilai', 'panel.laporan.detail-rekap') ? 'active' : '' }}">
                        <a href="{{ route('panel.laporan.rekap-nilai') }}" class='sidebar-link'>
                            <i class="bi bi-clipboard-data-fill"></i>
                            <span>Rekapitulasi Nilai</span>
                        </a>
                    </li>
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
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Periode</span>
                        </a>
                    </li>
                @endif

                {{-- Menu untuk Juri (SUDAH DISESUAIKAN) --}}
                @if(auth()->user()->role === 'juri')
                    {{-- Menampilkan menu penilaian berdasarkan spesialisasi juri --}}
                    @if(auth()->user()->jenis_juri === 'GK')
                        <li class="sidebar-item {{ request()->is('panel/penilaian/gagasan-kreatif*') ? 'active' : '' }}">
                            <a href="{{ route('juri.penilaian.gk') }}" class="sidebar-link d-flex align-items-center">
                                <i class="bi bi-card-checklist me-2"></i>
                                <span>Penilaian Gagasan Kreatif</span>
                            </a>
                        </li>
                    @elseif(auth()->user()->jenis_juri === 'BI')
                        <li class="sidebar-item {{ request()->is('panel/penilaian/bahasa-inggris*') ? 'active' : '' }}">
                            <a href="{{ route('juri.penilaian.bi') }}" class="sidebar-link d-flex align-items-center">
                                <i class="bi bi-card-checklist me-2"></i>
                                <span>Penilaian Bahasa Inggris</span>
                            </a>
                        </li>
                    @endif

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
                            <span>Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
