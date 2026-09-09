<?php
include "koneksi.php";
include "config_security.php";

// ============================
// REQUEST RESET PASSWORD
// ============================
if (isset($_POST['submitForgotPassword'])) {
    $email = sanitizeInput($_POST['email']);
    
    // Cek apakah email terdaftar
    $stmt = $conn->prepare("SELECT id_akun, username, nama_lengkap FROM akun WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Generate reset token
        $resetToken = generateToken(32);
        $resetExpire = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token berlaku 1 jam
        
        // Simpan token ke database
        $stmt = $conn->prepare("UPDATE akun SET reset_token = ?, reset_token_expire = ? WHERE email = ?");
        $stmt->bind_param("sss", $resetToken, $resetExpire, $email);
        $stmt->execute();
        
        // Buat link reset password
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/Travel%20Tour%20Remake/reset_password.php?token=" . $resetToken;
        
        // KIRIM EMAIL (Simulasi - Anda perlu konfigurasi PHPMailer untuk production)
        // Untuk development, kita tampilkan link di halaman
        $emailSubject = "Reset Password - Travel Website";
        $emailMessage = "
        <html>
        <head><title>Reset Password</title></head>
        <body>
            <h2>Halo, {$user['nama_lengkap']}!</h2>
            <p>Anda menerima email ini karena ada permintaan untuk mereset password akun Anda.</p>
            <p>Klik link berikut untuk mereset password:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>Link ini berlaku selama 1 jam.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            <br>
            <p>Terima kasih,<br>Travel Website Team</p>
        </body>
        </html>
        ";
        
        // UNTUK DEVELOPMENT: Tampilkan link di console/log
        // UNTUK PRODUCTION: Gunakan PHPMailer atau mail() function
        /*
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@travelwebsite.com" . "\r\n";
        
        mail($email, $emailSubject, $emailMessage, $headers);
        */
        
        // Log activity
        $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address) VALUES (?, 'user', 'forgot_password', 'Request reset password', ?)");
        $userId = $user['id_akun'];
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("is", $userId, $ipAddress);
        $stmt->execute();
        
        // UNTUK DEVELOPMENT: Redirect dengan token untuk testing
        header("location: forgot_password.php?sukses=true&debug_token=$resetToken");
        exit();
    } else {
        // Email tidak ditemukan (tapi jangan kasih tau user untuk security)
        header("location: forgot_password.php?sukses=true");
        exit();
    }
}

// ============================
// RESET PASSWORD (dengan token)
// ============================
if (isset($_POST['submitResetPassword'])) {
    $token = sanitizeInput($_POST['token']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validasi
    if (strlen($newPassword) < 6) {
        header("location: reset_password.php?token=$token&error=Password minimal 6 karakter");
        exit();
    }
    
    if ($newPassword !== $confirmPassword) {
        header("location: reset_password.php?token=$token&error=Password tidak cocok");
        exit();
    }
    
    // Cek token valid dan belum expired
    $stmt = $conn->prepare("SELECT id_akun, username FROM akun WHERE reset_token = ? AND reset_token_expire > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Hash password baru
        $hashedPassword = hashPassword($newPassword);
        
        // Update password dan hapus token
        $stmt = $conn->prepare("UPDATE akun SET password = ?, reset_token = NULL, reset_token_expire = NULL WHERE id_akun = ?");
        $stmt->bind_param("si", $hashedPassword, $user['id_akun']);
        $stmt->execute();
        
        // Log activity
        $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address) VALUES (?, 'user', 'reset_password', 'Password berhasil direset', ?)");
        $userId = $user['id_akun'];
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("is", $userId, $ipAddress);
        $stmt->execute();
        
        header("location: login.php?resetSukses=true");
        exit();
    } else {
        header("location: reset_password.php?token=$token&error=Token tidak valid atau sudah expired");
        exit();
    }
}
?>
