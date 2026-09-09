<?php
require_once 'config_security.php';
requireLogin();
include 'koneksi.php';

$user_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT id_akun, username, nama_lengkap, email, foto_profil FROM akun WHERE id_akun = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    session_unset(); session_destroy();
    header("Location: login.php?session=expired"); exit();
}

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fc; font-family: 'Poppins', sans-serif; }

        /* Cover */
        .edit-cover {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            height: 160px;
            position: relative;
            overflow: hidden;
        }
        .edit-cover::before {
            content:''; position:absolute;
            width:400px; height:400px;
            background: rgba(255,165,0,0.1);
            border-radius:50%;
            top:-180px; right:-60px;
        }
        .edit-cover::after {
            content:''; position:absolute;
            width:200px; height:200px;
            background: rgba(255,165,0,0.07);
            border-radius:50%;
            bottom:-80px; left:30px;
        }

        /* Wrapper */
        .edit-wrap {
            max-width: 640px;
            margin: -40px auto 60px;
            padding: 0 16px;
            position: relative;
            z-index: 1;
        }

        /* Main Card */
        .edit-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .edit-card-header {
            padding: 28px 32px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .edit-card-header .header-icon {
            width: 44px; height: 44px;
            background: #fff8e6;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .edit-card-header .header-icon i { color: #ffa500; font-size: 18px; }
        .edit-card-header h4 {
            font-size: 18px; font-weight: 800; color: #1a1a2e; margin: 0 0 2px;
        }
        .edit-card-header p { font-size: 12px; color: #777; margin: 0; }
        .edit-card-body { padding: 28px 32px 32px; }

        /* Avatar upload area */
        .avatar-upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            padding: 24px;
            background: #f8f9fc;
            border-radius: 14px;
            border: 2px dashed #e8ecf0;
            margin-bottom: 28px;
            transition: border-color 0.3s;
        }
        .avatar-upload-area:hover { border-color: #ffa500; }

        .avatar-preview-wrap {
            position: relative;
            display: inline-block;
        }
        .avatar-preview {
            width: 100px; height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .avatar-initials-prev {
            width: 100px; height: 100px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            background: linear-gradient(135deg, #ffa500, #e09400);
            display: flex; align-items: center; justify-content: center;
            font-size: 34px; font-weight: 800; color: white;
        }
        .avatar-cam-btn {
            position: absolute;
            bottom: 2px; right: 2px;
            width: 28px; height: 28px;
            background: #ffa500;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: background 0.2s;
        }
        .avatar-cam-btn:hover { background: #e09400; }
        .avatar-cam-btn i { color: white; font-size: 11px; }

        .upload-hint { font-size: 12px; color: #999; text-align: center; }
        .upload-hint strong { color: #555; }

        .btn-upload-photo {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 20px;
            background: #ffa500;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 13px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-upload-photo:hover { background: #e09400; transform: translateY(-1px); }

        /* Form fields */
        .field-group { margin-bottom: 20px; }
        .field-group label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 600; color: #2d2d2d;
            margin-bottom: 7px;
        }
        .field-group label i {
            width: 24px; height: 24px;
            background: #fff8e6; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            color: #ffa500; font-size: 11px; flex-shrink: 0;
        }
        .field-group .form-control {
            border: 1.5px solid #e8ecf0;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #1a1a2e;
            transition: all 0.3s;
        }
        .field-group .form-control:focus {
            border-color: #ffa500;
            box-shadow: 0 0 0 3px rgba(255,165,0,0.12);
            outline: none;
        }
        .field-group .hint {
            font-size: 11px; color: #aaa; margin-top: 5px;
        }

        /* Divider */
        .section-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0 20px;
            font-size: 12px; font-weight: 700; color: #aaa;
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1; height: 1px; background: #f0f0f0;
        }

        /* Alert */
        .alert {
            border: none; border-radius: 10px;
            font-size: 13px; font-family: 'Poppins', sans-serif;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px;
        }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }

        /* Buttons */
        .btn-save {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            width: 100%; padding: 13px;
            background: #ffa500; color: white;
            border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(255,165,0,0.35);
            margin-bottom: 10px;
        }
        .btn-save:hover { background: #e09400; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,165,0,0.4); }
        .btn-cancel {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            width: 100%; padding: 12px;
            background: white; color: #555;
            border: 1.5px solid #e8ecf0; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-cancel:hover { border-color: #ffa500; color: #ffa500; background: #fff8e6; }

        @media (max-width: 576px) { .edit-card-body, .edit-card-header { padding: 20px; } }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php" id="logo"><span>T</span>ravel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nb">
                <span><i class="fa-solid fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="nb">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftarDestinasi.php"><i class="fas fa-map-marker-alt me-1"></i>Destinasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="listBooking.php"><i class="fas fa-suitcase me-1"></i>My Booking</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user-circle me-1"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link btn-nav-logout" href="proses.php?logout=true"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Cover -->
    <div class="edit-cover"></div>

    <!-- Form Card -->
    <div class="edit-wrap">

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $success === 'profile_updated' ? 'Profil berhasil diperbarui!' : htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars(urldecode($error)) ?>
            </div>
        <?php endif; ?>

        <div class="edit-card">
            <div class="edit-card-header">
                <div class="header-icon"><i class="fas fa-user-edit"></i></div>
                <div>
                    <h4>Edit Profil</h4>
                    <p>Perbarui informasi akun Anda</p>
                </div>
            </div>

            <div class="edit-card-body">
                <form action="proses_profile.php" method="POST" enctype="multipart/form-data" id="editProfileForm">
                    <input type="hidden" name="action" value="update_profile">

                    <!-- Avatar Upload Area -->
                    <div class="avatar-upload-area" id="uploadArea">
                        <div class="avatar-preview-wrap">
                            <?php if (!empty($user['foto_profil']) && file_exists($user['foto_profil'])): ?>
                                <img src="<?= htmlspecialchars($user['foto_profil']) ?>"
                                     alt="Preview" class="avatar-preview" id="profilePreview">
                            <?php else: ?>
                                <div class="avatar-initials-prev" id="initialsCircle">
                                    <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                                </div>
                                <img src="" alt="Preview" class="avatar-preview" id="profilePreview" style="display:none;">
                            <?php endif; ?>
                            <label for="foto_profil" class="avatar-cam-btn" title="Ganti foto">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/*" style="display:none;">
                        <label for="foto_profil" class="btn-upload-photo">
                            <i class="fas fa-upload"></i> Pilih Foto
                        </label>
                        <div class="upload-hint">
                            Format: <strong>JPG, PNG, GIF</strong> &nbsp;·&nbsp; Maks: <strong>2MB</strong>
                        </div>
                    </div>

                    <div class="section-divider">Data Akun</div>

                    <!-- Nama Lengkap -->
                    <div class="field-group">
                        <label for="nama">
                            <i class="fas fa-id-card"></i> Nama Lengkap
                        </label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                    </div>

                    <!-- Username -->
                    <div class="field-group">
                        <label for="username">
                            <i class="fas fa-at"></i> Username
                        </label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= htmlspecialchars($user['username']) ?>" required>
                        <div class="hint">Username harus unik, minimal 3 karakter</div>
                    </div>

                    <!-- Email -->
                    <div class="field-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                        <div class="hint">Email digunakan untuk reset password</div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="profile.php" class="btn-cancel">
                            <i class="fas fa-arrow-left"></i> Kembali ke Profil
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footer">
        <div class="container">
            <h1><span>T</span>ravel</h1>
            <p>Jadikan perjalanan Anda lebih berkesan bersama kami.</p>
            <hr class="footer-divider">
            <div class="copyright"><p>&copy; Copyright King Laksa. All Rights Reserved</p></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const fileInput   = document.getElementById('foto_profil');
        const preview     = document.getElementById('profilePreview');
        const initCircle  = document.getElementById('initialsCircle');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                this.value = ''; return;
            }
            const validTypes = ['image/jpeg','image/jpg','image/png','image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Format file harus JPG, PNG, atau GIF!');
                this.value = ''; return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (initCircle) initCircle.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            const nama     = document.getElementById('nama').value.trim();
            const username = document.getElementById('username').value.trim();
            const email    = document.getElementById('email').value.trim();
            if (nama.length < 3)     { e.preventDefault(); alert('Nama minimal 3 karakter!'); return; }
            if (username.length < 3) { e.preventDefault(); alert('Username minimal 3 karakter!'); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { e.preventDefault(); alert('Format email tidak valid!'); return; }
        });
    </script>
</body>
</html>
