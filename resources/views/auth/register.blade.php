
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MoneyFlow</title>
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body>

    <div class="auth-container">

        <!-- Left Side -->
        <div class="auth-left">
            <img src="/images/logo.png" class="logo" alt="MoneyFlow">
            <img src="/images/auth.png" class="illustration" alt="Illustration">
        </div>

        <!-- Right Side -->
        <div class="auth-right">
            <form action="{{ route('register') }}" method="POST">
                <h2>Register</h2>
                @csrf

                <div class="input-group">
                    <label>Nama</label>
                    <input type="text" name="name" placeholder="Masukkan Nama anda" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan alamat email anda" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password anda" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi password anda" required>
                </div>

                <button type="submit" class="btn-primary">Masuk</button>

                <p class="footer-text">Sudah punya akun?
                    <a href="/login">Klik disini</a>
                </p>
            </form>
            @if (session('success') || session('error') || $errors->any())
            <div class="alert-overlay show" id="alertModal">
                <div class="alert-modal {{ session('success') ? 'success' : 'error' }}">
                    <div class="alert-icon">
                        @if(session('success'))
                            ✓
                        @else
                            !
                        @endif
                    </div>

                    <h3 class="alert-title">
                        {{ session('success') ? 'Berhasil' : 'Gagal' }}
                    </h3>

                    <p class="alert-message">
                        @if(session('success'))
                            {{ session('success') }}
                        @elseif(session('error'))
                            {{ session('error') }}
                        @elseif($errors->any())
                            @if($errors->has('email'))
                                Email sudah terdaftar, silakan gunakan email lain.
                            @elseif($errors->has('password'))
                                Password dan konfirmasi password tidak cocok.
                            @else
                                {{ $errors->first() }}
                            @endif
                        @endif
                    </p>

                    <button class="alert-btn" onclick="closeAlert()">OK</button>
                </div>
            </div>
            @endif

        </div>

    </div>
    <script>
    function closeAlert() {
        const modal = document.getElementById('alertModal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        }
    }

    // Auto close (opsional)
    setTimeout(() => {
        closeAlert();
    }, 3500);
    </script>
</body>

</html>
