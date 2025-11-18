<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MoneyFlow</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="auth-container">

    <!-- Left Side -->
    <div class="auth-left">
        <img src="/images/logo.png" class="logo" alt="MoneyFlow">
        <img src="/images/illustration.png" class="illustration" alt="Illustration">
    </div>

    <!-- Right Side -->
    <div class="auth-right">
        <h2>Login</h2>

        <form action="{{ route('login') }}" method="POST">
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
    </div>

</div>

</body>
</html>
