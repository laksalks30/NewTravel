<?php
/**
 * Verifikasi Syntax SQL - travel_db.sql
 * Cek apakah kolom OTP sudah ada di file SQL
 */

echo "==========================================\n";
echo "VERIFIKASI SYNTAX SQL FILE\n";
echo "==========================================\n\n";

$sqlFile = 'travel_db.sql';

if (!file_exists($sqlFile)) {
    die("❌ File $sqlFile tidak ditemukan!\n");
}

$content = file_get_contents($sqlFile);

echo "📄 File: $sqlFile\n";
echo "📊 Ukuran: " . number_format(strlen($content)) . " bytes\n\n";

// Cek apakah semua kolom OTP ada di CREATE TABLE akun
$requiredColumns = [
    'reset_otp' => 'varchar(6)',
    'reset_otp_expire' => 'datetime',
    'reset_otp_attempts' => 'int(11)',
    'reset_otp_last_attempt' => 'datetime'
];

echo "==========================================\n";
echo "CEK KOLOM OTP DI TABLE `akun`\n";
echo "==========================================\n\n";

$allFound = true;

foreach ($requiredColumns as $column => $type) {
    // Cek apakah kolom ada di file
    $pattern = "/`$column`\s+$type/i";
    if (preg_match($pattern, $content)) {
        echo "✅ Kolom `$column` ($type) - FOUND\n";
    } else {
        echo "❌ Kolom `$column` ($type) - NOT FOUND!\n";
        $allFound = false;
    }
}

echo "\n";

// Cek apakah ada CREATE TABLE akun
if (preg_match('/CREATE TABLE `akun`/i', $content)) {
    echo "✅ CREATE TABLE `akun` - FOUND\n";
} else {
    echo "❌ CREATE TABLE `akun` - NOT FOUND!\n";
    $allFound = false;
}

// Cek semua table yang harus ada
$requiredTables = ['akun', 'akun_admin', 'destinasi', 'pemesanan', 'remember_tokens', 'activity_log'];

echo "\n";
echo "==========================================\n";
echo "CEK SEMUA TABLE\n";
echo "==========================================\n\n";

foreach ($requiredTables as $table) {
    if (preg_match("/CREATE TABLE `$table`/i", $content)) {
        echo "✅ Table `$table` - FOUND\n";
    } else {
        echo "❌ Table `$table` - NOT FOUND!\n";
        $allFound = false;
    }
}

echo "\n";
echo "==========================================\n";
echo "CEK DATA SAMPLE\n";
echo "==========================================\n\n";

// Cek INSERT statements
$insertTables = ['akun', 'akun_admin', 'destinasi'];

foreach ($insertTables as $table) {
    if (preg_match("/INSERT INTO `$table`/i", $content)) {
        echo "✅ Data sample untuk `$table` - FOUND\n";
    } else {
        echo "⚠️  Data sample untuk `$table` - NOT FOUND\n";
    }
}

echo "\n";
echo "==========================================\n";
echo "HASIL VERIFIKASI\n";
echo "==========================================\n\n";

if ($allFound) {
    echo "✅ SEMUA KOLOM OTP SUDAH ADA!\n";
    echo "✅ SEMUA TABLE LENGKAP!\n";
    echo "✅ File SQL siap untuk di-import!\n\n";
    
    echo "Cara import:\n";
    echo "1. Buka phpMyAdmin: http://localhost/phpmyadmin\n";
    echo "2. Klik tab 'Import'\n";
    echo "3. Choose file: travel_db.sql\n";
    echo "4. Klik 'Import'\n\n";
    
    echo "Atau via command line:\n";
    echo "mysql -u root < travel_db.sql\n\n";
    
    echo "🎉 File SQL sudah UPDATE dengan sistem OTP!\n";
} else {
    echo "❌ Ada kolom/table yang missing!\n";
    echo "⚠️  Silakan cek file SQL secara manual.\n";
}

echo "\n";

// Ekstrak dan tampilkan CREATE TABLE akun untuk preview
echo "==========================================\n";
echo "PREVIEW: CREATE TABLE akun\n";
echo "==========================================\n\n";

if (preg_match('/CREATE TABLE `akun`\s*\((.*?)\)\s*ENGINE/is', $content, $matches)) {
    $tableContent = $matches[1];
    
    // Split by comma and show each column
    $lines = array_filter(array_map('trim', explode("\n", $tableContent)));
    
    foreach ($lines as $line) {
        // Highlight OTP columns
        if (preg_match('/reset_otp/', $line)) {
            echo "🔸 $line\n";
        } else {
            echo "  $line\n";
        }
    }
}

echo "\n";
?>
