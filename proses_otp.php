<?php
include "koneksi.php";
include "config_security.php";

// ============================
// REQUEST RESET PASSWORD (Generate OTP)
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
        
        // Generate OTP 6 digit
        $otp = generateOTP();
        $otpExpire = date('Y-m-d H:i:s', strtotime('+5 minutes')); // OTP berlaku 5 menit
        
        // Simpan OTP ke database
        $stmt = $conn->prepare("UPDATE akun SET reset_otp = ?, reset_otp_expire = ?, reset_otp_attempts = 0 WHERE email = ?");
        $stmt->bind_param("sss", $otp, $otpExpire, $email);
        $stmt->execute();
        
        // SIMULASI EMAIL - Tampilkan OTP di halaman
        // Untuk production, gunakan PHPMailer atau API email service (SendGrid, Mailgun, etc)
        
        /* CONTOH KIRIM EMAIL REAL (PHPMailer):
        require 'vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';
        $mail->Password = 'your-app-password';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('noreply@travelwebsite.com', 'Travel Website');
        $mail->addAddress($email, $user['nama_lengkap']);
        
        $mail->isHTML(true);
        $mail->Subject = 'Kode OTP Reset Password';
        $mail->Body = "
            <h2>Halo, {$user['nama_lengkap']}!</h2>
            <p>Kode OTP untuk reset password Anda adalah:</p>
            <h1 style='color: #667eea; letter-spacing: 5px;'>$otp</h1>
            <p>Kode ini berlaku selama <strong>5 menit</strong>.</p>
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        ";
        
        $mail->send();
        */
        
        // Log activity
        try {
            $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address) VALUES (?, 'user', 'forgot_password', 'Request OTP reset password', ?)");
            $userId = $user['id_akun'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $stmt->bind_param("is", $userId, $ipAddress);
            $stmt->execute();
        } catch (Exception $e) {
            // Silent fail
        }
        
        // Redirect ke halaman verifikasi OTP dengan simulasi OTP di URL (hanya untuk development!)
        header("location: verify_otp.php?email=" . urlencode($email) . "&otp_sent=true&debug_otp=" . $otp);
        exit();
    } else {
        // Email tidak ditemukan (tapi jangan kasih tau user untuk security)
        header("location: verify_otp.php?email=" . urlencode($email) . "&otp_sent=true");
        exit();
    }
}

// ============================
// VERIFY OTP & RESET PASSWORD
// ============================
if (isset($_POST['submitVerifyOTP'])) {
    $email = sanitizeInput($_POST['email']);
    $otpInput = sanitizeInput($_POST['otp']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validasi password
    if (strlen($newPassword) < 6) {
        header("location: verify_otp.php?email=" . urlencode($email) . "&error=" . urlencode("Password minimal 6 karakter"));
        exit();
    }
    
    if ($newPassword !== $confirmPassword) {
        header("location: verify_otp.php?email=" . urlencode($email) . "&error=" . urlencode("Password tidak cocok"));
        exit();
    }
    
    // Cek OTP valid dan belum expired
    $stmt = $conn->prepare("SELECT id_akun, username, reset_otp, reset_otp_expire, reset_otp_attempts FROM akun WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Cek apakah OTP expired
        if (strtotime($user['reset_otp_expire']) < time()) {
            header("location: verify_otp.php?email=" . urlencode($email) . "&error=" . urlencode("Kode OTP sudah expired. Silakan request ulang."));
            exit();
        }
        
        // Cek jumlah percobaan (max 5 attempts)
        if ($user['reset_otp_attempts'] >= 5) {
            // Block OTP dan hapus
            $stmt = $conn->prepare("UPDATE akun SET reset_otp = NULL, reset_otp_expire = NULL WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            header("location: verify_otp.php?email=" . urlencode($email) . "&error=" . urlencode("Terlalu banyak percobaan salah. Silakan request OTP baru."));
            exit();
        }
        
        // Verifikasi OTP
        if ($otpInput === $user['reset_otp']) {
            // OTP BENAR - Reset password
            $hashedPassword = hashPassword($newPassword);
            
            // Update password dan hapus OTP
            $stmt = $conn->prepare("UPDATE akun SET password = ?, reset_otp = NULL, reset_otp_expire = NULL, reset_otp_attempts = 0 WHERE id_akun = ?");
            $stmt->bind_param("si", $hashedPassword, $user['id_akun']);
            $stmt->execute();
            
            // Log activity
            try {
                $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address) VALUES (?, 'user', 'reset_password', 'Password berhasil direset dengan OTP', ?)");
                $userId = $user['id_akun'];
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                $stmt->bind_param("is", $userId, $ipAddress);
                $stmt->execute();
            } catch (Exception $e) {
                // Silent fail
            }
            
            header("location: login.php?resetSukses=true");
            exit();
        } else {
            // OTP SALAH - Increment attempts
            $newAttempts = $user['reset_otp_attempts'] + 1;
            $stmt = $conn->prepare("UPDATE akun SET reset_otp_attempts = ?, reset_otp_last_attempt = NOW() WHERE email = ?");
            $stmt->bind_param("is", $newAttempts, $email);
            $stmt->execute();
            
            $remainingAttempts = 5 - $newAttempts;
            header("location: verify_otp.php?email=" . urlencode($email) . "&error=" . urlencode("Kode OTP salah. Sisa percobaan: $remainingAttempts"));
            exit();
        }
    } else {
        header("location: verify_otp.php?email=" . urlencode($email) . "&error=" . urlencode("Email tidak ditemukan"));
        exit();
    }
}

// ============================
// RESEND OTP
// ============================
if (isset($_POST['resendOTP'])) {
    $email = sanitizeInput($_POST['email']);
    
    // Cek email valid
    $stmt = $conn->prepare("SELECT id_akun, nama_lengkap FROM akun WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Generate OTP baru
        $otp = generateOTP();
        $otpExpire = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        // Update OTP di database
        $stmt = $conn->prepare("UPDATE akun SET reset_otp = ?, reset_otp_expire = ?, reset_otp_attempts = 0 WHERE email = ?");
        $stmt->bind_param("sss", $otp, $otpExpire, $email);
        $stmt->execute();
        
        // Redirect dengan OTP baru (development only!)
        header("location: verify_otp.php?email=" . urlencode($email) . "&otp_sent=true&resend=true&debug_otp=" . $otp);
        exit();
    } else {
        header("location: forgot_password.php?error=" . urlencode("Email tidak ditemukan"));
        exit();
    }
}
?>
