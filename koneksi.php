<?php
$is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || php_sapi_name() === 'cli' || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;

if ($is_local) {
    // Koneksi Localhost (XAMPP / PHP Built-in Server)
    $conn = mysqli_connect('localhost', 'root', '', 'travel_db');
} else {
    // Koneksi Hosting InfinityFree (Production Online)
    $conn = mysqli_connect('sql104.infinityfree.com', 'if0_42872968', 'Kjt2GmxJMsDg7O', 'if0_42872968_travel_db');
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>