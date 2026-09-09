<?php
include "koneksi.php";
include "config_security.php";

requireAdmin(); // Memastikan hanya admin yang bisa akses

$sql = "SELECT * FROM destinasi ORDER BY id_destinasi DESC";
$result = $conn->query($sql);
$activePage = 'destinasi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Destinasi — Travel Admin</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-dest-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .admin-dest-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.09);
        }

        .admin-dest-img {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #eef1f6;
        }

        .admin-dest-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .admin-dest-card:hover .admin-dest-img img {
            transform: scale(1.05);
        }

        .admin-dest-badge-cat {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--primary);
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .admin-dest-badge-city {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(26, 26, 46, 0.75);
            backdrop-filter: blur(4px);
            color: white;
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .admin-dest-body {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-dest-body h5 {
            font-size: 17px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 8px;
        }

        .admin-dest-body p {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .admin-dest-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 16px;
        }

        .admin-dest-actions {
            display: flex;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid #f1f3f7;
        }

        .btn-action-edit {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px;
            border-radius: 10px;
            background: #fff8e6;
            color: #d97706;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #fde68a;
            transition: all 0.25s;
        }

        .btn-action-edit:hover {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
        }

        .btn-action-delete {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px;
            border-radius: 10px;
            background: #fee2e2;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #fecaca;
            transition: all 0.25s;
        }

        .btn-action-delete:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }
    </style>
</head>
<body class="bg-light">

    <div class="admin-layout">
        <!-- Unified Sidebar -->
        <?php include "admin_sidebar.php"; ?>

        <!-- Main Content Area -->
        <main class="admin-main">
            <!-- Topbar -->
            <header class="admin-topbar">
                <div class="admin-topbar-title">
                    <h4>Kelola Destinasi</h4>
                    <span>Daftar paket wisata dan tempat rekreasi</span>
                </div>
                <div class="admin-topbar-actions">
                    <a href="tambahDestinasi.php" class="btn btn-warning text-white fw-bold px-3 py-2" style="border-radius: 25px; font-size: 13px; background: var(--primary); border: none; box-shadow: 0 4px 15px rgba(255,165,0,0.3);">
                        <i class="fas fa-plus me-1"></i> Tambah Destinasi
                    </a>
                </div>
            </header>

            <!-- Admin Body -->
            <div class="admin-body">
                <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses_hapus') : ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Destinasi berhasil dihapus dari sistem!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['status']) && $_GET['status'] === 'sukses_tambah') : ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Destinasi baru berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['status']) && $_GET['status'] === 'sukses_edit') : ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Data destinasi berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['status']) && $_GET['status'] === 'gagal_hapus') : ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> Gagal menghapus destinasi. Silakan coba lagi.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filter & Search Bar -->
                <div class="admin-card-modern mb-4">
                    <div class="card-body-modern py-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari nama destinasi atau kota..." style="border-radius: 0 12px 12px 0;">
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="text-muted" style="font-size: 13.5px;">
                                    Total: <strong class="text-dark" id="countDest"><?= $result->num_rows ?></strong> Destinasi Terdaftar
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Destinations Grid -->
                <div class="row g-4" id="destGrid">
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <?php
                            $id = $row['id_destinasi'];
                            $nama = htmlspecialchars($row['nama_destinasi']);
                            $kota = htmlspecialchars($row['kota_destinasi']);
                            $kategori = htmlspecialchars($row['kategori_destinasi']);
                            $harga = $row['harga_destinasi'];
                            $deskripsi = htmlspecialchars($row['deskripsi_destinasi']);
                            $imgSrc = !empty($row['gambar_destinasi']) ? 'data:image/jpeg;base64,' . base64_encode($row['gambar_destinasi']) : 'images/travel indo.jpg';
                            ?>
                            <div class="col-sm-6 col-lg-4 col-xl-3 dest-item">
                                <div class="admin-dest-card">
                                    <div class="admin-dest-img">
                                        <img src="<?= $imgSrc ?>" alt="<?= $nama ?>">
                                        <span class="admin-dest-badge-cat"><?= $kategori ?></span>
                                        <span class="admin-dest-badge-city"><i class="fas fa-map-marker-alt me-1"></i><?= $kota ?></span>
                                    </div>
                                    <div class="admin-dest-body">
                                        <h5><?= $nama ?></h5>
                                        <p><?= $deskripsi ?></p>
                                        <div class="admin-dest-price">Rp <?= number_format($harga, 0, ',', '.') ?> <span class="text-muted fw-normal" style="font-size: 12px;">/ orang</span></div>
                                        <div class="admin-dest-actions">
                                            <a href="editDestinasi.php?id=<?= $id ?>" class="btn-action-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="proses.php?deleteId=<?= $id ?>" class="btn-action-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus destinasi <?= addslashes($nama) ?>?');">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="col-12 text-center py-5">
                            <div class="p-5 bg-white rounded-3 shadow-sm">
                                <i class="fas fa-map-marked-alt text-muted" style="font-size: 64px;"></i>
                                <h5 class="mt-3 text-secondary">Belum ada destinasi wisata</h5>
                                <p class="text-muted">Klik tombol "+ Tambah Destinasi" untuk menambahkan destinasi baru.</p>
                                <a href="tambahDestinasi.php" class="btn btn-warning text-white fw-bold px-4 py-2 mt-2" style="border-radius: 25px; background: var(--primary); border: none;">
                                    <i class="fas fa-plus me-1"></i> Tambah Sekarang
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Live Search Script -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let visibleCount = 0;
            document.querySelectorAll('.dest-item').forEach(card => {
                const text = card.innerText.toLowerCase();
                const match = text.includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });
            document.getElementById('countDest').innerText = visibleCount;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>