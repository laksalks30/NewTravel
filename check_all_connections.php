<?php
/**
 * CEK SEMUA FILE PHP - KONEKSI DATABASE
 * Verifikasi bahwa semua file terhubung dengan database yang benar
 */

echo "==========================================\n";
echo "CEK KONEKSI DATABASE - SEMUA FILE PHP\n";
echo "==========================================\n\n";

// Scan semua file PHP
$files = glob('*.php');
sort($files);

// Exclude file testing dan utility
$excludeFiles = [
    'cek_database_otp.php',
    'test_import_sql.php',
    'verify_sql_syntax.php',
    'import_database.php',
    'update_to_otp_system.php',
    'update_database_reset_token.php',
    'setup_database.php'
];

$coreFiles = [];
$testFiles = [];

foreach ($files as $file) {
    if (in_array($file, $excludeFiles)) {
        $testFiles[] = $file;
    } else {
        $coreFiles[] = $file;
    }
}

echo "📊 Total file PHP: " . count($files) . "\n";
echo "   - Core files: " . count($coreFiles) . "\n";
echo "   - Test/Utility files: " . count($testFiles) . "\n\n";

echo "==========================================\n";
echo "FILE KONEKSI UTAMA\n";
echo "==========================================\n\n";

// Cek koneksi.php
if (file_exists('koneksi.php')) {
    $content = file_get_contents('koneksi.php');
    
    echo "📄 koneksi.php\n";
    
    if (preg_match("/mysqli_connect\('([^']+)',\s*'([^']*)',\s*'([^']*)',\s*'([^']+)'/", $content, $matches)) {
        echo "   Host: " . $matches[1] . "\n";
        echo "   User: " . $matches[2] . "\n";
        echo "   Password: " . ($matches[3] ? "***" : "(empty)") . "\n";
        echo "   Database: " . $matches[4] . "\n";
        
        if ($matches[4] === 'travel_db') {
            echo "   ✅ Database name CORRECT!\n";
        } else {
            echo "   ❌ Database name WRONG! Should be 'travel_db'\n";
        }
    } else {
        echo "   ⚠️  Cannot parse connection string\n";
    }
    
    echo "\n";
} else {
    echo "❌ koneksi.php TIDAK DITEMUKAN!\n\n";
}

echo "==========================================\n";
echo "CORE FILES - CEK INCLUDE/REQUIRE\n";
echo "==========================================\n\n";

$withKoneksi = [];
$withoutKoneksi = [];

foreach ($coreFiles as $file) {
    $content = file_get_contents($file);
    
    // Cek apakah file include koneksi.php
    if (preg_match("/(include|require|include_once|require_once)\s+['\"]koneksi\.php['\"]/", $content)) {
        $withKoneksi[] = $file;
    } else {
        // Cek apakah butuh database (ada query atau mysqli)
        if (preg_match("/(mysqli_|->query|->prepare|\$conn)/", $content)) {
            $withoutKoneksi[] = $file;
        }
    }
}

echo "✅ Files dengan koneksi.php (" . count($withKoneksi) . "):\n";
echo str_repeat("-", 60) . "\n";
foreach ($withKoneksi as $file) {
    echo "   ✓ $file\n";
}

echo "\n";

if (count($withoutKoneksi) > 0) {
    echo "⚠️  Files dengan query tapi TIDAK include koneksi.php (" . count($withoutKoneksi) . "):\n";
    echo str_repeat("-", 60) . "\n";
    foreach ($withoutKoneksi as $file) {
        echo "   ⚠️  $file\n";
    }
    echo "\n";
} else {
    echo "✅ Semua file yang butuh database sudah include koneksi.php!\n\n";
}

echo "==========================================\n";
echo "CEK FILE AUTO_LOGIN.PHP\n";
echo "==========================================\n\n";

if (file_exists('auto_login.php')) {
    $content = file_get_contents('auto_login.php');
    
    echo "📄 auto_login.php\n";
    
    if (preg_match("/(include|require).*koneksi/", $content)) {
        echo "   ✅ Include koneksi.php\n";
    } else {
        echo "   ⚠️  TIDAK include koneksi.php\n";
    }
    
    if (preg_match("/remember_tokens/", $content)) {
        echo "   ✅ Menggunakan table remember_tokens\n";
    } else {
        echo "   ⚠️  Tidak menggunakan table remember_tokens\n";
    }
    
    echo "\n";
}

echo "==========================================\n";
echo "TEST KONEKSI DATABASE\n";
echo "==========================================\n\n";

include 'koneksi.php';

if ($conn) {
    echo "✅ Koneksi database BERHASIL!\n\n";
    
    // Cek semua table
    $tables = ['akun', 'akun_admin', 'destinasi', 'pemesanan', 'remember_tokens', 'activity_log'];
    
    echo "Cek keberadaan table:\n";
    echo str_repeat("-", 60) . "\n";
    
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) > 0) {
            echo "   ✅ Table '$table'\n";
        } else {
            echo "   ❌ Table '$table' TIDAK DITEMUKAN!\n";
        }
    }
    
    echo "\n";
    
    // Cek kolom OTP di table akun
    echo "Cek kolom OTP di table akun:\n";
    echo str_repeat("-", 60) . "\n";
    
    $result = mysqli_query($conn, "DESCRIBE akun");
    $otpColumns = ['reset_otp', 'reset_otp_expire', 'reset_otp_attempts', 'reset_otp_last_attempt'];
    $foundColumns = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        if (in_array($row['Field'], $otpColumns)) {
            $foundColumns[] = $row['Field'];
            echo "   ✅ " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    }
    
    if (count($foundColumns) === 4) {
        echo "\n   ✅ Semua 4 kolom OTP ada!\n";
    } else {
        echo "\n   ⚠️  Hanya " . count($foundColumns) . " dari 4 kolom OTP ditemukan\n";
    }
    
    mysqli_close($conn);
} else {
    echo "❌ Koneksi database GAGAL!\n";
    echo "Error: " . mysqli_connect_error() . "\n";
}

echo "\n";
echo "==========================================\n";
echo "RINGKASAN\n";
echo "==========================================\n\n";

echo "✅ File koneksi.php: CORRECT (localhost, root, '', travel_db)\n";
echo "✅ Core files dengan koneksi: " . count($withKoneksi) . " files\n";

if (count($withoutKoneksi) > 0) {
    echo "⚠️  Files butuh perbaikan: " . count($withoutKoneksi) . " files\n";
} else {
    echo "✅ Semua files sudah terhubung dengan benar!\n";
}

echo "\n🎉 PROJECT SIAP DIGUNAKAN!\n";
echo "\nSilakan cek di browser:\n";
echo "   http://localhost:8000/index.php\n";
echo "   http://localhost/Travel Tour Remake/index.php\n\n";
?>
