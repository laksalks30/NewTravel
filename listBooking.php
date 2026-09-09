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
           p.jumlah_orang, d.nama_destinasi, d.kota_destinasi, d.harga_destinasi, p.status
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

        @media print {
            body * { visibility: hidden; }
            .modal.show, .modal.show .modal-content, .modal.show .modal-content * {
                visibility: visible;
            }
            .modal.show {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .modal-footer, .btn-close { display: none !important; }
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
                                    <th class="text-center">Aksi / Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $acceptedBookings = [];
                                while ($row = mysqli_fetch_assoc($query)): 
                                    $status = strtolower($row['status'] ?? 'pending');
                                    $cls = match($status) {
                                        'accepted', 'confirmed' => 'status-confirmed',
                                        'rejected', 'cancelled' => 'status-cancelled',
                                        default => 'status-pending'
                                    };
                                    if ($status === 'accepted' || $status === 'confirmed') {
                                        $acceptedBookings[] = $row;
                                    }
                                ?>
                                    <tr>
                                        <td style="color:#aaa;font-size:12px;"><?= $no++ ?></td>
                                        <td><span class="id-badge">#TRV-<?= $row['id_pemesanan'] ?></span></td>
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
                                            <span class="status-badge <?= $cls ?>"><?= ucfirst(htmlspecialchars($row['status'])) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($status === 'accepted' || $status === 'confirmed'): ?>
                                                <button type="button" class="btn btn-sm btn-success text-white fw-bold px-3 py-1" data-bs-toggle="modal" data-bs-target="#modalTiket<?= $row['id_pemesanan'] ?>" style="border-radius: 20px; font-size: 12px; background: #10b981; border: none; box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
                                                    <i class="fas fa-ticket-alt me-1"></i> Lihat E-Tiket
                                                </button>
                                            <?php elseif ($status === 'rejected' || $status === 'cancelled'): ?>
                                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Travel,%20booking%20saya%20nomor%20%23TRV-<?= $row['id_pemesanan'] ?>%20statusnya%20ditolak.%20Boleh%20tanya%20alasannya?" target="_blank" class="btn btn-sm btn-outline-danger" style="border-radius: 20px; font-size: 11px;">
                                                    <i class="fab fa-whatsapp me-1"></i> Tanya CS
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 12px;"><i class="fas fa-clock me-1 text-warning"></i>Menunggu ACC</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal E-Tiket untuk Pesanan yang sudah Di-ACC -->
                <?php foreach ($acceptedBookings as $b): 
                    $totalHarga = (int)$b['harga_destinasi'] * (int)$b['jumlah_orang'];
                ?>
                    <div class="modal fade" id="modalTiket<?= $b['id_pemesanan'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.25);">
                                <!-- Ticket Header -->
                                <div style="background: linear-gradient(135deg, #1a1a2e, #16213e); color: white; padding: 24px 28px; position: relative;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span style="font-weight: 800; font-size: 22px; letter-spacing: -0.5px;"><span style="color:#ffa500;">T</span>ravel E-Ticket</span>
                                            <div style="font-size: 12px; color: rgba(255,255,255,0.7); margin-top: 2px;">Voucher Perjalanan & Bukti Booking Resmi</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success px-3 py-2" style="border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-check-circle me-1"></i> BOOKING ACCEPTED
                                            </span>
                                            <div style="font-size: 11.5px; color: rgba(255,255,255,0.7); margin-top: 4px;">Kode: <strong>#TRV-<?= str_pad($b['id_pemesanan'], 5, '0', STR_PAD_LEFT) ?></strong></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Body -->
                                <div class="modal-body p-4" style="background: #ffffff;">
                                    <div class="mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <h4 class="fw-bold mb-1" style="color: #1a1a2e;"><?= htmlspecialchars($b['nama_destinasi']) ?></h4>
                                            <span class="text-muted" style="font-size: 13.5px;"><i class="fas fa-map-marker-alt text-warning me-1"></i><?= htmlspecialchars($b['kota_destinasi']) ?></span>
                                        </div>
                                        <div class="text-md-end">
                                            <div class="text-muted" style="font-size: 11.5px;">Total Biaya Paket:</div>
                                            <div class="fw-bold text-success" style="font-size: 18px;">Rp <?= number_format($totalHarga, 0, ',', '.') ?></div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-sm-6 col-md-4">
                                            <small class="text-muted d-block" style="font-size: 11px;">Nama Pemesan</small>
                                            <span class="fw-semibold text-dark" style="font-size: 13.5px;"><?= htmlspecialchars($user['nama_lengkap']) ?></span>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <small class="text-muted d-block" style="font-size: 11px;">Jumlah Peserta</small>
                                            <span class="fw-semibold text-dark" style="font-size: 13.5px;"><i class="fas fa-users text-warning me-1"></i><?= $b['jumlah_orang'] ?> Orang</span>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <small class="text-muted d-block" style="font-size: 11px;">Kota Asal Keberangkatan</small>
                                            <span class="fw-semibold text-dark" style="font-size: 13.5px;"><i class="fas fa-location-arrow text-primary me-1"></i><?= htmlspecialchars($b['asal']) ?></span>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="p-2 bg-light rounded-3">
                                                <small class="text-muted d-block" style="font-size: 11px;"><i class="far fa-calendar-alt text-primary me-1"></i>Tanggal Berangkat</small>
                                                <span class="fw-bold text-dark" style="font-size: 13.5px;"><?= date('l, d F Y', strtotime($b['tanggal_berangkat'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="p-2 bg-light rounded-3">
                                                <small class="text-muted d-block" style="font-size: 11px;"><i class="far fa-calendar-check text-success me-1"></i>Tanggal Pulang</small>
                                                <span class="fw-bold text-dark" style="font-size: 13.5px;"><?= date('l, d F Y', strtotime($b['tanggal_pulang'])) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Next Steps / Panduan Keberangkatan -->
                                    <div class="p-3 mb-3" style="background: #f0fdf4; border-radius: 12px; border-left: 4px solid #10b981;">
                                        <div class="fw-bold text-dark mb-1" style="font-size: 13px;">
                                            <i class="fas fa-compass text-success me-1"></i> Langkah Selanjutnya Setelah Booking di-ACC:
                                        </div>
                                        <ul class="ps-3 mb-0 text-secondary" style="font-size: 12px; line-height: 1.7;">
                                            <li><strong>Simpan / Cetak E-Tiket:</strong> Klik tombol <em>"Cetak E-Tiket"</em> untuk menyimpan file PDF atau mencetaknya sebagai bukti verifikasi.</li>
                                            <li><strong>Konfirmasi Titik Kumpul (Meeting Point):</strong> Admin/Tour Guide akan menghubungi Anda via WhatsApp H-1 sebelum keberangkatan untuk share lokasi jemput.</li>
                                            <li><strong>Pelunasan & Koordinasi:</strong> Pembayaran/pelunasan dapat dikonfirmasi langsung dengan CS Travel melalui WhatsApp.</li>
                                            <li><strong>Hari Keberangkatan:</strong> Cukup tunjukkan QR Code atau ID Booking ini kepada petugas/driver di meeting point.</li>
                                        </ul>
                                    </div>

                                    <div class="row align-items-center py-2 bg-light rounded-3 px-3">
                                        <div class="col-auto">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=TRAVEL-BOOKING-<?= $b['id_pemesanan'] ?>-<?= urlencode($b['nama_destinasi']) ?>" alt="QR Code" style="width: 80px; height: 80px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; padding: 3px;">
                                        </div>
                                        <div class="col">
                                            <div class="fw-bold text-dark" style="font-size: 12.5px;">E-Voucher QR Verifikasi</div>
                                            <p class="text-muted mb-0" style="font-size: 11.5px;">Tunjukkan QR Code ini pada petugas penjemputan saat hari H keberangkatan tur.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Footer -->
                                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between p-3">
                                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 20px;">Tutup</button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-warning text-white btn-sm px-3 fw-bold" onclick="window.print()" style="border-radius: 20px; background: #ffa500; border: none; box-shadow: 0 4px 12px rgba(255,165,0,0.3);">
                                            <i class="fas fa-print me-1"></i> Cetak E-Tiket (PDF)
                                        </button>
                                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Travel,%20pesanan%20saya%20%23TRV-<?= $b['id_pemesanan'] ?>%20untuk%20destinasi%20<?= urlencode($b['nama_destinasi']) ?>%20sudah%20di-ACC.%20Boleh%20minta%20info%20koordinasi%20titik%20kumpul%20dan%20pembayaran?" target="_blank" class="btn btn-success btn-sm px-3 fw-bold" style="border-radius: 20px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                                            <i class="fab fa-whatsapp me-1"></i> Chat CS WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
