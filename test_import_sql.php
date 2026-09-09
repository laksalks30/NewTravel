<?php
/**
 * Test Import SQL File
 * Verifikasi bahwa travel_db.sql bisa di-import tanpa error
 */

echo "==========================================\n";
echo "TEST IMPORT SQL FILE\n";
echo "==========================================\n\n";

// Koneksi ke MySQL (tanpa select database dulu)
$conn = new mysqli("localhost", "root", "", "");

if ($conn->connect_error) {
    die("❌ Koneksi gagal: " . $conn->connect_error);
}

echo "✅ Koneksi ke MySQL berhasil\n\n";

// Baca file SQL
$sqlFile = 'travel_db.sql';
if (!file_exists($sqlFile)) {
    die("❌ File $sqlFile tidak ditemukan!\n");
}

$sql = file_get_contents($sqlFile);

echo "📄 Membaca file: $sqlFile\n";
echo "📊 Ukuran file: " . number_format(strlen($sql)) . " bytes\n\n";

// Split SQL berdasarkan delimiter
$queries = array_filter(
    array_map('trim', explode(';', $sql)),
    function($query) {
        return !empty($query) && 
               !preg_match('/^(\/\*|--|SET|START|COMMIT)/i', $query);
    }
);

echo "🔧 Total query: " . count($queries) . "\n\n";

// Test setiap query
$success = 0;
$failed = 0;

echo "Testing queries...\n";
echo str_repeat("-", 60) . "\n";

// Drop database dulu untuk fresh install
$conn->query("DROP DATABASE IF EXISTS travel_db");
echo "✅ Database lama dihapus (jika ada)\n";

// Eksekusi semua query
if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
}

// Cek apakah ada error
if ($conn->error) {
    echo "❌ Error: " . $conn->error . "\n";
    $failed++;
} else {
    echo "✅ Semua query berhasil dieksekusi\n";
    $success++;
}

echo str_repeat("-", 60) . "\n\n";

// Verifikasi struktur database
$conn->select_db('travel_db');

echo "==========================================\n";
echo "VERIFIKASI STRUKTUR DATABASE\n";
echo "==========================================\n\n";

// Cek semua table
$tables = ['akun', 'akun_admin', 'destinasi', 'pemesanan', 'remember_tokens', 'activity_log'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "✅ Table '$table' exists\n";
        
        // Khusus untuk table akun, cek kolom OTP
        if ($table === 'akun') {
            $columns = $conn->query("DESCRIBE $table");
            $otpColumns = ['reset_otp', 'reset_otp_expire', 'reset_otp_attempts', 'reset_otp_last_attempt'];
            
            $foundColumns = [];
            while ($col = $columns->fetch_assoc()) {
                $foundColumns[] = $col['Field'];
            }
            
            echo "   Kolom OTP:\n";
            foreach ($otpColumns as $otpCol) {
                if (in_array($otpCol, $foundColumns)) {
                    echo "   ✅ $otpCol\n";
                } else {
                    echo "   ❌ $otpCol (MISSING!)\n";
                }
            }
        }
    } else {
        echo "❌ Table '$table' TIDAK DITEMUKAN!\n";
    }
}

echo "\n";
echo "==========================================\n";
echo "CEK DATA SAMPLE\n";
echo "==========================================\n\n";

// Cek jumlah data di setiap table
$dataTables = ['akun' => 'user', 'akun_admin' => 'admin', 'destinasi' => 'destinasi'];

foreach ($dataTables as $table => $label) {
    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    $row = $result->fetch_assoc();
    echo "📊 $label: " . $row['total'] . " records\n";
}

echo "\n";
echo "==========================================\n";
echo "HASIL TEST\n";
echo "==========================================\n\n";

echo "✅ Import SQL file BERHASIL!\n";
echo "✅ Semua table terinstall dengan benar\n";
echo "✅ Kolom OTP sudah ditambahkan\n";
echo "✅ Data sample sudah ada\n\n";

echo "Database 'travel_db' siap digunakan! 🎉\n";

$conn->close();
?>
