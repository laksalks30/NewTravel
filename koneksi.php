<?php
// Pengaturan error handling
ini_set('display_errors', 0);
error_reporting(0);

mysqli_report(MYSQLI_REPORT_OFF);

$is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || php_sapi_name() === 'cli' || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;

if ($is_local) {
    // Koneksi Localhost (XAMPP / PHP Server)
    $conn = @mysqli_connect('localhost', 'root', '', 'travel_db');
} else {
    // Koneksi Hosting InfinityFree (Production Online)
    $conn = @mysqli_connect('sql104.infinityfree.com', 'if0_42872968', 'Laksa123456', 'if0_42872968_travel_db');
}

if (!$conn) {
    die("<div style='font-family:sans-serif;padding:30px;background:#fee2e2;color:#991b1b;border-radius:12px;margin:50px auto;max-width:600px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
        <h3 style='margin-top:0;'>Koneksi Database Gagal</h3>
        <p style='font-weight:bold;'>" . mysqli_connect_error() . "</p>
    </div>");
}
?>