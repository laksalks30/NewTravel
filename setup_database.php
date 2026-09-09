<?php
/**
 * SETUP DATABASE - Jalankan file ini sekali untuk setup database
 * Buka: http://localhost:8000/setup_database.php
 */

include "koneksi.php";

echo "<h2>🔧 Setup Database - Travel Tour Website</h2>";
echo "<hr>";

// Array untuk menyimpan hasil
$results = [];

// 1. Update tabel akun - Tambah kolom email dan ubah password length
echo "<h3>1. Update Tabel AKUN</h3>";
$sql = "SHOW COLUMNS FROM `akun` LIKE 'email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // Kolom email belum ada, tambahkan
    $sql1 = "ALTER TABLE `akun` ADD COLUMN `email` VARCHAR(100) NULL AFTER `username`";
    if (mysqli_query($conn, $sql1)) {
        echo "✅ Kolom 'email' berhasil ditambahkan<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "✅ Kolom 'email' sudah ada<br>";
}

// Cek kolom reset_token
$sql = "SHOW COLUMNS FROM `akun` LIKE 'reset_token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $sql2 = "ALTER TABLE `akun` ADD COLUMN `reset_token` VARCHAR(100) NULL";
    $sql3 = "ALTER TABLE `akun` ADD COLUMN `reset_token_expire` DATETIME NULL";
    
    if (mysqli_query($conn, $sql2)) {
        echo "✅ Kolom 'reset_token' berhasil ditambahkan<br>";
    }
    if (mysqli_query($conn, $sql3)) {
        echo "✅ Kolom 'reset_token_expire' berhasil ditambahkan<br>";
    }
} else {
    echo "✅ Kolom 'reset_token' sudah ada<br>";
}

// Update password column length
$sql4 = "ALTER TABLE `akun` MODIFY COLUMN `password` VARCHAR(255) NOT NULL";
if (mysqli_query($conn, $sql4)) {
    echo "✅ Kolom 'password' berhasil diupdate ke VARCHAR(255)<br>";
} else {
    echo "⚠️ Password column: " . mysqli_error($conn) . "<br>";
}

echo "<hr>";

// 2. Update tabel akun_admin
echo "<h3>2. Update Tabel AKUN_ADMIN</h3>";
$sql = "SHOW COLUMNS FROM `akun_admin` LIKE 'email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $sql5 = "ALTER TABLE `akun_admin` ADD COLUMN `email` VARCHAR(100) NULL AFTER `username`";
    if (mysqli_query($conn, $sql5)) {
        echo "✅ Kolom 'email' berhasil ditambahkan<br>";
    }
} else {
    echo "✅ Kolom 'email' sudah ada<br>";
}

$sql6 = "ALTER TABLE `akun_admin` MODIFY COLUMN `password` VARCHAR(255) NOT NULL";
if (mysqli_query($conn, $sql6)) {
    echo "✅ Kolom 'password' berhasil diupdate ke VARCHAR(255)<br>";
} else {
    echo "⚠️ Password column: " . mysqli_error($conn) . "<br>";
}

echo "<hr>";

// 3. Buat tabel remember_tokens
echo "<h3>3. Buat Tabel REMEMBER_TOKENS</h3>";
$sql7 = "CREATE TABLE IF NOT EXISTS `remember_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `user_type` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`, `user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($conn, $sql7)) {
    echo "✅ Tabel 'remember_tokens' berhasil dibuat<br>";
} else {
    echo "⚠️ " . mysqli_error($conn) . "<br>";
}

echo "<hr>";

// 4. Buat tabel activity_log
echo "<h3>4. Buat Tabel ACTIVITY_LOG</h3>";
$sql8 = "CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL,
  `user_type` ENUM('user', 'admin', 'guest') DEFAULT 'guest',
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`, `user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($conn, $sql8)) {
    echo "✅ Tabel 'activity_log' berhasil dibuat<br>";
} else {
    echo "⚠️ " . mysqli_error($conn) . "<br>";
}

echo "<hr>";

// 5. Update password admin menjadi hash
echo "<h3>5. Update Password Admin</h3>";
$hashedPassword = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);

// Cek apakah password sudah di-hash
$sql = "SELECT password FROM akun_admin WHERE username = 'admin'";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (strlen($row['password']) < 60) {
        // Password masih plain text, update
        $sql9 = "UPDATE `akun_admin` SET `password` = '$hashedPassword' WHERE `username` = 'admin'";
        if (mysqli_query($conn, $sql9)) {
            echo "✅ Password admin berhasil di-hash<br>";
            echo "📝 Login admin dengan:<br>";
            echo "&nbsp;&nbsp;&nbsp;Username: <strong>admin</strong><br>";
            echo "&nbsp;&nbsp;&nbsp;Password: <strong>admin123</strong><br>";
        } else {
            echo "⚠️ " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "✅ Password admin sudah di-hash<br>";
        echo "📝 Login admin dengan:<br>";
        echo "&nbsp;&nbsp;&nbsp;Username: <strong>admin</strong><br>";
        echo "&nbsp;&nbsp;&nbsp;Password: <strong>admin123</strong><br>";
    }
} else {
    echo "⚠️ Admin tidak ditemukan di database<br>";
}

echo "<hr>";

// Test koneksi
echo "<h3>6. Test Koneksi Database</h3>";
if ($conn) {
    echo "✅ Koneksi database berhasil<br>";
    echo "📊 Database: " . mysqli_get_host_info($conn) . "<br>";
} else {
    echo "❌ Koneksi database gagal<br>";
}

echo "<hr>";
echo "<h2>✅ SETUP DATABASE SELESAI!</h2>";
echo "<p>Sekarang Anda bisa:</p>";
echo "<ul>";
echo "<li>✅ Register user baru di <a href='register.php'>register.php</a></li>";
echo "<li>✅ Login user di <a href='login.php'>login.php</a></li>";
echo "<li>✅ Login admin di <a href='loginAdmin.php'>loginAdmin.php</a> (admin/admin123)</li>";
echo "<li>✅ Test fitur lupa password di <a href='forgot_password.php'>forgot_password.php</a></li>";
echo "</ul>";

echo "<p><strong>⚠️ PENTING:</strong> Hapus file ini setelah setup selesai untuk keamanan!</p>";

mysqli_close($conn);
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}
h2 {
    color: #333;
    border-bottom: 3px solid #ffc107;
    padding-bottom: 10px;
}
h3 {
    color: #555;
    margin-top: 20px;
}
hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 20px 0;
}
a {
    color: #ffc107;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    text-decoration: underline;
}
</style>
