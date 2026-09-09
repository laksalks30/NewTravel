<?php
require 'koneksi.php';

echo "==========================================\n";
echo "CEK STRUKTUR DATABASE - TABEL AKUN\n";
echo "==========================================\n\n";

// Cek struktur tabel akun
$result = $conn->query("DESCRIBE akun");

echo "Kolom di tabel akun:\n";
echo str_repeat("-", 50) . "\n";

while($row = $result->fetch_assoc()) {
    echo sprintf("%-30s %s\n", $row['Field'], $row['Type']);
}

echo "\n\n";
echo "==========================================\n";
echo "CEK DATA OTP (jika ada)\n";
echo "==========================================\n\n";

// Cek apakah ada data OTP yang aktif
$result = $conn->query("SELECT username, email, reset_otp, reset_otp_expire, reset_otp_attempts 
                        FROM akun 
                        WHERE reset_otp IS NOT NULL 
                        ORDER BY reset_otp_expire DESC 
                        LIMIT 5");

if($result->num_rows > 0) {
    echo "Data OTP yang pernah digenerate:\n";
    echo str_repeat("-", 80) . "\n";
    
    while($row = $result->fetch_assoc()) {
        echo "Username: " . $row['username'] . "\n";
        echo "Email: " . $row['email'] . "\n";
        echo "OTP Code: " . $row['reset_otp'] . "\n";
        echo "Expire: " . $row['reset_otp_expire'] . "\n";
        echo "Attempts: " . $row['reset_otp_attempts'] . "\n";
        echo str_repeat("-", 80) . "\n";
    }
} else {
    echo "Belum ada OTP yang di-generate.\n";
}

echo "\n✅ Database OTP system siap digunakan!\n";
?>
