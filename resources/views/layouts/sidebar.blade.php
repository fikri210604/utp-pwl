<div class="collapse d-lg-block" id="appSidebar">
    <div class="px-3 py-3">
        <div class="nav flex-column">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('incoming-letters.index') }}" class="nav-link {{ request()->routeIs('incoming-letters.*') ? 'active fw-semibold' : '' }}">
                Surat Masuk
            </a>
            <a href="{{ route('outgoing-letters.index') }}" class="nav-link {{ request()->routeIs('outgoing-letters.*') ? 'active fw-semibold' : '' }}">
                Surat Keluar
            </a>
            <a href="{{ route('user_manajemen.index') }}" class="nav-link {{ request()->routeIs('user_manajemen.*') ? 'active fw-semibold' : '' }}">
                User
            </a>
            <a href="{{ route('letter_code.index') }}" class="nav-link {{ request()->routeIs('nomor-surat.*') ? 'active fw-semibold' : '' }}">
                Kode Surat
            </a>
        </div>

        @auth
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger w-100" type="submit">Logout</button>
        </form>
        @endauth
    </div>
</div>
