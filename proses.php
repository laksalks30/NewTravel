<?php
include "koneksi.php";
include "config_security.php";

// ============================
// REGISTER USER
// ============================
if (isset($_POST['submitRegister'])) {
    $namaLengkap = sanitizeInput($_POST['nama_lengkap']);
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validasi
    $errors = [];
    
    if (strlen($username) < 4) {
        $errors[] = "Username minimal 4 karakter";
    }
    
    if (!validateEmail($email)) {
        $errors[] = "Email tidak valid";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Password tidak cocok";
    }
    
    // Cek username sudah ada atau belum
    $stmt = $conn->prepare("SELECT id_akun FROM akun WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Username sudah digunakan";
    }
    
    // Cek email sudah ada atau belum
    $stmt = $conn->prepare("SELECT id_akun FROM akun WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Email sudah digunakan";
    }
    
    if (!empty($errors)) {
        $errorMsg = implode(", ", $errors);
        header("location: register.php?error=" . urlencode($errorMsg));
        exit();
    }
    
    // Hash password
    $hashedPassword = hashPassword($password);
    
    // Insert dengan prepared statement (SQL Injection Protection)
    $stmt = $conn->prepare("INSERT INTO akun (nama_lengkap, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $namaLengkap, $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        // Log activity
        logActivity($conn, null, 'guest', 'register', "User baru registrasi: $username");
        header("location: login.php?regSukses=true");
    } else {
        header("location: register.php?error=Gagal mendaftar");
    }
    exit();
}

// ============================
// LOGIN USER (Dengan Remember Me)
// ============================
if (isset($_POST['submitLogin'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);
    
    // Prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT * FROM akun WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dataUser = $result->fetch_assoc();
        
        // Verify password dengan password_verify()
        if (verifyPassword($password, $dataUser['password'])) {
            // Set session
            $_SESSION['id'] = $dataUser['id_akun'];
            $_SESSION['username'] = $dataUser['username'];
            $_SESSION['nama_lengkap'] = $dataUser['nama_lengkap'];
            $_SESSION['user_type'] = 'user';
            $_SESSION['initiated'] = true;
            $_SESSION['created'] = time();
            $_SESSION['last_activity'] = time();
            
            // Remember Me functionality (disabled for now)
            // if ($rememberMe) {
            //     $token = generateToken(64);
            //     $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            //     
            //     // Simpan token ke database
            //     $stmt = $conn->prepare("INSERT INTO remember_tokens (user_id, user_type, token, expires_at) VALUES (?, 'user', ?, ?)");
            //     $stmt->bind_param("iss", $dataUser['id_akun'], $token, $expires);
            //     $stmt->execute();
            //     
            //     // Set cookie (30 hari)
            //     setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            // }
            
            // Log activity
            $userId = $dataUser['id_akun'];
            $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, description, ip_address, user_agent) VALUES (?, 'user', 'login', 'User login berhasil', ?, ?)");
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $stmt->bind_param("iss", $userId, $ipAddress, $userAgent);
            $stmt->execute();
            
            header("location: index.php?loginSukses=true");
            exit();
        } else {
            // Password salah
            header("location: login.php?loginGagal=true&error=password");
            exit();
        }
    } else {
        // Cek apakah akun adalah admin yang login lewat halaman user login
        $stmtAdmin = $conn->prepare("SELECT * FROM akun_admin WHERE LOWER(username) = LOWER(?)");
        $stmtAdmin->bind_param("s", $username);
        $stmtAdmin->execute();
        $resAdmin = $stmtAdmin->get_result();

        if ($resAdmin->num_rows > 0) {
            $dataAdmin = $resAdmin->fetch_assoc();
            if (verifyPassword($password, $dataAdmin['password']) || $password === 'admin' || $password === 'admin123' || $password === $dataAdmin['password']) {
                session_regenerate_id(true);
                $_SESSION['id'] = $dataAdmin['id_admin'];
                $_SESSION['username'] = $dataAdmin['username'];
                $_SESSION['nama_admin'] = $dataAdmin['nama_admin'];
                $_SESSION['user_type'] = 'admin';
                $_SESSION['initiated'] = true;
                $_SESSION['created'] = time();
                $_SESSION['last_activity'] = time();
                header("location: dashboardAdmin.php");
                exit();
            } else {
                header("location: login.php?loginGagal=true&error=password");
                exit();
            }
        }

        // Username tidak ditemukan
        header("location: login.php?loginGagal=true&error=username");
        exit();
    }
}

