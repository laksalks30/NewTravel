<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

$id_akun = $_SESSION['id'];
$nama_destinasi_terpilih = $_GET['nama_destinasi'] ?? "Destinasi tidak ditemukan";
$error = $_GET['error'] ?? "";
$asal = $_GET['asal'] ?? "";
$jumlah_orang = $_GET['jumlah_orang'] ?? "";
$tanggal_berangkat = $_GET['tanggal_berangkat'] ?? "";
$tanggal_pulang = $_GET['tanggal_pulang'] ?? "";

$userQuery = mysqli_query($conn, "SELECT nama_lengkap FROM akun WHERE id_akun = '$id_akun'");
$userData = mysqli_fetch_assoc($userQuery);

if (!$userData) {
    session_unset();
    session_destroy();
    header("Location: login.php?session=expired");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking — <?= htmlspecialchars($nama_destinasi_terpilih) ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fc; }
        .booking-wrap { padding: 50px 0 70px; }
        .booking-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .booking-header {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            padding: 28px 32px;
            position: relative;
        }
        .booking-header::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,165,0,0.1);
            border-radius: 50%;
            top: -80px; right: -60px;
        }
        .booking-header h3 {
            font-size: 22px;
            font-weight: 800;
            color: white;
            margin: 0 0 4px;
        }
        .booking-header p {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin: 0;
        }
        .booking-header .dest-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,165,0,0.2);
            border: 1px solid rgba(255,165,0,0.4);
            color: #ffaa80;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            margin-top: 12px;
        }
        .booking-body { padding: 32px; }

        .form-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-section-title i { color: #ffa500; }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #2d2d2d;
            margin-bottom: 6px;
        }
        .form-control {
            border: 1.5px solid #e8ecf0;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
            color: #2d2d2d;
        }
        .form-control:focus {
            border-color: #ffa500;
            box-shadow: 0 0 0 3px rgba(255,165,0,0.1);
            outline: none;
        }
        .form-control:disabled, .form-control[readonly] {
            background: #f8f9fc;
            color: #777;
        }
        .btn-submit-booking {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 32px;
            background: #ffa500;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(255,165,0,0.35);
        }
        .btn-submit-booking:hover {
            background: #e09400;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(255,165,0,0.45);
        }
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            background: white;
            color: #555;
            border: 1.5px solid #e8ecf0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        .btn-cancel:hover { border-color: #ffa500; color: #ffa500; background: #fff8e6; }
        .alert { border-radius: 10px; border: none; font-size: 13px; font-family: 'Poppins', sans-serif; }
        .alert-danger { background: #fee2e2; color: #991b1b; }
        .date-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 480px) { .date-row { grid-template-columns: 1fr; } .booking-body { padding: 24px; } }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php" id="logo"><span>T</span>ravel</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftarDestinasi.php"><i class="fas fa-map-marker-alt me-1"></i>Destinasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="listBooking.php"><i class="fas fa-suitcase me-1"></i>My Booking</a></li>
                    <li class="nav-item"><a class="nav-link btn-nav-logout" href="proses.php?logout=true"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="booking-wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6">

                    <?php if ($error): ?>
                        <div class="alert alert-danger mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <div class="booking-card">
                        <div class="booking-header">
                            <h3><i class="fas fa-calendar-check me-2"></i>Form Pemesanan</h3>
                            <p>Lengkapi data perjalanan Anda</p>
                            <div class="dest-badge">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($nama_destinasi_terpilih) ?>
                            </div>
                        </div>

                        <div class="booking-body">
                            <form action="proses.php" method="POST">
                                <input type="hidden" name="id_akun" value="<?= $id_akun ?>">
                                <input type="hidden" name="id_destinasi" value="<?= $_GET['id_destinasi'] ?? '' ?>">

                                <div class="form-section-title"><i class="fas fa-user"></i> Data Pemesan</div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($userData['nama_lengkap']) ?>" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-location-arrow me-1" style="color:#ffa500;"></i>Asal Kota</label>
                                    <input type="text" class="form-control" id="asal" name="asal" placeholder="Contoh: Jakarta, Surabaya, Bandung..." value="<?= htmlspecialchars($asal) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-map-marker-alt me-1" style="color:#ffa500;"></i>Destinasi Tujuan</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($nama_destinasi_terpilih) ?>" readonly>
                                </div>

                                <div class="form-section-title mt-4"><i class="fas fa-plane"></i> Detail Perjalanan</div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-users me-1" style="color:#ffa500;"></i>Jumlah Orang</label>
                                    <input type="number" class="form-control" id="jumlah_orang" name="jumlah_orang" min="1" placeholder="Masukkan jumlah orang" value="<?= htmlspecialchars($jumlah_orang) ?>" required>
                                </div>

                                <div class="date-row mb-4">
                                    <div>
                                        <label class="form-label"><i class="fas fa-calendar-alt me-1" style="color:#ffa500;"></i>Tanggal Berangkat</label>
                                        <input type="date" class="form-control" id="tanggal_berangkat" name="tanggal_berangkat" value="<?= htmlspecialchars($tanggal_berangkat) ?>" required>
                                    </div>
                                    <div>
                                        <label class="form-label"><i class="fas fa-calendar-check me-1" style="color:#ffa500;"></i>Tanggal Pulang</label>
                                        <input type="date" class="form-control" id="tanggal_pulang" name="tanggal_pulang" value="<?= htmlspecialchars($tanggal_pulang) ?>" required>
                                    </div>
                                </div>

                                <div class="d-flex gap-3 justify-content-between align-items-center flex-wrap">
                                    <a href="daftarDestinasi.php" class="btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
                                    <button type="submit" class="btn-submit-booking" name="submitBooking">
                                        <i class="fas fa-paper-plane"></i> Konfirmasi Booking
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi tanggal pulang >= berangkat
        document.getElementById('tanggal_berangkat').addEventListener('change', function() {
            document.getElementById('tanggal_pulang').min = this.value;
        });
    </script>
</body>
</html>
