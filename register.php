<?php
include "koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Travel</title>
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
            background: var(--bg-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }
        .register-container {
            width: 100%;
            max-width: 560px;
        }
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand a {
            font-size: 28px;
            font-weight: 800;
            color: var(--secondary);
            text-decoration: none;
        }
        .brand a span { color: var(--primary); }
        .card-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            padding: 40px;
        }
        .card-form h2 {
            font-size: 24px;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 4px;
        }
        .card-form .subtitle {
            color: var(--text-muted);
            font-size: 13px;
            margin-bottom: 28px;
        }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
            display: block;
        }
        .input-wrapper { position: relative; }
        .input-wrapper i.icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }
        .input-wrapper input {
            width: 100%;
            padding: 11px 16px 11px 40px;
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
        .hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 5px;
        }
        .hint.success { color: #059669; }
        .hint.danger { color: #dc2626; }
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
            margin-top: 8px;
        }
        .btn-submit:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,165,0,0.4);
        }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
        .divider {
            text-align: center;
            margin: 20px 0;
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
        .login-link {
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }
        .login-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }
        .login-link a:hover { text-decoration: underline; }
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
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 480px) { .row-2 { grid-template-columns: 1fr; } .card-form { padding: 28px 20px; } }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="brand"><a href="index.php"><span>T</span>ravel</a></div>

        <div class="card-form">
            <h2>Buat Akun Baru ✨</h2>
            <p class="subtitle">Bergabung dan mulai perjalanan impianmu hari ini</p>

            <form action="proses.php" method="POST" id="registerForm">
                <div class="row-2">
                    <div class="form-group">
                        <label for="nama_lengkap"><i class="fas fa-user" style="color:#ffa500;margin-right:5px;"></i>Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="icon fas fa-user"></i>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Nama lengkap" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username"><i class="fas fa-at" style="color:#ffa500;margin-right:5px;"></i>Username</label>
                        <div class="input-wrapper">
                            <i class="icon fas fa-at"></i>
                            <input type="text" id="username" name="username" placeholder="Min. 4 karakter" minlength="4" required>
                        </div>
                        <div class="hint">Minimal 4 karakter</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope" style="color:#ffa500;margin-right:5px;"></i>Email</label>
                    <div class="input-wrapper">
                        <i class="icon fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                    <div class="hint">Digunakan untuk reset password</div>
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock" style="color:#ffa500;margin-right:5px;"></i>Password</label>
                        <div class="input-wrapper">
                            <i class="icon fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Min. 6 karakter" minlength="6" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock" style="color:#ffa500;margin-right:5px;"></i>Konfirmasi</label>
                        <div class="input-wrapper">
                            <i class="icon fas fa-lock"></i>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                        </div>
                        <div id="password-match" class="hint"></div>
                    </div>
                </div>

                <button class="btn-submit" type="submit" name="submitRegister" id="submitBtn">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="divider">sudah punya akun?</div>
            <div class="login-link"><a href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Masuk ke Akun</a></div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const matchMessage = document.getElementById('password-match');
        const submitBtn = document.getElementById('submitBtn');
        function checkPasswordMatch() {
            if (confirmPassword.value === '') {
                matchMessage.textContent = '';
                matchMessage.className = 'hint';
                submitBtn.disabled = false;
                return;
            }
            if (password.value === confirmPassword.value) {
                matchMessage.textContent = '✓ Password cocok';
                matchMessage.className = 'hint success';
                submitBtn.disabled = false;
            } else {
                matchMessage.textContent = '✗ Password tidak cocok';
                matchMessage.className = 'hint danger';
                submitBtn.disabled = true;
            }
        }
        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
