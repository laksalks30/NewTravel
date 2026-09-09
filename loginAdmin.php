<?php
include "koneksi.php";
include "config_security.php";

if (isset($_SESSION['id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
    header("Location: dashboardAdmin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 165, 0, 0.08);
            border-radius: 50%;
            top: -150px;
            right: -150px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            background: rgba(255, 165, 0, 0.05);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
        }

        .admin-login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            width: 100%;
            max-width: 440px;
            padding: 40px 35px;
            position: relative;
            z-index: 2;
        }

        .admin-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .admin-header .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            padding: 5px 16px;
            border-radius: 20px;
            margin-bottom: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .admin-header h3 {
            font-size: 26px;
            font-weight: 800;
            color: var(--secondary);
            margin-bottom: 6px;
        }

        .admin-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 6px;
        }

        .input-group-text {
            background: var(--bg-soft);
            border-color: var(--border);
            color: #888;
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            border-color: var(--border);
            padding: 11px 14px;
            font-size: 14px;
            border-radius: 0 12px 12px 0;
            background: var(--bg-soft);
            transition: all 0.3s;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.15);
        }

        .btn-admin-login {
            background: var(--primary);
            color: white;
            font-weight: 700;
            font-size: 15px;
            padding: 12px;
            border-radius: 12px;
            border: none;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(255, 165, 0, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-admin-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(255, 165, 0, 0.45);
            color: white;
        }

        .back-to-home {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
        }

        .back-to-home a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-to-home a:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="admin-login-card">
        <div class="admin-header">
            <div class="admin-badge">
                <i class="fas fa-shield-alt"></i> Area Administrator
            </div>
            <h3>Login Admin</h3>
            <p>Masukkan kredensial admin untuk mengakses panel dashboard</p>
        </div>

        <?php if (isset($_GET['loginGagal'])) : ?>
            <div class="alert alert-danger d-flex align-items-center mb-4 py-2 px-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <small>Username atau Password salah!</small>
            </div>
        <?php elseif (isset($_GET['logoutSukses'])) : ?>
            <div class="alert alert-success d-flex align-items-center mb-4 py-2 px-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <small>Logout berhasil. Silakan login kembali.</small>
            </div>
        <?php elseif (isset($_GET['require'])) : ?>
            <div class="alert alert-warning d-flex align-items-center mb-4 py-2 px-3" role="alert">
                <i class="fas fa-lock me-2"></i>
                <small>Silakan login sebagai admin terlebih dahulu.</small>
            </div>
        <?php endif; ?>

        <form action="proses.php" method="POST">
            <div class="mb-3">
                <label class="form-label" for="username">Username Admin</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required autofocus />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required />
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius: 0 12px 12px 0; border-color: var(--border); background: var(--bg-soft);">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button class="btn-admin-login" type="submit" name="submitAdmin">
                <i class="fas fa-sign-in-alt"></i> Masuk Dashboard
            </button>
        </form>

        <div class="back-to-home">
            <a href="index.php">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    </script>
</body>
</html>