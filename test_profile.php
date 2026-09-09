<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Profile User - Travel Tour</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .step {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .step h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .step p {
            color: #666;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px 5px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
        }
        .success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        .info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }
        .code {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            overflow-x: auto;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        ul {
            margin: 10px 0 10px 30px;
            color: #666;
        }
        ul li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Fitur Profile User</h1>
        <p style="color: #666; margin-bottom: 30px;">Ikuti langkah-langkah berikut untuk test fitur profile</p>

        <!-- Step 1 -->
        <div class="step warning">
            <h3>⚙️ STEP 1: Setup Database</h3>
            <p>Jalankan setup untuk memastikan database siap:</p>
            <div style="margin-top: 15px;">
                <a href="check_profile.php" class="btn" target="_blank">🔧 Jalankan Setup Database</a>
            </div>
            <p style="margin-top: 15px; font-size: 14px;">
                ✅ Pastikan semua menunjukkan centang hijau sebelum lanjut ke step berikutnya
            </p>
        </div>

        <!-- Step 2 -->
        <div class="step info">
            <h3>🔐 STEP 2: Login ke Sistem</h3>
            <p>Login dengan salah satu user berikut:</p>
            <div class="code">
                <strong>User 1:</strong><br>
                Username: amba<br>
                Password: 123
            </div>
            <p style="margin-top: 10px;">Atau register user baru terlebih dahulu</p>
            <div style="margin-top: 15px;">
                <a href="login.php" class="btn">🔑 Login</a>
                <a href="register.php" class="btn btn-secondary">📝 Register</a>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="step success">
            <h3>👤 STEP 3: Test View Profile</h3>
            <p>Setelah login, klik menu "Profile" di navbar untuk melihat halaman profile Anda</p>
            <ul>
                <li>✅ Harus tampil foto profil (default)</li>
                <li>✅ Harus tampil nama lengkap</li>
                <li>✅ Harus tampil username</li>
                <li>✅ Harus tampil email (jika ada)</li>
                <li>✅ Harus tampil riwayat booking (jika ada)</li>
            </ul>
            <div style="margin-top: 15px;">
                <a href="profile.php" class="btn">👤 Lihat Profile</a>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="step">
            <h3>✏️ STEP 4: Test Edit Profile</h3>
            <p>Dari halaman profile, klik tombol "Edit Profil" dan coba:</p>
            <ul>
                <li>Ubah nama lengkap</li>
                <li>Ubah username (harus unik)</li>
                <li>Ubah email</li>
                <li>Upload foto profil baru (jpg/png, max 2MB)</li>
            </ul>
            <p style="margin-top: 10px;">Klik "Simpan Perubahan" dan lihat apakah data berhasil diupdate</p>
        </div>

        <!-- Step 5 -->
        <div class="step">
            <h3>🔑 STEP 5: Test Change Password</h3>
            <p>Dari halaman profile, klik tombol "Ganti Password" dan coba:</p>
            <ul>
                <li>Input password lama yang benar</li>
                <li>Input password baru (minimal 8 karakter)</li>
                <li>Lihat password strength meter berubah warna</li>
                <li>Input konfirmasi password yang sama</li>
                <li>Klik "Ubah Password"</li>
            </ul>
            <p style="margin-top: 10px; color: #dc3545;">
                <strong>⚠️ Perhatian:</strong> Setelah ganti password, Anda akan otomatis logout. 
                Login kembali dengan password baru untuk memastikan berhasil.
            </p>
        </div>

        <!-- Troubleshooting -->
        <div class="step warning">
            <h3>🐛 Troubleshooting</h3>
            <p><strong>Jika ada error:</strong></p>
            <ul>
                <li>Pastikan XAMPP MySQL sudah berjalan</li>
                <li>Pastikan PHP development server berjalan (php -S localhost:8000)</li>
                <li>Jalankan ulang setup database (Step 1)</li>
                <li>Clear browser cache dan cookies</li>
                <li>Cek folder images/profile/ memiliki permission write</li>
            </ul>
            <div style="margin-top: 15px;">
                <a href="check_profile.php" class="btn btn-secondary">🔄 Re-run Setup</a>
            </div>
        </div>

        <!-- Back to Home -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="btn">🏠 Kembali ke Home</a>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; color: #999;">
            <p>💡 <strong>Tips:</strong> Test semua fitur satu per satu dan perhatikan pesan sukses/error yang muncul</p>
            <p style="margin-top: 10px;">✅ Fitur Profile User - Travel Tour Website</p>
        </div>
    </div>
</body>
</html>
