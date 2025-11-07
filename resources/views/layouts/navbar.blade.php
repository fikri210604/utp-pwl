<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
        <!-- Tombol Toggle Sidebar untuk mobile -->
        <button class="btn btn-outline-light me-2 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-expanded="false" aria-label="Toggle sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand/Logo Aplikasi -->
        <a class="navbar-brand d-flex align-items-center me-auto" href="{{ route('dashboard') }}">
            <i class="bi bi-envelope-paper-fill fs-4 me-2"></i>
            <span class="fw-bold">Manajemen Surat</span>
        </a>

        <!-- Tombol Toggle Navbar untuk mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Form Pencarian di sisi kanan -->
            <form class="d-flex ms-auto me-3" role="search" method="GET" action="{{ route('search.index') }}">
                <input class="form-control" type="search" name="q" placeholder="Cari surat..." aria-label="Search" value="{{ request('q') ?? '' }}">
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                @auth
                    <!-- Dropdown Profil Pengguna -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff" alt="Profil" width="32" height="32" class="rounded-circle me-2">
                            {{ auth()->user()->name ?? 'Pengguna' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Link Login jika belum terautentikasi -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>