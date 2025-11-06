
<div class="collapse d-lg-block min-vh-100" id="appSidebar" style="background-color: #2563eb; border-top-right-radius: 1.5rem; border-bottom-right-radius: 1.5rem;">
    <div class="p-4 d-flex flex-column h-100 text-white">

        <div class="nav flex-column nav-pills gap-2 mt-4">
            <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill fs-5" style="width: 20px;"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('incoming-letters.index') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('incoming-letters.*') ? 'active' : '' }}">
                <i class="bi bi-inbox-fill fs-5" style="width: 20px;"></i> <span>Surat Masuk</span>
            </a>
            <a href="{{ route('outgoing-letters.index') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('outgoing-letters.*') ? 'active' : '' }}">
                <i class="bi bi-send-fill fs-5" style="width: 20px;"></i> <span>Surat Keluar</span>
            </a>
            <a href="{{ route('letter_code.index') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('letter_code.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill fs-5" style="width: 20px;"></i> <span>Kode Surat</span>
            </a>
            <a href="{{ route('user_manajemen.index') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('user_manajemen.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill fs-5" style="width: 20px;"></i> <span>User</span>
            </a>
        </div>

        @auth
        <div class="mt-auto pt-4 border-top border-white border-opacity-25">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link d-flex align-items-center gap-3 w-100" type="submit"><i class="bi bi-power fs-5" style="width: 20px;"></i> <span>Logout</span></button>
            </form>
        </div>
        @endauth

    </div>
</div>
<style>
    .nav-pills .nav-link { color: white; }
    .nav-pills .nav-link.active, .nav-pills .nav-link:hover {
        color: #2563eb !important;
        background-color: white !important;
    }
</style>
