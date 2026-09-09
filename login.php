<?php
include "koneksi.php";
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
        :root {
            --primary: #ffa500;
            --primary-dark: #e09400;
            --secondary: #1a1a2e;
            --bg-soft: #f8f9fc;
            --border: #e8ecf0;
            --text: #2d2d2d;
            --text-muted: #777;
        }
        body {
            min-height: 100vh;
            display: flex;
            background: var(--bg-soft);
        }
        .left-panel {
            width: 55%;
            background: linear-gradient(135deg, var(--secondary) 0%, #16213e 50%, #0f3460 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,165,0,0.12);
            border-radius: 50%;
            top: -150px; right: -150px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,165,0,0.08);
            border-radius: 50%;
            bottom: -80px; left: -80px;
        }
        .left-panel .logo {
            font-size: 34px;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
            position: relative; z-index: 1;
        }
        .left-panel .logo span { color: var(--primary); }
        .left-panel .tagline {
            color: rgba(255,255,255,0.7);
            font-size: 15px;
            text-align: center;
            line-height: 1.7;
            max-width: 340px;
            position: relative; z-index: 1;
            margin-bottom: 40px;
        }
        .left-panel .feature-list {
            list-style: none;
            padding: 0;
            position: relative; z-index: 1;
        }
        .left-panel .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            margin-bottom: 14px;
        }
        .left-panel .feature-list li i {
            width: 32px; height: 32px;
            background: rgba(255,165,0,0.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 14px;
            flex-shrink: 0;
        }
        .right-panel {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            background: white;
        }
        .form-box { width: 100%; max-width: 380px; }
        .form-box h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 6px;
        }
        .form-box .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 32px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
            display: block;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i.icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }
        .input-wrapper input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
            outline: none;
            color: var(--text);
        }
        .input-wrapper input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,165,0,0.1);
        }
        .row-check {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
        }
        .row-check label { color: var(--text-muted); cursor: pointer; }
        .row-check a { color: var(--primary); font-weight: 600; text-decoration: none; }
        .row-check a:hover { text-decoration: underline; }
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,165,0,0.4);
        }
        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
            color: var(--text-muted);
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: var(--border);
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .register-link {
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }
        .register-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }
        .register-link a:hover { text-decoration: underline; }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-top: 16px;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .alert-warning { background: #fef3c7; color: #92400e; }
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 24px; }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div class="logo"><span>T</span>ravel</div>
        <p class="tagline">Temukan destinasi impianmu dan buat kenangan tak terlupakan bersama orang-orang terkasih.</p>
        <ul class="feature-list">
            <li><i class="fas fa-map-marked-alt"></i> Ratusan destinasi wisata pilihan</li>
            <li><i class="fas fa-shield-alt"></i> Booking aman & terpercaya</li>
            <li><i class="fas fa-headset"></i> Dukungan 24/7 siap membantu</li>
            <li><i class="fas fa-tag"></i> Harga terbaik & transparan</li>
        </ul>
    </div>

    <div class="right-panel">
        <div class="form-box">
            <h2>Selamat Datang 👋</h2>
            <p class="subtitle">Masuk ke akun Anda untuk mulai menjelajahi</p>

            <form action="proses.php" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user" style="margin-right:6px;color:#ffa500;"></i> Username</label>
                    <div class="input-wrapper">
                        <i class="icon fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock" style="margin-right:6px;color:#ffa500;"></i> Password</label>
                    <div class="input-wrapper">
                        <i class="icon fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="row-check">
                    <label>
                        <input type="checkbox" id="remember_me" name="remember_me" style="accent-color:#ffa500;margin-right:6px;">
                        Ingat Saya
                    </label>
                    <a href="forgot_password.php">Lupa Password?</a>
                </div>

                <button class="btn-submit" type="submit" name="submitLogin">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </button>
            </form>

            <div class="divider">atau</div>

            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar Gratis</a>
            </div>

            <?php if (isset($_GET['regSukses'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> Register berhasil! Silakan login.</div>
            <?php elseif (isset($_GET['loginGagal'])): ?>
                <div class="alert alert-danger"><i class="fas fa-times-circle"></i> Username atau Password salah!</div>
            <?php elseif (isset($_GET['logoutSukses'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> Logout berhasil!</div>
            <?php elseif (isset($_GET['resetSukses'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> Password berhasil direset!</div>
            <?php elseif (isset($_GET['session']) && $_GET['session'] == 'expired'): ?>
                <div class="alert alert-warning"><i class="fas fa-clock"></i> Session expired. Silakan login kembali.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
