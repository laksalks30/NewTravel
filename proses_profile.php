<?php
// File Backend untuk Proses Profile User
require_once 'config_security.php';
requireLogin();

include 'koneksi.php';

$user_id = $_SESSION['id'];
$action = isset($_POST['action']) ? $_POST['action'] : '';

// ====================================
// UPDATE PROFIL (Nama, Username, Email, Foto)
// ====================================
if ($action === 'update_profile') {
    $nama = sanitizeInput($_POST['nama']);
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    
    // Validasi input
    if (empty($nama) || empty($username) || empty($email)) {
        header("Location: edit_profile.php?error=" . urlencode("Semua field harus diisi"));
        exit();
    }
    
    // Validasi panjang
    if (strlen($nama) < 3) {
        header("Location: edit_profile.php?error=" . urlencode("Nama minimal 3 karakter"));
        exit();
    }
    
    if (strlen($username) < 3) {
        header("Location: edit_profile.php?error=" . urlencode("Username minimal 3 karakter"));
        exit();
    }
    
    // Validasi email
    if (!validateEmail($email)) {
        header("Location: edit_profile.php?error=" . urlencode("Format email tidak valid"));
        exit();
    }
    
    // Cek apakah username sudah digunakan user lain
    $stmt = $conn->prepare("SELECT id_akun FROM akun WHERE username = ? AND id_akun != ?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $stmt->close();
        header("Location: edit_profile.php?error=" . urlencode("Username sudah digunakan"));
        exit();
    }
    $stmt->close();
    
    // Cek apakah email sudah digunakan user lain
    $stmt = $conn->prepare("SELECT id_akun FROM akun WHERE email = ? AND id_akun != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $stmt->close();
        header("Location: edit_profile.php?error=" . urlencode("Email sudah digunakan"));
        exit();
    }
    $stmt->close();
    
    // Handle upload foto profil
    $foto_profil_path = null;
    
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto_profil'];
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        // Validasi tipe file
        if (!in_array($file['type'], $allowed_types)) {
            header("Location: edit_profile.php?error=" . urlencode("Format file harus JPG, PNG, atau GIF"));
            exit();
        }
        
        // Validasi ukuran file
        if ($file['size'] > $max_size) {
            header("Location: edit_profile.php?error=" . urlencode("Ukuran file maksimal 2MB"));
            exit();
        }
        
        // Generate nama file unik
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
        $upload_path = 'images/profile/' . $new_filename;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $foto_profil_path = $upload_path;
            
            // Hapus foto lama jika ada (kecuali default)
            $stmt = $conn->prepare("SELECT foto_profil FROM akun WHERE id_akun = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $old_data = $result->fetch_assoc();
            $stmt->close();
            
            if ($old_data && $old_data['foto_profil'] && 
                $old_data['foto_profil'] !== 'images/profile/default.jpg' && 
                file_exists($old_data['foto_profil'])) {
                unlink($old_data['foto_profil']);
            }
        } else {
            header("Location: edit_profile.php?error=" . urlencode("Gagal upload foto"));
            exit();
        }
    }
    
    // Update database
    try {
        if ($foto_profil_path) {
            // Update dengan foto baru
            $stmt = $conn->prepare("UPDATE akun SET nama_lengkap = ?, username = ?, email = ?, foto_profil = ? WHERE id_akun = ?");
            $stmt->bind_param("ssssi", $nama, $username, $email, $foto_profil_path, $user_id);
        } else {
            // Update tanpa foto
            $stmt = $conn->prepare("UPDATE akun SET nama_lengkap = ?, username = ?, email = ? WHERE id_akun = ?");
            $stmt->bind_param("sssi", $nama, $username, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Update session data
            $_SESSION['user_nama'] = $nama;
            $_SESSION['username'] = $username;
            
            // Log activity
            logActivity($conn, $user_id, 'user', 'profile_updated', 'User memperbarui profil');
            
            header("Location: edit_profile.php?success=profile_updated");
            exit();
        } else {
            $stmt->close();
            header("Location: edit_profile.php?error=" . urlencode("Gagal memperbarui profil"));
            exit();
        }
    } catch (Exception $e) {
        header("Location: edit_profile.php?error=" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
}

// ====================================
// GANTI PASSWORD
// ====================================
elseif ($action === 'change_password') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    
    // Validasi input
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        header("Location: change_password.php?error=" . urlencode("Semua field harus diisi"));
        exit();
    }
    
    // Validasi panjang password baru
    if (strlen($password_baru) < 8) {
        header("Location: change_password.php?error=" . urlencode("Password baru minimal 8 karakter"));
        exit();
    }
    
    // Validasi password baru dan konfirmasi
    if ($password_baru !== $konfirmasi_password) {
        header("Location: change_password.php?error=" . urlencode("Password baru dan konfirmasi tidak cocok"));
        exit();
    }
    
    // Validasi password baru tidak sama dengan password lama
    if ($password_lama === $password_baru) {
        header("Location: change_password.php?error=" . urlencode("Password baru harus berbeda dengan password lama"));
        exit();
    }
    
    // Ambil password lama dari database
    $stmt = $conn->prepare("SELECT password FROM akun WHERE id_akun = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        header("Location: change_password.php?error=" . urlencode("User tidak ditemukan"));
        exit();
    }
    
    // Verifikasi password lama
    if (!verifyPassword($password_lama, $user['password'])) {
        header("Location: change_password.php?error=" . urlencode("Password lama salah"));
        exit();
    }
    
    // Hash password baru
    $password_baru_hashed = hashPassword($password_baru);
    
    // Update password di database
    try {
        $stmt = $conn->prepare("UPDATE akun SET password = ? WHERE id_akun = ?");
        $stmt->bind_param("si", $password_baru_hashed, $user_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Logout user (untuk keamanan)
            session_destroy();
            
            // Redirect ke login
            header("Location: login.php?success=password_changed");
            exit();
        } else {
            $stmt->close();
            header("Location: change_password.php?error=" . urlencode("Gagal mengubah password"));
            exit();
        }
    } catch (Exception $e) {
        header("Location: change_password.php?error=" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
}

// Jika action tidak valid
else {
    header("Location: profile.php");
    exit();
}
?>
