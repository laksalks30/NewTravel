<?php
/**
 * AUTO LOGIN DENGAN REMEMBER ME
 * Include file ini di halaman yang membutuhkan auto-login
 * Letakkan setelah config_security.php dan koneksi.php
 */

// Include koneksi database jika belum
if (!isset($conn)) {
    include_once 'koneksi.php';
}

// Cek apakah user sudah login
if (!isLoggedIn()) {
    // Cek apakah ada remember token di cookie
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        
        // Cari token di database
        $stmt = $conn->prepare("SELECT rt.user_id, rt.user_type, rt.expires_at, 
                                       a.username, a.nama_lengkap 
                                FROM remember_tokens rt 
                                LEFT JOIN akun a ON rt.user_id = a.id_akun AND rt.user_type = 'user'
                                WHERE rt.token = ? AND rt.expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            
            // Regenerate session ID
            session_regenerate_id(true);
            
            // Set session
            $_SESSION['id'] = $data['user_id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['user_type'] = $data['user_type'];
            $_SESSION['initiated'] = true;
            $_SESSION['created'] = time();
            $_SESSION['last_activity'] = time();
            $_SESSION['auto_login'] = true;
            
            // Log activity
            $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address) VALUES (?, ?, 'auto_login', 'User auto-login dengan remember token', ?)");
            $userId = $data['user_id'];
            $userType = $data['user_type'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $stmt->bind_param("iss", $userId, $userType, $ipAddress);
            $stmt->execute();
        } else {
            // Token tidak valid atau expired, hapus cookie
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
    }
}
?>
