<?php
include 'koneksi.php';

echo "==========================================\n";
echo "CEK RELASI ANTAR TABEL\n";
echo "==========================================\n\n";

// Cek foreign keys di table pemesanan
$sql = "SHOW CREATE TABLE pemesanan";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

echo "Table: pemesanan\n";
echo str_repeat("-", 60) . "\n";
echo $row[1] . "\n\n";

// Cek foreign keys di table remember_tokens
$sql = "SHOW CREATE TABLE remember_tokens";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

echo "Table: remember_tokens\n";
echo str_repeat("-", 60) . "\n";
echo $row[1] . "\n\n";

// Cek foreign keys di table activity_log
$sql = "SHOW CREATE TABLE activity_log";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

echo "Table: activity_log\n";
echo str_repeat("-", 60) . "\n";
echo $row[1] . "\n\n";

echo "==========================================\n";
echo "ANALISIS RELASI\n";
echo "==========================================\n\n";

// Dari diagram yang kamu tunjukkan:
echo "Relasi yang SEHARUSNYA ada:\n";
echo "1. pemesanan.id_akun → akun.id_akun (FK)\n";
echo "2. pemesanan.id_destinasi → destinasi.id_destinasi (FK)\n";
echo "3. remember_tokens.user_id → akun.id_akun (optional FK)\n";
echo "4. activity_log.user_id → akun.id_akun atau akun_admin.id_admin (optional)\n\n";

echo "Cek apakah FK sudah ada:\n";
echo str_repeat("-", 60) . "\n";

// Cek FK di pemesanan
$sql = "SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'travel_db' 
AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY TABLE_NAME";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "✅ {$row['TABLE_NAME']}.{$row['COLUMN_NAME']} → {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}\n";
    }
} else {
    echo "❌ TIDAK ADA FOREIGN KEY!\n";
}

mysqli_close($conn);
?>
