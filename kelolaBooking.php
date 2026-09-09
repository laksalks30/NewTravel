<?php
include "koneksi.php";
include "config_security.php";

requireAdmin(); // Memastikan hanya admin yang bisa akses

$activePage = 'booking';

// Query semua pemesanan dengan detail user dan destinasi
$query = "SELECT p.*, a.nama_lengkap, a.email, d.nama_destinasi, d.harga_destinasi, d.kota_destinasi 
          FROM pemesanan p 
          JOIN akun a ON p.id_akun = a.id_akun 
          JOIN destinasi d ON p.id_destinasi = d.id_destinasi 
          ORDER BY p.id_pemesanan DESC";

$result = mysqli_query($conn, $query);

// Hitung statistik booking
$totalBooking = 0;
$totalAccepted = 0;
$totalRejected = 0;
$totalPending = 0;

$bookings = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
        $totalBooking++;
        $st = $row['status'] ?? 'Pending';
        if ($st === 'Accepted') $totalAccepted++;
        elseif ($st === 'Rejected') $totalRejected++;
        else $totalPending++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking — Travel Admin</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
                    <h4>Kelola Booking</h4>
                    <span>Daftar pesanan tiket paket wisata masuk</span>
                </div>
                <div class="admin-topbar-actions">
                    <a href="dashboardAdmin.php" class="btn btn-outline-secondary btn-sm px-3 py-2" style="border-radius: 20px; font-size: 13px;">
                        <i class="fas fa-arrow-left me-1"></i> Dashboard
                    </a>
                </div>
            </header>

            <!-- Admin Body -->
            <div class="admin-body">
                <!-- Flash Messages -->
                <?php if (isset($_GET['success']) && $_GET['success'] === 'accepted') : ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Pemesanan berhasil di-<strong>Accept</strong> (diterima)!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['success']) && $_GET['success'] === 'rejected') : ?>
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Pemesanan telah di-<strong>Reject</strong> (ditolak).
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['error'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> Terjadi kesalahan: <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- 4 Quick Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="admin-card-modern p-3 mb-0 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 11px;">Semua Booking</small>
                                <h3 class="mb-0 fw-bold mt-1"><?= $totalBooking ?></h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59,130,246,0.12); display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 18px;">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="admin-card-modern p-3 mb-0 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 11px;">Pending</small>
                                <h3 class="mb-0 fw-bold mt-1 text-warning"><?= $totalPending ?></h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.12); display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 18px;">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="admin-card-modern p-3 mb-0 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 11px;">Accepted</small>
                                <h3 class="mb-0 fw-bold mt-1 text-success"><?= $totalAccepted ?></h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 18px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="admin-card-modern p-3 mb-0 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 11px;">Rejected</small>
                                <h3 class="mb-0 fw-bold mt-1 text-danger"><?= $totalRejected ?></h3>
                            </div>
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.12); display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 18px;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="admin-card-modern">
                    <div class="card-header-modern">
                        <h5><i class="fas fa-list-ul text-primary me-2"></i>Daftar Pemesanan</h5>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Cari nama, kota, destinasi..." style="width: 250px; border-radius: 20px;">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-table-modern mb-0" id="bookingTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pemesan</th>
                                    <th>Rute Perjalanan</th>
                                    <th>Tanggal Liburan</th>
                                    <th>Peserta & Biaya</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($bookings)) : ?>
                                    <?php $no = 1; foreach ($bookings as $row) : ?>
                                        <?php
                                        $id_booking = $row['id_pemesanan'];
                                        $nama = htmlspecialchars($row['nama_lengkap']);
                                        $asal = htmlspecialchars($row['asal'] ?? '-');
                                        $tujuan = htmlspecialchars($row['nama_destinasi']);
                                        $orang = (int)$row['jumlah_orang'];
                                        $harga = (int)($row['harga_destinasi'] ?? 0);
                                        $total = $harga * $orang;
                                        $tgl_berangkat = !empty($row['tanggal_berangkat']) ? date('d M Y', strtotime($row['tanggal_berangkat'])) : '-';
                                        $tgl_pulang = !empty($row['tanggal_pulang']) ? date('d M Y', strtotime($row['tanggal_pulang'])) : '-';
                                        $status = $row['status'] ?? 'Pending';
                                        ?>
                                        <tr>
                                            <td class="text-muted fw-bold"><?= $no++ ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="width:34px;height:34px;border-radius:50%;background:#e0e7ff;color:#4338ca;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">
                                                        <?= strtoupper(substr($nama, 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <strong><?= $nama ?></strong>
                                                        <div class="text-muted" style="font-size: 11.5px;"><?= htmlspecialchars($row['email'] ?? '') ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-secondary"><?= $asal ?> <i class="fas fa-arrow-right text-muted mx-1" style="font-size:11px;"></i> <?= $tujuan ?></div>
                                                <small class="text-muted"><i class="fas fa-map-pin me-1"></i><?= htmlspecialchars($row['kota_destinasi'] ?? '') ?></small>
                                            </td>
                                            <td>
                                                <div style="font-size:12.5px;">
                                                    <div><i class="far fa-calendar-check text-primary me-1"></i> <?= $tgl_berangkat ?></div>
                                                    <div class="text-muted"><i class="far fa-calendar-times me-1"></i> <?= $tgl_pulang ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= $orang ?> Orang</div>
                                                <div class="text-primary fw-semibold" style="font-size: 13px;">Rp <?= number_format($total, 0, ',', '.') ?></div>
                                            </td>
                                            <td>
                                                <?php if ($status === 'Accepted') : ?>
                                                    <span class="badge bg-success px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                                                        <i class="fas fa-check-circle me-1"></i> Accepted
                                                    </span>
                                                <?php elseif ($status === 'Rejected') : ?>
                                                    <span class="badge bg-danger px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                                                        <i class="fas fa-times-circle me-1"></i> Rejected
                                                    </span>
                                                <?php else : ?>
                                                    <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                                                        <i class="fas fa-clock me-1"></i> Pending
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <form action="proses.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="id_pemesanan" value="<?= $id_booking ?>">
                                                        <button type="submit" name="accept" class="btn btn-sm btn-outline-success" title="Terima Booking" <?= $status === 'Accepted' ? 'disabled' : '' ?> style="border-radius: 8px;">
                                                            <i class="fas fa-check"></i> Accept
                                                        </button>
                                                    </form>
                                                    <form action="proses.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="id_pemesanan" value="<?= $id_booking ?>">
                                                        <button type="submit" name="reject" class="btn btn-sm btn-outline-danger" title="Tolak Booking" <?= $status === 'Rejected' ? 'disabled' : '' ?> style="border-radius: 8px;">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 text-secondary" style="opacity: 0.3;"></i>
                                            <h5>Belum ada pesanan booking</h5>
                                            <p class="mb-0">Pesanan dari wisatawan akan muncul secara realtime di tabel ini.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Live Search Table -->
    <script>
        document.getElementById('tableSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#bookingTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>