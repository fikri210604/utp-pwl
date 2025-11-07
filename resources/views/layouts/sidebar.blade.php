<div class="collapse d-lg-block bg-white min-vh-100" id="appSidebar">
    <div class="p-4 d-flex flex-column h-100">

        <div class="nav flex-column nav-pills gap-2 mt-4">


            <a href="{{ route('dashboard') }}"
                class="nav-link text-primary {{ request()->routeIs('dashboard') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-grid-1x2-fill fs-5"></i> <span>Dashboard</span>
            </a>

            <a href="{{ route('incoming-letters.index') }}"
                class="nav-link text-primary {{ request()->routeIs('incoming-letters.*') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-inbox-fill fs-5"></i> <span>Surat Masuk</span>
            </a>

            <a href="{{ route('outgoing-letters.index') }}"
                class="nav-link text-primary {{ request()->routeIs('outgoing-letters.*') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-send-fill fs-5"></i> <span>Surat Keluar</span>
            </a>

            <a href="{{ route('letter_code.index') }}"
                class="nav-link text-primary {{ request()->routeIs('letter_code.*') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-tags-fill fs-5"></i> <span>Kode Surat</span>
            </a>

            <a href="{{ route('perihal_surat.index') }}"
                class="nav-link text-primary {{ request()->routeIs('perihal_surat.*') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-card-text fs-5"></i> <span>Perihal Surat</span>
            </a>

            <a href="{{ route('penandatangan.index') }}"
                class="nav-link text-primary {{ request()->routeIs('penandatangan.*') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-person-badge fs-5"></i> <span>Penandatangan</span>
            </a>

            <a href="{{ route('user_manajemen.index') }}"
                class="nav-link text-primary {{ request()->routeIs('user_manajemen.*') ? 'active bg-primary text-white' : '' }}">
                <i class="bi bi-people-fill fs-5"></i> <span>User</span>
            </a>

        </div>

        @auth
            <div class="mt-auto pt-4 border-top">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link text-primary w-100 d-flex align-items-center gap-3" type="submit">
                        <i class="bi bi-power fs-5"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
            <!-- Mobile-only quick logout inside menu -->
            <a href="#" class="nav-link text-primary d-lg-none"
                onclick="event.preventDefault(); document.getElementById('logoutFormSidebarQuick').submit();">
                <i class="bi bi-power fs-5"></i> <span>Logout</span>
            </a>
            <form id="logoutFormSidebarQuick" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
            </form>
        @endauth


    </div>
</div>
<style>
    /* Responsiveness: transform sidebar to slide from left on mobile */
    @media (max-width: 991.98px) {
        #appSidebar.collapse {
            position: fixed;
            top: 56px;
            /* navbar height */
            left: 0;
            width: 80%;
            max-width: 320px;
            height: calc(100vh - 56px) !important;
            /* override collapse height */
            overflow-y: auto;
            z-index: 1045;
            /* above content */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
            border-right: 1px solid rgba(0, 0, 0, .1);
            display: block;
            /* allow transform anim */
            transform: translateX(-100%);
            transition: transform .25s ease-in-out;
        }

        #appSidebar.collapse.show {
            transform: translateX(0);
        }
    }
</style>