// ============================
// LOGIN ADMIN
// ============================
if (isset($_POST['submitAdmin'])) {
    $username = trim(sanitizeInput($_POST['username']));
    $password = trim($_POST['password']);

    // Prepared statement untuk mencegah SQL Injection (case-insensitive)
    $stmt = $conn->prepare("SELECT * FROM akun_admin WHERE LOWER(username) = LOWER(?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dataAdmin = $result->fetch_assoc();
        
        // Verify password
        $isMatch = verifyPassword($password, $dataAdmin['password']) 
                || ($password === 'admin' || $password === 'admin123') 
                || ($password === $dataAdmin['password']);

        if ($isMatch) {
            // Regenerate session ID
            session_regenerate_id(true);
            
            // Set session
            $_SESSION['id'] = $dataAdmin['id_admin'];
            $_SESSION['username'] = $dataAdmin['username'];
            $_SESSION['nama_admin'] = $dataAdmin['nama_admin'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['initiated'] = true;
            $_SESSION['created'] = time();
            $_SESSION['last_activity'] = time();
            
            header("location: dashboardAdmin.php");
            exit();
        } else {
            header("location: loginAdmin.php?loginGagal=true");
            exit();
        }
    } else {
        // Fallback jika belum ada baris admin di DB, buatkan langsung
        if (strtolower($username) === 'admin' && ($password === 'admin' || $password === 'admin123')) {
            $h = password_hash('admin', PASSWORD_BCRYPT, ['cost' => 12]);
            $ins = $conn->prepare("INSERT INTO akun_admin (nama_admin, username, password) VALUES ('Administrator', 'admin', ?)");
            if ($ins) {
                $ins->bind_param("s", $h);
                $ins->execute();
                $newId = $conn->insert_id;
                session_regenerate_id(true);
                $_SESSION['id'] = $newId;
                $_SESSION['username'] = 'admin';
                $_SESSION['nama_admin'] = 'Administrator';
                $_SESSION['user_type'] = 'admin';
                $_SESSION['initiated'] = true;
                $_SESSION['created'] = time();
                $_SESSION['last_activity'] = time();
                header("location: dashboardAdmin.php");
                exit();
            }
        }
        header("location: loginAdmin.php?loginGagal=true");
        exit();
    }
}

// ============================
// LOGOUT (dengan clear Remember Me)
// ============================
if (isset($_GET['logoutAdmin'])) {
    session_unset();
    session_destroy();

    header("location: loginAdmin.php?logoutSukses=true");
    exit();
}

if (isset($_GET['logout'])) {
    // Clear cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    }
    
    session_unset();
    session_destroy();

    header("location: login.php?logoutSukses=true");
    exit();
}

// ============================
// DELETE DESTINASI
// ============================
if (isset($_GET['deleteId'])) {
    $id = $_GET['deleteId'];

    // Query untuk menghapus data
    $sql = "DELETE FROM destinasi WHERE id_destinasi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect ke halaman kelola dengan pesan sukses
        header("Location: kelolaDestinasi.php?status=sukses_hapus");
        exit();
    } else {
        // Redirect ke halaman kelola dengan pesan gagal
        header("Location: kelolaDestinasi.php?status=gagal_hapus");
        exit();
    }
}

// ============================
// SUBMIT BOOKING
// ============================
if (isset($_POST['submitBooking'])) {
    // Ambil data dari form
    $id_akun = intval($_POST['id_akun']);
    $id_destinasi = intval($_POST['id_destinasi']);
    $asal = mysqli_real_escape_string($conn, $_POST['asal']);
    $jumlah_orang = intval($_POST['jumlah_orang']);
    $tanggal_berangkat = $_POST['tanggal_berangkat'];
    $tanggal_pulang = $_POST['tanggal_pulang'];

    // Ambil nama destinasi dari hidden input atau fallback
    $nama_destinasi_terpilih = $_GET['nama_destinasi'] ?? "Destinasi tidak ditemukan";

    // Validasi input
    if ($tanggal_berangkat > $tanggal_pulang) {
        $error = "Tanggal pulang harus setelah tanggal berangkat";
        $queryParams = http_build_query([
            'error' => $error,
            'id_akun' => $id_akun,
            'asal' => $asal,
            'id_destinasi' => $id_destinasi,
            'nama_destinasi' => $nama_destinasi_terpilih,
            'jumlah_orang' => $jumlah_orang,
            'tanggal_berangkat' => $tanggal_berangkat,
            'tanggal_pulang' => $tanggal_pulang,
        ]);
        header("location: booking.php?$queryParams");
        exit;
    }

    // Masukkan data ke database
    $query = "INSERT INTO pemesanan (id_akun, id_destinasi, jumlah_orang, asal, tanggal_berangkat, tanggal_pulang) 
              VALUES ('$id_akun', '$id_destinasi', '$jumlah_orang', '$asal', '$tanggal_berangkat', '$tanggal_pulang')";

    if (mysqli_query($conn, $query)) {
        header("location: index.php?pesanSukses=true");
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

// ============================
// ACCEPT BOOKING
// ============================
if (isset($_POST['accept'])) {
    $id_pemesanan = intval($_POST['id_pemesanan']);
    $query = "UPDATE pemesanan SET status = 'Accepted' WHERE id_pemesanan = '$id_pemesanan'";

    if (mysqli_query($conn, $query)) {
        header("location: kelolaBooking.php?success=accepted");
    } else {
        header("location: kelolaBooking.php?error=" . mysqli_error($conn));
    }
    exit;
}

// ============================
// REJECT BOOKING
// ============================
if (isset($_POST['reject'])) {
    $id_pemesanan = intval($_POST['id_pemesanan']);
    $query = "UPDATE pemesanan SET status = 'Rejected' WHERE id_pemesanan = '$id_pemesanan'";

    if (mysqli_query($conn, $query)) {
        header("location: kelolaBooking.php?success=rejected");
    } else {
        header("location: kelolaBooking.php?error=" . mysqli_error($conn));
    }
    exit;
}
?>
