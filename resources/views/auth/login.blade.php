
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MoneyFlow</title>
    <link rel="stylesheet" href="/css/auth.css">
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N31FE4HZC0"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-N31FE4HZC0');
</script>
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
            <form action="{{ route('login') }}" method="POST">
                <h2>Login</h2>
                @csrf

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan alamat email anda" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password anda" required>
                </div>

                <button type="submit" class="btn-primary">Masuk</button>

                <p class="footer-text">Belum punya akun?
                    <a href="/register">Klik disini</a>
                </p>
            </form>
            @if (session('success') || session('error'))
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
                        {{ session('success') ?? session('error') }}
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
