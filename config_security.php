<?php
/**
 * CONFIG SECURITY
 * File konfigurasi untuk keamanan aplikasi
 */

// Start session dengan konfigurasi yang aman
if (session_status() === PHP_SESSION_NONE) {
    // Konfigurasi session yang aman
    ini_set('session.cookie_httponly', 1);  // Mencegah XSS attack via JavaScript
    ini_set('session.use_only_cookies', 1);  // Hanya gunakan cookies
    ini_set('session.cookie_secure', 0);     // Set ke 1 jika menggunakan HTTPS
    ini_set('session.cookie_samesite', 'Strict'); // Mencegah CSRF attack
    
    // Session timeout (30 menit = 1800 detik)
    ini_set('session.gc_maxlifetime', 1800);
    
    session_start();
    
    // Regenerate session ID untuk mencegah session fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
        $_SESSION['created'] = time();
    }
    
    // Check session timeout (30 menit)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        // Session expired
        session_unset();
        session_destroy();
        header("Location: login.php?session=expired");
        exit();
    }
    
    // Update last activity time
    $_SESSION['last_activity'] = time();
    
    // Regenerate session ID setiap 5 menit untuk keamanan ekstra
    if (isset($_SESSION['created']) && (time() - $_SESSION['created'] > 300)) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

/**
 * Fungsi untuk cek apakah user sudah login
 */
function isLoggedIn() {
    return isset($_SESSION['id']) && isset($_SESSION['user_type']);
}

/**
 * Fungsi untuk cek apakah user adalah admin
 */
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Fungsi untuk memaksa user login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php?require=login");
        exit();
    }
}

/**
 * Fungsi untuk memaksa user adalah admin
 */
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: loginAdmin.php?require=admin");
        exit();
    }
}

/**
 * Fungsi untuk sanitize input
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Fungsi untuk validasi email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Fungsi untuk generate random token (untuk reset password, remember me, dll)
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Fungsi untuk generate OTP 6 digit
 */
function generateOTP() {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Fungsi untuk hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Fungsi untuk verify password
 */
function verifyPassword($password, $hash) {
    if (password_verify($password, $hash)) {
        return true;
    }
    if ($password === $hash) {
        return true;
    }
    if (md5($password) === $hash) {
        return true;
    }
    return false;
}

/**
 * Fungsi untuk log activity
 */
function logActivity($conn, $userId, $userType, $action, $description) {
    try {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("isssss", $userId, $userType, $action, $description, $ipAddress, $userAgent);
            $stmt->execute();
            $stmt->close();
        }
    } catch (Exception $e) {
        // Silently fail jika tabel activity_log belum ada
        // Untuk production, log error ke file
    }
}
?>
