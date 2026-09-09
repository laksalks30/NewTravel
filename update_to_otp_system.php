<?php
// Update Database - Ubah sistem Token ke OTP

include 'koneksi.php';

echo "=== UPDATE DATABASE: Sistem OTP ===\n\n";

// Drop kolom lama jika ada
$conn->query("ALTER TABLE akun DROP COLUMN IF EXISTS reset_token");
$conn->query("ALTER TABLE akun DROP COLUMN IF EXISTS reset_token_expire");

// Tambah kolom OTP baru
$sql = "ALTER TABLE akun 
        ADD COLUMN reset_otp VARCHAR(6) DEFAULT NULL AFTER background_profil,
        ADD COLUMN reset_otp_expire DATETIME DEFAULT NULL AFTER reset_otp,
        ADD COLUMN reset_otp_attempts INT DEFAULT 0 AFTER reset_otp_expire,
        ADD COLUMN reset_otp_last_attempt DATETIME DEFAULT NULL AFTER reset_otp_attempts";

if ($conn->query($sql)) {
    echo "✅ Kolom OTP berhasil ditambahkan!\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

echo "\n=== VERIFIKASI STRUKTUR TABEL ===\n";
$result = $conn->query("DESCRIBE akun");
echo "\nStruktur tabel 'akun':\n";
echo str_pad("Field", 30) . str_pad("Type", 20) . str_pad("Null", 10) . "Default\n";
echo str_repeat("-", 80) . "\n";
while ($row = $result->fetch_assoc()) {
    echo str_pad($row['Field'], 30) . 
         str_pad($row['Type'], 20) . 
         str_pad($row['Null'], 10) . 
         $row['Default'] . "\n";
}

echo "\n✅ Database berhasil di-update ke sistem OTP!\n";

$conn->close();
?>
