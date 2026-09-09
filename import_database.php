<?php
/**
 * Import Database travel_db.sql
 * Via PHP (untuk file besar dengan BLOB)
 */

echo "==========================================\n";
echo "IMPORT DATABASE: travel_db.sql\n";
echo "==========================================\n\n";

// Koneksi tanpa select database
$conn = new mysqli("localhost", "root", "");

if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}

echo "✅ Koneksi ke MySQL berhasil\n\n";

// Set max_allowed_packet lebih besar
$conn->query("SET GLOBAL max_allowed_packet=67108864"); // 64MB
echo "✅ max_allowed_packet di-set ke 64MB\n\n";

// Baca dan eksekusi SQL file menggunakan mysql command
$sqlFile = realpath('travel_db.sql');
$mysqlPath = 'c:\\xampp\\mysql\\bin\\mysql.exe';

if (!file_exists($mysqlPath)) {
    echo "⚠️  MySQL executable tidak ditemukan di: $mysqlPath\n";
    echo "📝 Silakan import manual via phpMyAdmin:\n";
    echo "   1. Buka: http://localhost/phpmyadmin\n";
    echo "   2. Klik tab 'Import'\n";
    echo "   3. Pilih file: travel_db.sql\n";
    echo "   4. Klik 'Import'\n\n";
    exit;
}

echo "📄 Importing file: travel_db.sql\n";
echo "⏳ Mohon tunggu...\n\n";

// Execute mysql import
$command = "\"$mysqlPath\" -u root < \"$sqlFile\"";
exec($command, $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ Import berhasil!\n\n";
    
    // Verifikasi
    $conn->select_db('travel_db');
    
    echo "==========================================\n";
    echo "VERIFIKASI DATABASE\n";
    echo "==========================================\n\n";
    
    // Cek table akun
    $result = $conn->query("DESCRIBE akun");
    
    echo "Struktur table akun:\n";
    echo str_repeat("-", 50) . "\n";
    
    $otpColumns = ['reset_otp', 'reset_otp_expire', 'reset_otp_attempts', 'reset_otp_last_attempt'];
    $foundOTP = 0;
    
    while ($row = $result->fetch_assoc()) {
        $isOTP = in_array($row['Field'], $otpColumns);
        
        if ($isOTP) {
            echo "🔸 " . sprintf("%-30s %s\n", $row['Field'], $row['Type']);
            $foundOTP++;
        } else {
            echo "  " . sprintf("%-30s %s\n", $row['Field'], $row['Type']);
        }
    }
    
    echo "\n";
    
    if ($foundOTP === 4) {
        echo "✅ Semua 4 kolom OTP berhasil ditambahkan!\n";
    } else {
        echo "⚠️  Hanya $foundOTP dari 4 kolom OTP yang ditemukan\n";
    }
    
    echo "\n🎉 Database travel_db siap digunakan!\n";
    
} else {
    echo "❌ Import gagal!\n";
    echo "Error code: $returnCode\n\n";
    echo "Silakan import manual via phpMyAdmin.\n";
}

$conn->close();
?>
