<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Surat')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('head')
    @yield('head')
    <script>
        function confirmDelete(e){ if(!confirm('Yakin ingin menghapus data ini?')){ e.preventDefault(); } }
    </script>
    @stack('scripts-head')
    @yield('scripts-head')
    
</head>
<body class="bg-light">
    
    @auth
    @include('layouts.navbar')
        <!-- Overlay untuk sidebar (mobile) -->
        <div id="sidebarOverlay" class="sidebar-overlay"></div>
        <div class="container-fluid">
            <div class="row">
                <aside id="sidebarColumn" class="col-12 col-lg-2 col-md-3 bg-white border-end min-vh-100 p-0">
                    @include('layouts.sidebar')
                </aside>
                <main class="col-12 col-lg-10 col-md-9 p-4">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="container py-5">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    @endauth

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
      /* Sidebar overlay */
      .sidebar-overlay { display:none; position: fixed; top: 56px; left:0; right:0; bottom:0; background: rgba(0,0,0,.35); z-index: 1040; }
      .sidebar-overlay.show { display:block; }
      /* Remove sidebar column height on mobile to let main content rise to top */
      @media (max-width: 991.98px) {
        #sidebarColumn { height: 0 !important; min-height: 0 !important; padding: 0 !important; border: 0 !important; }
      }
    </style>
    <script>
      (function(){
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay && window.bootstrap) {
          sidebar.addEventListener('shown.bs.collapse', function(){ overlay.classList.add('show'); document.body.style.overflow = 'hidden'; });
          sidebar.addEventListener('hidden.bs.collapse', function(){ overlay.classList.remove('show'); document.body.style.overflow = ''; });
          overlay.addEventListener('click', function(){
            const instance = bootstrap.Collapse.getOrCreateInstance(sidebar);
            instance.hide();
          });
        }
      })();
    </script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>
