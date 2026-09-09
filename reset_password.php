<?php
include "koneksi.php";
include "config_security.php";

// Cek apakah ada token
if (!isset($_GET['token'])) {
    header("location: login.php");
    exit();
}

$token = sanitizeInput($_GET['token']);

// Validasi token
$stmt = $conn->prepare("SELECT id_akun, username, nama_lengkap FROM akun WHERE reset_token = ? AND reset_token_expire > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $tokenValid = false;
} else {
    $tokenValid = true;
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Travel Website</title>
    
    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php" id="logo"><span>T</span>ravel</a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Reset Password Section -->
    <section class="vh-100" style="background-image: url('background.jpg'); background-size: cover; background-position: center;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5">
                            <?php if ($tokenValid): ?>
                                <h3 class="mb-4 text-center">Reset Password</h3>
                                <p class="text-muted text-center mb-4">
                                    Halo, <strong><?php echo htmlspecialchars($user['nama_lengkap']); ?></strong>!<br>
                                    Masukkan password baru Anda
                                </p>
                                
                                <form action="proses_reset_password.php" method="POST" id="resetForm">
                                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                    
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="new_password">
                                            <i class="fas fa-lock"></i> Password Baru
                                        </label>
                                        <input type="password" id="new_password" name="new_password" 
                                               class="form-control" placeholder="Minimal 6 karakter" 
                                               minlength="6" required />
                                        <small class="text-muted">Minimal 6 karakter</small>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="confirm_password">
                                            <i class="fas fa-lock"></i> Konfirmasi Password
                                        </label>
                                        <input type="password" id="confirm_password" name="confirm_password" 
                                               class="form-control" placeholder="Ketik ulang password" 
                                               required />
                                        <small id="password-match-message" class="text-muted"></small>
                                    </div>

                                    <button class="btn btn-warning btn-block w-100 mb-3" type="submit" 
                                            name="submitResetPassword" id="submitBtn">
                                        <i class="fas fa-key"></i> Reset Password
                                    </button>
                                    
                                    <a href="login.php" class="btn btn-outline-secondary btn-block w-100">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                                    </a>
                                </form>

                                <!-- Alert Messages -->
                                <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger mt-3" role="alert">
                                        <i class="fas fa-exclamation-circle"></i> 
                                        <?php echo htmlspecialchars($_GET['error']); ?>
                                    </div>
                                <?php endif; ?>
                                
                            <?php else: ?>
                                <h3 class="mb-4 text-center text-danger">
                                    <i class="fas fa-times-circle"></i> Token Tidak Valid
                                </h3>
                                <p class="text-center">
                                    Token reset password tidak valid atau sudah expired.<br>
                                    Silakan minta link reset password baru.
                                </p>
                                <a href="forgot_password.php" class="btn btn-warning btn-block w-100 mt-3">
                                    <i class="fas fa-redo"></i> Request Reset Password Baru
                                </a>
                                <a href="login.php" class="btn btn-outline-secondary btn-block w-100 mt-2">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Password Match Validation -->
    <script>
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const matchMessage = document.getElementById('password-match-message');
        const submitBtn = document.getElementById('submitBtn');

        function checkPasswordMatch() {
            if (confirmPassword.value === '') {
                matchMessage.textContent = '';
                matchMessage.className = 'text-muted';
                submitBtn.disabled = false;
                return;
            }
            
            if (newPassword.value === confirmPassword.value) {
                matchMessage.textContent = '✓ Password cocok';
                matchMessage.className = 'text-success';
                submitBtn.disabled = false;
            } else {
                matchMessage.textContent = '✗ Password tidak cocok';
                matchMessage.className = 'text-danger';
                submitBtn.disabled = true;
            }
        }

        newPassword.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
