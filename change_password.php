<?php
// Halaman Ganti Password - Form untuk mengubah password
require_once 'config_security.php';
requireLogin();

include 'koneksi.php';

$user_id = $_SESSION['id'];

// Ambil data user
$stmt = $conn->prepare("SELECT username, nama_lengkap, foto_profil FROM akun WHERE id_akun = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Cek jika user tidak ditemukan (session tidak valid)
if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php?session=expired");
    exit();
}

// Handle messages
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Travel Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .password-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .password-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .password-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .password-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 38px;
            color: #999;
        }
        .password-toggle:hover {
            color: #667eea;
        }
        .password-input-wrapper {
            position: relative;
        }
        .security-tips {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .security-tips h6 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .security-tips ul {
            margin: 0;
            padding-left: 20px;
        }
        .security-tips li {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-plane"></i> Travel Tour
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftarDestinasi.php">Destinasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listBooking.php">Booking Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php">
                            <i class="fas fa-user-circle"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proses.php?logout=true">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container password-container">
        <div class="password-card">
            <div class="password-header">
                <img src="<?php echo htmlspecialchars($user['foto_profil'] ?? 'images/profile/default.jpg'); ?>" 
                     alt="Profile">
                <h2>
                    <i class="fas fa-key text-warning"></i> Ganti Password
                </h2>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($user['nama_lengkap']); ?></p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> 
                    Password berhasil diubah! Silakan login kembali dengan password baru.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> 
                    <?php echo htmlspecialchars(urldecode($error)); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="proses_profile.php" method="POST" id="changePasswordForm">
                <input type="hidden" name="action" value="change_password">
                
                <!-- Password Lama -->
                <div class="mb-3 password-input-wrapper">
                    <label for="password_lama" class="form-label">
                        <i class="fas fa-lock"></i> Password Lama
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_lama" 
                           name="password_lama" 
                           required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password_lama')"></i>
                </div>

                <!-- Password Baru -->
                <div class="mb-3 password-input-wrapper">
                    <label for="password_baru" class="form-label">
                        <i class="fas fa-key"></i> Password Baru
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_baru" 
                           name="password_baru" 
                           required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password_baru')"></i>
                    <div class="password-strength" id="strengthBar"></div>
                    <small class="text-muted" id="strengthText"></small>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-4 password-input-wrapper">
                    <label for="konfirmasi_password" class="form-label">
                        <i class="fas fa-check-double"></i> Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="konfirmasi_password" 
                           name="konfirmasi_password" 
                           required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('konfirmasi_password')"></i>
                    <small class="text-muted" id="matchText"></small>
                </div>

                <!-- Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Ubah Password
                    </button>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>

            <!-- Security Tips -->
            <div class="security-tips">
                <h6><i class="fas fa-shield-alt"></i> Tips Keamanan Password:</h6>
                <ul>
                    <li>Gunakan minimal 8 karakter</li>
                    <li>Kombinasikan huruf besar, huruf kecil, angka, dan simbol</li>
                    <li>Jangan gunakan informasi pribadi (nama, tanggal lahir, dll)</li>
                    <li>Hindari password yang mudah ditebak seperti "12345678"</li>
                    <li>Gunakan password yang berbeda untuk setiap akun</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Travel Tour. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle show/hide password
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Check password strength
        document.getElementById('password_baru').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (password.length === 0) {
                strengthText.textContent = '';
            } else if (strength <= 1) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Password lemah';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Password sedang';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Password kuat';
                strengthText.style.color = '#28a745';
            }
        });

        // Check password match
        document.getElementById('konfirmasi_password').addEventListener('input', function() {
            const password = document.getElementById('password_baru').value;
            const confirm = this.value;
            const matchText = document.getElementById('matchText');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirm.length === 0) {
                matchText.textContent = '';
                submitBtn.disabled = false;
            } else if (password === confirm) {
                matchText.textContent = '✓ Password cocok';
                matchText.style.color = '#28a745';
                submitBtn.disabled = false;
            } else {
                matchText.textContent = '✗ Password tidak cocok';
                matchText.style.color = '#dc3545';
                submitBtn.disabled = true;
            }
        });

        // Validate form
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const passwordLama = document.getElementById('password_lama').value;
            const passwordBaru = document.getElementById('password_baru').value;
            const konfirmasi = document.getElementById('konfirmasi_password').value;

            if (passwordLama.length < 1) {
                e.preventDefault();
                alert('Password lama harus diisi!');
                return false;
            }

            if (passwordBaru.length < 8) {
                e.preventDefault();
                alert('Password baru harus minimal 8 karakter!');
                return false;
            }

            if (passwordBaru !== konfirmasi) {
                e.preventDefault();
                alert('Password baru dan konfirmasi tidak cocok!');
                return false;
            }

            if (passwordLama === passwordBaru) {
                e.preventDefault();
                alert('Password baru harus berbeda dengan password lama!');
                return false;
            }
        });
    </script>
</body>
</html>
