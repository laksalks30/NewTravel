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

$stmt = $conn->prepare("
    SELECT p.*, d.nama_destinasi, d.deskripsi_destinasi, d.gambar_destinasi, d.harga_destinasi, d.kota_destinasi
    FROM pemesanan p
    JOIN destinasi d ON p.id_destinasi = d.id_destinasi
    WHERE p.id_akun = ?
    ORDER BY p.tanggal_berangkat DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();

$totalBooking = $bookings->num_rows;
$bookings->data_seek(0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fc; font-family: 'Poppins', sans-serif; }

        /* ---- COVER HERO ---- */
        .profile-cover {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            height: 200px;
            position: relative;
            overflow: hidden;
        }
        .profile-cover::before {
            content:''; position:absolute;
            width:450px; height:450px;
            background: rgba(255,165,0,0.12);
            border-radius:50%;
            top:-200px; right:-80px;
        }
        .profile-cover::after {
            content:''; position:absolute;
            width:250px; height:250px;
            background: rgba(255,165,0,0.07);
            border-radius:50%;
            bottom:-100px; left:40px;
        }

        /* ---- AVATAR ---- */
        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-top: -60px;
        }
        .avatar-img {
            width: 120px; height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            background: #f0f0f0;
        }
        .avatar-initials {
            width: 120px; height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #ffa500, #e09400);
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; font-weight: 800; color: white;
        }
        .avatar-badge {
            position: absolute;
            bottom: 6px; right: 6px;
            width: 26px; height: 26px;
            background: #22c55e;
            border-radius: 50%;
            border: 3px solid white;
        }

        /* ---- PROFILE HEADER BAR ---- */
        .profile-bar {
            background: white;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            padding: 0 0 24px;
            margin-bottom: 32px;
        }
        .profile-bar .inner {
            display: flex;
            align-items: flex-end;
            gap: 20px;
            padding: 0 24px;
            flex-wrap: wrap;
        }
        .profile-bar .name-block { flex: 1; padding-bottom: 4px; min-width: 200px; }
        .profile-bar .name-block h2 {
            font-size: 22px; font-weight: 800; color: #1a1a2e; margin: 0 0 2px;
        }
        .profile-bar .name-block p {
            font-size: 13px; color: #777; margin: 0;
        }
        .profile-bar .stat-chips {
            display: flex; gap: 10px; flex-wrap: wrap; padding-bottom: 4px;
        }
        .stat-chip {
            display: inline-flex; align-items: center; gap: 7px;
            background: #f8f9fc;
            border: 1px solid #e8ecf0;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 12px; font-weight: 600; color: #2d2d2d;
        }
        .stat-chip i { color: #ffa500; font-size: 12px; }
        .stat-chip strong { font-size: 14px; color: #1a1a2e; }

        /* ---- CARDS ---- */
        .section-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .section-card .card-top {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f0f0f0;
            display: flex; align-items: center; gap: 10px;
        }
        .section-card .card-top h5 {
            font-size: 15px; font-weight: 700; color: #1a1a2e; margin: 0;
        }
        .section-card .card-top .icon-wrap {
            width: 34px; height: 34px;
            background: #fff8e6; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .section-card .card-top .icon-wrap i { color: #ffa500; font-size: 14px; }
        .section-card .card-body-custom { padding: 20px 24px; }

        /* ---- INFO ROWS ---- */
        .info-row {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .ir-icon {
            width: 36px; height: 36px;
            background: #f8f9fc; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .info-row .ir-icon i { color: #ffa500; font-size: 14px; }
        .info-row .ir-content .label { font-size: 11px; font-weight: 600; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-row .ir-content .value { font-size: 14px; font-weight: 600; color: #1a1a2e; margin-top: 2px; }

        /* ---- ACTION BUTTONS ---- */
        .btn-edit {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 12px;
            background: #ffa500; color: white;
            border: none; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none; cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(255,165,0,0.3);
            margin-bottom: 10px;
        }
        .btn-edit:hover { background: #e09400; color: white; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(255,165,0,0.4); }

        .btn-pass {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 11px;
            background: white; color: #1a1a2e;
            border: 1.5px solid #e8ecf0; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-pass:hover { border-color: #ffa500; color: #ffa500; background: #fff8e6; }

        /* ---- BOOKING ITEMS ---- */
        .booking-item {
            display: flex; gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #f5f5f5;
            align-items: flex-start;
        }
        .booking-item:last-child { border-bottom: none; padding-bottom: 0; }
        .booking-item .b-img {
            width: 90px; height: 70px;
            border-radius: 12px; object-fit: cover;
            flex-shrink: 0;
        }
        .booking-item .b-content { flex: 1; min-width: 0; }
        .booking-item .b-content h6 {
            font-size: 14px; font-weight: 700; color: #1a1a2e;
            margin: 0 0 3px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .booking-item .b-content .b-city {
            font-size: 12px; color: #777; margin-bottom: 6px;
        }
        .booking-item .b-content .b-meta {
            display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 8px;
        }
        .booking-item .b-content .b-meta span {
            font-size: 11px; color: #777;
            display: flex; align-items: center; gap: 4px;
        }
        .booking-item .b-content .b-meta span i { color: #ffa500; }

        .status-badge {
            font-size: 11px; font-weight: 700;
            padding: 3px 12px; border-radius: 20px;
            text-transform: capitalize;
        }
        .s-pending   { background: #fef3c7; color: #92400e; }
        .s-confirmed { background: #d1fae5; color: #065f46; }
        .s-rejected  { background: #fee2e2; color: #991b1b; }
        .s-completed { background: #dbeafe; color: #1e40af; }
        .s-default   { background: #f3f4f6; color: #374151; }

        /* ---- EMPTY STATE ---- */
        .empty-profile {
            text-align: center; padding: 50px 20px;
        }
        .empty-profile .e-icon {
            font-size: 56px; color: #e8ecf0;
            display: block; margin-bottom: 14px;
        }
        .empty-profile h6 { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .empty-profile p { font-size: 13px; color: #777; margin-bottom: 20px; }
        .btn-explore {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 24px;
            background: #ffa500; color: white;
            border-radius: 25px; font-size: 13px; font-weight: 600;
            text-decoration: none; transition: all 0.3s;
        }
        .btn-explore:hover { background: #e09400; color: white; transform: translateY(-1px); }

        @media (max-width: 576px) {
            .profile-bar .inner { align-items: flex-start; }
            .avatar-img, .avatar-initials { width: 90px; height: 90px; font-size: 30px; }
        }
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
    <div class="profile-cover"></div>

    <!-- Profile Bar -->
    <div class="profile-bar">
        <div class="container">
            <div class="inner">
                <!-- Avatar -->
                <div class="avatar-wrapper">
                    <?php if (!empty($user['foto_profil']) && file_exists($user['foto_profil'])): ?>
                        <img src="<?= htmlspecialchars($user['foto_profil']) ?>" alt="Avatar" class="avatar-img">
                    <?php else: ?>
                        <div class="avatar-initials">
                            <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <span class="avatar-badge"></span>
                </div>

                <!-- Name -->
                <div class="name-block">
                    <h2><?= htmlspecialchars($user['nama_lengkap']) ?></h2>
                    <p><i class="fas fa-at me-1"></i><?= htmlspecialchars($user['username']) ?></p>
                </div>

                <!-- Stats -->
                <div class="stat-chips">
                    <div class="stat-chip">
                        <i class="fas fa-suitcase"></i>
                        <div><strong><?= $totalBooking ?></strong><br><span style="font-weight:400;color:#777;">Booking</span></div>
                    </div>
                    <div class="stat-chip">
                        <i class="fas fa-map-marker-alt"></i>
                        <div><strong><?= $totalBooking ?></strong><br><span style="font-weight:400;color:#777;">Destinasi</span></div>
                    </div>
                    <div class="stat-chip">
                        <i class="fas fa-star"></i>
                        <div><strong>4.9</strong><br><span style="font-weight:400;color:#777;">Rating</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container pb-5">
        <div class="row g-4">

            <!-- LEFT: Info + Actions -->
            <div class="col-lg-4">

                <!-- Info Card -->
                <div class="section-card">
                    <div class="card-top">
                        <div class="icon-wrap"><i class="fas fa-user"></i></div>
                        <h5>Informasi Akun</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="info-row">
                            <div class="ir-icon"><i class="fas fa-id-card"></i></div>
                            <div class="ir-content">
                                <div class="label">Nama Lengkap</div>
                                <div class="value"><?= htmlspecialchars($user['nama_lengkap']) ?></div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="ir-icon"><i class="fas fa-at"></i></div>
                            <div class="ir-content">
                                <div class="label">Username</div>
                                <div class="value">@<?= htmlspecialchars($user['username']) ?></div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="ir-icon"><i class="fas fa-envelope"></i></div>
                            <div class="ir-content">
                                <div class="label">Email</div>
                                <div class="value" style="font-size:13px;"><?= htmlspecialchars($user['email']) ?></div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="ir-icon"><i class="fas fa-shield-alt"></i></div>
                            <div class="ir-content">
                                <div class="label">Status Akun</div>
                                <div class="value">
                                    <span style="background:#d1fae5;color:#065f46;font-size:11px;font-weight:700;padding:3px 12px;border-radius:20px;">
                                        ✓ Aktif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="section-card">
                    <div class="card-top">
                        <div class="icon-wrap"><i class="fas fa-cog"></i></div>
                        <h5>Pengaturan Akun</h5>
                    </div>
                    <div class="card-body-custom">
                        <a href="edit_profile.php" class="btn-edit">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                        <a href="change_password.php" class="btn-pass">
                            <i class="fas fa-key"></i> Ganti Password
                        </a>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Booking History -->
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="card-top" style="justify-content:space-between;flex-wrap:wrap;gap:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="icon-wrap"><i class="fas fa-history"></i></div>
                            <h5>Riwayat Perjalanan</h5>
                        </div>
                        <span style="background:#fff8e6;color:#ffa500;font-size:12px;font-weight:700;padding:4px 14px;border-radius:20px;">
                            <?= $totalBooking ?> Trip
                        </span>
                    </div>
                    <div class="card-body-custom">

                        <?php if ($totalBooking > 0): ?>
                            <?php while ($booking = $bookings->fetch_assoc()):
                                $status = strtolower($booking['status']);
                                $scls = match($status) {
                                    'confirmed' => 's-confirmed',
                                    'rejected'  => 's-rejected',
                                    'completed' => 's-completed',
                                    default     => 's-pending'
                                };
                                $label = match($status) {
                                    'confirmed' => '✓ Dikonfirmasi',
                                    'rejected'  => '✗ Ditolak',
                                    'completed' => '✈ Selesai',
                                    default     => '⏳ Menunggu'
                                };
                            ?>
                            <div class="booking-item">
                                <img class="b-img"
                                     src="data:image/jpeg;base64,<?= base64_encode($booking['gambar_destinasi']) ?>"
                                     alt="<?= htmlspecialchars($booking['nama_destinasi']) ?>">
                                <div class="b-content">
                                    <h6><?= htmlspecialchars($booking['nama_destinasi']) ?></h6>
                                    <?php if (!empty($booking['kota_destinasi'])): ?>
                                        <div class="b-city"><i class="fas fa-map-marker-alt me-1" style="color:#ffa500;"></i><?= htmlspecialchars($booking['kota_destinasi']) ?></div>
                                    <?php endif; ?>
                                    <div class="b-meta">
                                        <span><i class="fas fa-calendar-alt"></i><?= date('d M Y', strtotime($booking['tanggal_berangkat'])) ?> → <?= date('d M Y', strtotime($booking['tanggal_pulang'])) ?></span>
                                        <span><i class="fas fa-users"></i><?= $booking['jumlah_orang'] ?> orang</span>
                                        <span><i class="fas fa-location-arrow"></i><?= htmlspecialchars($booking['asal']) ?></span>
                                    </div>
                                    <span class="status-badge <?= $scls ?>"><?= $label ?></span>
                                </div>
                            </div>
                            <?php endwhile; ?>

                        <?php else: ?>
                            <div class="empty-profile">
                                <i class="fas fa-map-marked-alt e-icon"></i>
                                <h6>Belum Ada Perjalanan</h6>
                                <p>Mulai petualanganmu dan buat kenangan tak terlupakan!</p>
                                <a href="daftarDestinasi.php" class="btn-explore">
                                    <i class="fas fa-compass"></i> Jelajahi Destinasi
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
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
</body>
</html>
