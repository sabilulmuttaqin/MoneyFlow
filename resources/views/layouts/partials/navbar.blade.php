<nav class="navbar">
    <div class="navbar-left">
        <h3>Welcome back, {{ Auth::user()->name }} 👋</h3>
    </div>

    <div class="navbar-right">
        <div class="profile-dropdown">
            <div class="profile-card" id="profileToggle">
                <img src="{{ Auth::user()->profile_photo_url }}" class="profile-img" alt="User">
                <div class="user-info">
                    <span class="username">{{ Auth::user()->name }}</span>
                    <span class="email">{{ Auth::user()->email }}</span>
                </div>
                <svg class="dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </div>
            
            <div class="profile-menu" id="profileMenu">
                <a href="{{ route('profile.show') }}" class="profile-menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('profileToggle');
    const menu = document.getElementById('profileMenu');
    
    toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.classList.toggle('show');
        toggle.classList.toggle('active');
    });
    
    document.addEventListener('click', function(e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            toggle.classList.remove('active');
        }
    });
});
</script>
