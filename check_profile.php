<?php
// File untuk mengecek dan memperbaiki database untuk fitur Profile User
include 'koneksi.php';

echo "<h2>🔧 Check & Fix Database untuk Profile User</h2>";

// 1. Cek apakah kolom email sudah ada
echo "<h3>1. Checking kolom email...</h3>";
$result = mysqli_query($conn, "SHOW COLUMNS FROM akun LIKE 'email'");
if (mysqli_num_rows($result) == 0) {
    echo "❌ Kolom email BELUM ADA<br>";
    echo "⚙️ Menambahkan kolom email...<br>";
    
    $sql = "ALTER TABLE akun ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER username";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Kolom email berhasil ditambahkan!<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "✅ Kolom email sudah ada<br>";
}

// 2. Cek apakah kolom foto_profil sudah ada
echo "<h3>2. Checking kolom foto_profil...</h3>";
$result = mysqli_query($conn, "SHOW COLUMNS FROM akun LIKE 'foto_profil'");
if (mysqli_num_rows($result) == 0) {
    echo "❌ Kolom foto_profil BELUM ADA<br>";
    echo "⚙️ Menambahkan kolom foto_profil...<br>";
    
    $sql = "ALTER TABLE akun ADD COLUMN foto_profil VARCHAR(255) DEFAULT 'images/profile/default.jpg' AFTER password";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Kolom foto_profil berhasil ditambahkan!<br>";
    } else {
        echo "❌ Error: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "✅ Kolom foto_profil sudah ada<br>";
}

// 3. Cek folder profile
echo "<h3>3. Checking folder images/profile/...</h3>";
if (!file_exists('images/profile')) {
    echo "❌ Folder images/profile/ BELUM ADA<br>";
    echo "⚙️ Membuat folder...<br>";
    mkdir('images/profile', 0777, true);
    echo "✅ Folder berhasil dibuat!<br>";
} else {
    echo "✅ Folder images/profile/ sudah ada<br>";
}

// 4. Cek file default.jpg
echo "<h3>4. Checking file default.jpg...</h3>";
if (!file_exists('images/profile/default.jpg')) {
    echo "❌ File default.jpg BELUM ADA<br>";
    echo "⚙️ Membuat placeholder file...<br>";
    // Create a simple placeholder
    file_put_contents('images/profile/default.jpg', '<!-- Placeholder - replace with real image -->');
    echo "✅ File placeholder dibuat! (Ganti dengan foto real nanti)<br>";
} else {
    echo "✅ File default.jpg sudah ada<br>";
}

// 5. Test query profile
echo "<h3>5. Testing query profile...</h3>";
$test_sql = "SELECT id_akun, username, nama_lengkap, email, foto_profil FROM akun LIMIT 1";
$result = mysqli_query($conn, $test_sql);
if ($result) {
    echo "✅ Query profile berfungsi dengan baik<br>";
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    }
} else {
    echo "❌ Error query: " . mysqli_error($conn) . "<br>";
}

// 6. Cek file-file profile
echo "<h3>6. Checking file-file profile...</h3>";
$files = [
    'profile.php' => 'Halaman view profile',
    'edit_profile.php' => 'Halaman edit profile',
    'change_password.php' => 'Halaman ganti password',
    'proses_profile.php' => 'Backend processing'
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        echo "✅ $file - $desc<br>";
    } else {
        echo "❌ $file - $desc <strong>TIDAK DITEMUKAN!</strong><br>";
    }
}

echo "<hr>";
echo "<h3>✅ SEMUA PENGECEKAN SELESAI!</h3>";
echo "<p><a href='profile.php'>🔗 Test Profile Page</a></p>";
echo "<p><a href='index.php'>🏠 Kembali ke Home</a></p>";
?>
