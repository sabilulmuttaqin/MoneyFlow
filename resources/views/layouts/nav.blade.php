<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyFlow</title>
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    @yield('styles')
</head>

<body>
    @include('layouts.partials.navbar')
    
    {{-- Hamburger Toggle Button (only shows when sidebar is closed) --}}
    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
        <svg class="hamburger-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    
    {{-- Overlay for mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="layout">

        <aside class="sidebar" id="sidebar">
            {{-- Logo Section with Close Button --}}
            <div class="logo-section">
                <img src="{{ asset('images/logo.png') }}" alt="MoneyFlow" class="logo-img">
                {{-- Close button inside sidebar --}}
                <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="menu-label">Menu</div>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Home</span>
                </a>

                <a href="{{ route('kategori.index') }}"
                    class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('ringkasan.bulanan') }}" class="nav-item {{ request()->routeIs('ringkasan.*') ? 'active' : '' }}">
                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Ringkasan Bulanan</span>
                </a>

                <a href="{{ route('tabungan.index') }}"
                    class="nav-item {{ request()->routeIs('tabungan.*') ? 'active' : '' }}">
                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Tabungan</span>
                </a>

                <a href="{{ route('anggaran.index') }}"
                    class="nav-item {{ request()->routeIs('anggaran.*') ? 'active' : '' }}">
                    <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Anggaran</span>
                </a>
            </nav>

            {{-- Logout --}}
            <a class="logout" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                <svg class="logout-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>

                <span>Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>

        </aside>

        {{-- MAIN CONTENT --}}

        <main class="main-content">
            @yield('content')
        </main>

    </div>

    @yield('scripts')
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            hamburgerBtn.style.display = 'none';
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            hamburgerBtn.style.display = 'block';
        }
        
        hamburgerBtn.addEventListener('click', openSidebar);
        sidebarCloseBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
        
        // Close sidebar when clicking on nav item (mobile)
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    closeSidebar();
                }
            });
        });
    });
    </script>
</body>

</html>
