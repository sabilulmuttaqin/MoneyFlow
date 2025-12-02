<nav class="navbar">
    <div class="navbar-left">
        <h3>Welcome back, {{ Auth::user()->name }} 👋</h3>
    </div>

    <div class="navbar-right">
        <div class="profile-card">
            <img src="{{ asset('images/profile.avif') }}" class="profile-img" alt="User">
            <div class="user-info">
                <span class="username">{{ Auth::user()->name }}</span>
                <span class="email">{{ Auth::user()->email }}</span>
            </div>
        </div>
    </div>
</nav>
