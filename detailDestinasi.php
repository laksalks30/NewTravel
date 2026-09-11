<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

$id_destinasi = $_GET['id'] ?? null;
if ($id_destinasi) {
    $stmt = $conn->prepare("SELECT * FROM destinasi WHERE id_destinasi = ?");
    $stmt->bind_param("i", $id_destinasi);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='daftarDestinasi.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location.href='daftarDestinasi.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['nama_destinasi']) ?> — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css?v=3">
    <style>
        body { background: #f8f9fc; }
        .detail-hero {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            padding: 28px 0 20px;
        }
        .breadcrumb-custom {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.6);
        }
        .breadcrumb-custom a { color: #ffa500; text-decoration: none; }
        .breadcrumb-custom a:hover { text-decoration: underline; }
        .breadcrumb-custom span { color: white; }

        .detail-wrap { padding: 40px 0 60px; }
        .img-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
            margin-bottom: 20px;
        }
        .img-card img { width: 100%; height: 380px; object-fit: cover; display: block; }

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            margin-bottom: 20px;
        }
        .info-card h2 { font-size: 26px; font-weight: 800; color: #1a1a2e; margin-bottom: 6px; }
        .badge-cat {
            display: inline-block;
            background: #fff8e6;
            color: #ffa500;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #2d2d2d;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row i { width: 32px; height: 32px; background: #fff8e6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #ffa500; font-size: 13px; flex-shrink: 0; }
        .info-row .label { color: #aaa; font-size: 12px; font-weight: 600; display: block; }
        .price-big { font-size: 32px; font-weight: 800; color: #ffa500; }
        .price-big span { font-size: 14px; font-weight: 400; color: #777; }

        .btn-booking {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 15px;
            background: #ffa500;
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 8px 24px rgba(255,165,0,0.35);
        }
        .btn-booking:hover { background: #e09400; color: white; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(255,165,0,0.45); }

        .btn-back2 {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            background: white;
            color: #2d2d2d;
            border: 1.5px solid #e8ecf0;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-back2:hover { border-color: #ffa500; color: #ffa500; background: #fff8e6; }

        .map-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .map-card h5 {
            padding: 20px 24px 16px;
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            border-bottom: 1px solid #f0f0f0;
            margin: 0;
        }
        #map { height: 280px; }

        .desc-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .desc-card h5 { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 12px; }
        .desc-card p { font-size: 14px; color: #555; line-height: 1.8; }
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fas fa-user-circle me-1"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link btn-nav-logout" href="proses.php?logout=true"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb Hero -->
    <div class="detail-hero">
        <div class="container">
            <div class="breadcrumb-custom">
                <a href="index.php"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                <a href="daftarDestinasi.php">Destinasi</a>
                <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                <span><?= htmlspecialchars($data['nama_destinasi']) ?></span>
            </div>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="detail-wrap">
        <div class="container">
            <div class="row g-4">
                <!-- Left: Image + Description -->
                <div class="col-lg-7">
                    <div class="img-card">
                        <img src="data:image/jpeg;base64,<?= base64_encode($data['gambar_destinasi']) ?>" alt="<?= htmlspecialchars($data['nama_destinasi']) ?>">
                    </div>
                    <div class="desc-card">
                        <h5><i class="fas fa-align-left me-2" style="color:#ffa500;"></i>Deskripsi Destinasi</h5>
                        <p><?= nl2br(htmlspecialchars($data['deskripsi_destinasi'])) ?></p>
                    </div>
                </div>

                <!-- Right: Info + Booking + Map -->
                <div class="col-lg-5">
                    <div class="info-card">
                        <span class="badge-cat"><i class="fas fa-tag me-1"></i><?= htmlspecialchars($data['kategori_destinasi']) ?></span>
                        <h2><?= htmlspecialchars($data['nama_destinasi']) ?></h2>

                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <div><span class="label">Kota</span><?= htmlspecialchars($data['kota_destinasi']) ?></div>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-layer-group"></i>
                            <div><span class="label">Kategori</span><?= htmlspecialchars($data['kategori_destinasi']) ?></div>
                        </div>
                        <div class="info-row" style="padding-top:16px;">
                            <div style="flex:1;">
                                <span class="label" style="font-size:13px;">Harga per orang</span>
                                <div class="price-big">Rp<?= number_format($data['harga_destinasi'], 0, ',', '.') ?> <span>/ orang</span></div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="booking.php?id_destinasi=<?= $data['id_destinasi'] ?>&nama_destinasi=<?= urlencode($data['nama_destinasi']) ?>" class="btn-booking">
                                <i class="fas fa-calendar-check"></i> Pesan Sekarang
                            </a>
                            <a href="daftarDestinasi.php" class="btn-back2 mt-3 w-100" style="justify-content:center;">
                                <i class="fas fa-arrow-left"></i> Kembali ke Destinasi
                            </a>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="map-card">
                        <h5><i class="fas fa-map-marked-alt me-2" style="color:#ffa500;"></i>Lokasi di Peta</h5>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="navbar.js?v=1"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lat = <?= $data['latitude'] ?>;
        const lng = <?= $data['longitude'] ?>;
        const map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map)
            .bindPopup("<?= htmlspecialchars($data['nama_destinasi']) ?>")
            .openPopup();
    </script>
</body>
</html>
