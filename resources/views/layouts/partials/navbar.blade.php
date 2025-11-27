<nav class="navbar">
    <div class="navbar-left">
        <h3>Welcome back, {{ Auth::user()->name }} 👋</h3>
    </div>

    <div class="navbar-right">
        <div class="profile-info">
            <img src="{{ asset('images/profile.avif') }}" class="profile-img" alt="User">
            <span class="username">{{ Auth::user()->name }}</span>
        </div>
    </div>
</nav>
