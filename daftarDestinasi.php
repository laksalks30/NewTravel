<?php
include "koneksi.php";
include "config_security.php";

$loggedIn = isset($_SESSION['id']);

$query = "SELECT id_destinasi, nama_destinasi, deskripsi_destinasi, harga_destinasi, gambar_destinasi, kota_destinasi, kategori_destinasi FROM destinasi";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Destinasi — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=3">
    <style>
        .page-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            padding: 70px 0 50px;
            position: relative;
            overflow: hidden;
        }
        .page-hero::before {
            content:'';
            position:absolute;
            width:400px;height:400px;
            background:rgba(255,165,0,0.1);
            border-radius:50%;
            top:-150px;right:-100px;
        }
        .page-hero h1 {
            font-size: 40px;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
        }
        .page-hero h1 span { color: #ffa500; }
        .page-hero p { color: rgba(255,255,255,0.7); font-size: 15px; }
        .search-bar {
            background: white;
            border-radius: 14px;
            padding: 6px 6px 6px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            max-width: 520px;
        }
        .search-bar input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #2d2d2d;
        }
        .search-bar button {
            background: #ffa500;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
        }
        .search-bar button:hover { background: #e09400; }

        .dest-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 100%;
            background: white;
        }
        .dest-card:hover { transform: translateY(-8px); box-shadow: 0 16px 45px rgba(255,165,0,0.18); }
        .dest-card .img-wrap {
            position: relative;
            height: 210px;
            overflow: hidden;
        }
        .dest-card .img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .dest-card:hover .img-wrap img { transform: scale(1.07); }
        .dest-card .tag {
            position: absolute;
            top: 12px; left: 12px;
            background: #ffa500;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .dest-card .city-tag {
            position: absolute;
            top: 12px; right: 12px;
            background: rgba(0,0,0,0.55);
            color: white;
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }
        .dest-card .card-body { padding: 20px; }
        .dest-card .card-body h5 {
            font-size: 17px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }
        .dest-card .card-body p {
            font-size: 12px;
            color: #777;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .dest-card .price {
            font-size: 19px;
            font-weight: 800;
            color: #ffa500;
            margin-bottom: 14px;
        }
        .dest-card .price span { font-size: 12px; font-weight: 400; color: #777; }
        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 20px;
            background: #ffa500;
            color: white;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-detail:hover { background: #e09400; color: white; transform: translateX(4px); box-shadow: 0 6px 20px rgba(255,165,0,0.35); }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: white;
            color: #2d2d2d;
            border: 1.5px solid #e8ecf0;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-back:hover { border-color: #ffa500; color: #ffa500; background: #fff8e6; }
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #777;
        }
        .empty-state i { font-size: 64px; color: #e8ecf0; margin-bottom: 16px; display: block; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php" id="logo"><span>T</span>ravel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span><i class="fa-solid fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="daftarDestinasi.php"><i class="fas fa-map-marker-alt me-1"></i>Destinasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="listBooking.php"><i class="fas fa-suitcase me-1"></i>My Booking</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <?php if ($loggedIn): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fas fa-user-circle me-1"></i>Profile</a></li>
                        <li class="nav-item"><a class="nav-link btn-nav-logout" href="proses.php?logout=true"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn-nav-login" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Hero -->
    <div class="page-hero">
        <div class="container">
            <p style="color:#ffa500;font-size:13px;font-weight:600;margin-bottom:8px;">
                <i class="fas fa-home me-1"></i><a href="index.php" style="color:#ffa500;text-decoration:none;">Home</a> / Destinasi
            </p>
            <h1>Jelajahi <span>Destinasi</span> Wisata</h1>
            <p>Temukan ratusan destinasi wisata terbaik di seluruh Indonesia</p>
            <div class="search-bar mt-4">
                <i class="fas fa-search" style="color:#aaa;"></i>
                <input type="text" id="searchInput" placeholder="Cari destinasi, kota, atau kategori...">
                <button><i class="fas fa-search me-1"></i>Cari</button>
            </div>
        </div>
    </div>

    <!-- Cards -->
    <section style="background:#f8f9fc;padding:60px 0;">
        <div class="container">
            <?php $total = mysqli_num_rows($result); ?>
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <p style="font-size:14px;color:#777;margin:0;">
                    Menampilkan <strong style="color:#1a1a2e;"><?= $total ?></strong> destinasi wisata
                </p>
                <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <?php if ($total > 0): ?>
                <div class="row g-4" id="destGrid">
                    <?php while ($row = mysqli_fetch_assoc($result)):
                        $imageSrc = 'data:image/jpeg;base64,' . base64_encode($row['gambar_destinasi']);
                    ?>
                        <div class="col-md-4 dest-item">
                            <div class="dest-card">
                                <div class="img-wrap">
                                    <img src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row['nama_destinasi']) ?>">
                                    <?php if ($row['kategori_destinasi']): ?>
                                        <span class="tag"><?= htmlspecialchars($row['kategori_destinasi']) ?></span>
                                    <?php endif; ?>
                                    <?php if ($row['kota_destinasi']): ?>
                                        <span class="city-tag"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($row['kota_destinasi']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h5><?= htmlspecialchars($row['nama_destinasi']) ?></h5>
                                    <p><?= htmlspecialchars($row['deskripsi_destinasi']) ?></p>
                                    <div class="price">Rp<?= number_format($row['harga_destinasi'], 0, ',', '.') ?> <span>/ orang</span></div>
                                    <a href="detailDestinasi.php?id=<?= $row['id_destinasi'] ?>" class="btn-detail">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-map-marked-alt"></i>
                    <h5>Belum ada destinasi</h5>
                    <p>Destinasi wisata akan segera hadir.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="navbar.js?v=1"></script>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.dest-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
