<nav class="navbar navbar-expand-lg sticky-md-top py-3 bg-white shadow-lg"
     style="border-radius: 0 0 20px 20px; z-index: 1000;">
    <div class="container">

        <!-- Logo dan Nama -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Logo PILMAPRES FST" width="40" class="me-2">
            <span class="fw-semibold" style="color: #00135E;">PILMAPRESFST</span>
        </a>

        <!-- Toggler untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center fw-semibold">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('home') }}" style="color: #00135E;">Beranda</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a href="/login" class="btn btn-primary rounded-pill px-5"
                       style="background-color: #00135E; border-color: #00135E;">
                        Masuk
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
