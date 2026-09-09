<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

$id_akun = $_SESSION['id'];
$query = mysqli_query($conn, "
    SELECT p.id_pemesanan, p.asal, p.tanggal_berangkat, p.tanggal_pulang,
           p.jumlah_orang, d.nama_destinasi, d.kota_destinasi, p.status
    FROM pemesanan p
    JOIN destinasi d ON p.id_destinasi = d.id_destinasi
    WHERE p.id_akun = '$id_akun'
    ORDER BY p.id_pemesanan DESC
");
$userQ = mysqli_query($conn, "SELECT nama_lengkap FROM akun WHERE id_akun = '$id_akun'");
$user = mysqli_fetch_assoc($userQ);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fc; }
        .page-hero {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            padding: 50px 0 40px;
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content:''; position:absolute;
            width:350px;height:350px;
            background:rgba(255,165,0,0.1);
            border-radius:50%;
            top:-120px;right:-80px;
        }
        .page-hero h1 { font-size:32px;font-weight:800;color:white;margin-bottom:4px; }
        .page-hero h1 span { color:#ffa500; }
        .page-hero p { color:rgba(255,255,255,0.65);font-size:14px; }

        .content-wrap { padding: 40px 0 60px; }

        .booking-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .booking-card .card-header-custom {
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .booking-card .card-header-custom h5 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }

        table { font-family: 'Poppins', sans-serif; }
        thead th {
            background: #f8f9fc;
            font-size: 12px;
            font-weight: 700;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border: none;
        }
        tbody tr { border-bottom: 1px solid #f5f5f5; transition: background 0.2s; }
        tbody tr:hover { background: #fff8f5; }
        tbody td {
            padding: 14px 16px;
            font-size: 13px;
            color: #2d2d2d;
            vertical-align: middle;
            border: none;
        }
        .dest-name { font-weight: 700; color: #1a1a2e; }
        .city-text { font-size: 12px; color: #777; }
        .id-badge {
            background: #f0f4ff;
            color: #3b5bdb;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .status-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .date-block { font-size: 13px; }
        .date-block small { display: block; font-size: 11px; color: #aaa; }

        .btn-back-custom {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: white;
            color: #555;
            border: 1.5px solid #e8ecf0;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        .btn-back-custom:hover { border-color: #ffa500; color: #ffa500; background: #fff8e6; }

        .btn-book-new {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: #ffa500;
            color: white;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        .btn-book-new:hover { background: #e09400; color: white; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(255,165,0,0.35); }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state .empty-icon { font-size: 72px; color: #e8ecf0; display: block; margin-bottom: 16px; }
        .empty-state h5 { font-size: 18px; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
        .empty-state p { color: #777; font-size: 14px; margin-bottom: 24px; }

        .summary-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff8e6;
            color: #ffa500;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
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
                    <li class="nav-item"><a class="nav-link active" href="listBooking.php"><i class="fas fa-suitcase me-1"></i>My Booking</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fas fa-user-circle me-1"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link btn-nav-logout" href="proses.php?logout=true"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Hero -->
    <div class="page-hero">
        <div class="container">
            <h1>My <span>Booking</span></h1>
            <p>Halo, <strong><?= htmlspecialchars($user['nama_lengkap'] ?? 'Traveler') ?></strong> — berikut riwayat perjalanan Anda</p>
        </div>
    </div>

    <div class="content-wrap">
        <div class="container">
            <?php $total = mysqli_num_rows($query); ?>

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <span class="summary-chip"><i class="fas fa-suitcase"></i> <?= $total ?> Total Booking</span>
                <div class="d-flex gap-2">
                    <a href="daftarDestinasi.php" class="btn-book-new"><i class="fas fa-plus"></i> Booking Baru</a>
                    <a href="index.php" class="btn-back-custom"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <?php if ($total > 0): ?>
                <div class="booking-card">
                    <div class="card-header-custom">
                        <h5><i class="fas fa-list me-2" style="color:#ffa500;"></i>Daftar Pemesanan</h5>
                        <small style="color:#aaa;font-size:12px;">Diurutkan dari terbaru</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Booking</th>
                                    <th>Destinasi</th>
                                    <th>Asal</th>
                                    <th>Orang</th>
                                    <th>Berangkat</th>
                                    <th>Pulang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <td style="color:#aaa;font-size:12px;"><?= $no++ ?></td>
                                        <td><span class="id-badge">#<?= $row['id_pemesanan'] ?></span></td>
                                        <td>
                                            <div class="dest-name"><?= htmlspecialchars($row['nama_destinasi']) ?></div>
                                            <?php if (!empty($row['kota_destinasi'])): ?>
                                                <div class="city-text"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($row['kota_destinasi']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['asal']) ?></td>
                                        <td><i class="fas fa-users me-1" style="color:#ffa500;"></i><?= $row['jumlah_orang'] ?></td>
                                        <td>
                                            <div class="date-block">
                                                <?= date('d M Y', strtotime($row['tanggal_berangkat'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-block">
                                                <?= date('d M Y', strtotime($row['tanggal_pulang'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $status = strtolower($row['status']);
                                            $cls = match($status) {
                                                'confirmed' => 'status-confirmed',
                                                'cancelled' => 'status-cancelled',
                                                default => 'status-pending'
                                            };
                                            ?>
                                            <span class="status-badge <?= $cls ?>"><?= ucfirst(htmlspecialchars($row['status'])) ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="booking-card">
                    <div class="empty-state">
                        <i class="fas fa-suitcase-rolling empty-icon"></i>
                        <h5>Belum Ada Booking</h5>
                        <p>Anda belum melakukan pemesanan wisata apapun.<br>Yuk, mulai rencanakan perjalanan impianmu!</p>
                        <a href="daftarDestinasi.php" class="btn-book-new" style="margin:0 auto;">
                            <i class="fas fa-compass"></i> Jelajahi Destinasi
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
