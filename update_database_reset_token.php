<?php
// Update Database - Tambah kolom reset_token

include 'koneksi.php';

echo "=== UPDATE DATABASE: Tambah Kolom Reset Token ===\n\n";

// Cek apakah kolom sudah ada
$check = $conn->query("SHOW COLUMNS FROM akun LIKE 'reset_token'");
if ($check->num_rows > 0) {
    echo "❌ Kolom 'reset_token' sudah ada di tabel akun.\n";
} else {
    // Tambah kolom reset_token
    $sql1 = "ALTER TABLE akun ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL AFTER background_profil";
    if ($conn->query($sql1)) {
        echo "✅ Kolom 'reset_token' berhasil ditambahkan!\n";
    } else {
        echo "❌ Error menambah reset_token: " . $conn->error . "\n";
    }
}

// Cek apakah kolom sudah ada
$check2 = $conn->query("SHOW COLUMNS FROM akun LIKE 'reset_token_expire'");
if ($check2->num_rows > 0) {
    echo "❌ Kolom 'reset_token_expire' sudah ada di tabel akun.\n";
} else {
    // Tambah kolom reset_token_expire
    $sql2 = "ALTER TABLE akun ADD COLUMN reset_token_expire DATETIME DEFAULT NULL AFTER reset_token";
    if ($conn->query($sql2)) {
        echo "✅ Kolom 'reset_token_expire' berhasil ditambahkan!\n";
    } else {
        echo "❌ Error menambah reset_token_expire: " . $conn->error . "\n";
    }
}

echo "\n=== VERIFIKASI STRUKTUR TABEL ===\n";
$result = $conn->query("DESCRIBE akun");
echo "\nStruktur tabel 'akun':\n";
echo str_pad("Field", 25) . str_pad("Type", 20) . str_pad("Null", 10) . "Key\n";
echo str_repeat("-", 70) . "\n";
while ($row = $result->fetch_assoc()) {
    echo str_pad($row['Field'], 25) . 
         str_pad($row['Type'], 20) . 
         str_pad($row['Null'], 10) . 
         $row['Key'] . "\n";
}

echo "\n✅ Database berhasil di-update!\n";
echo "Sekarang fitur 'Forgot Password' sudah bisa digunakan.\n";

$conn->close();
?>
