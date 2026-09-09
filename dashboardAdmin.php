<?php
include "koneksi.php";
include "config_security.php";

requireAdmin(); // Memastikan hanya admin yang bisa akses

$id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM akun_admin WHERE id_admin = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dataAdmin = $result->fetch_assoc();

$activePage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik — Travel Admin</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <h4>Dashboard Statistik</h4>
                    <span>Selamat datang kembali, <strong><?= htmlspecialchars($dataAdmin['nama_admin']) ?></strong>!</span>
                </div>
                <div class="admin-topbar-actions">
                    <span class="badge bg-light text-dark border px-3 py-2" style="font-size: 12px;">
                        <i class="far fa-calendar-alt me-1 text-primary"></i> <?= date('d M Y') ?>
                    </span>
                    <a href="index.php" target="_blank" class="btn-topbar-website">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                    <a href="proses.php?logoutAdmin=true" class="btn-topbar-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <!-- Admin Body -->
            <div class="admin-body">
                <!-- Statistics 4 Cards Row -->
                <div class="row g-4 mb-4">
                    <!-- Total Destinasi -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card-modern">
                            <div class="stat-card-info">
                                <h6>Total Destinasi</h6>
                                <h2>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS total FROM destinasi"; 
                                        $res = mysqli_query($conn, $query); 
                                        $data = mysqli_fetch_assoc($res); 
                                        echo $data['total']; 
                                    ?>
                                </h2>
                            </div>
                            <div class="stat-icon-wrap stat-icon-orange">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Booking -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card-modern">
                            <div class="stat-card-info">
                                <h6>Total Booking</h6>
                                <h2>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS total FROM pemesanan"; 
                                        $res = mysqli_query($conn, $query); 
                                        $data = mysqli_fetch_assoc($res); 
                                        echo $data['total']; 
                                    ?>
                                </h2>
                            </div>
                            <div class="stat-icon-wrap stat-icon-blue">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total User -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card-modern">
                            <div class="stat-card-info">
                                <h6>Total Pengguna</h6>
                                <h2>
                                    <?php 
                                        $query = "SELECT COUNT(*) AS total FROM akun"; 
                                        $res = mysqli_query($conn, $query); 
                                        $data = mysqli_fetch_assoc($res); 
                                        echo $data['total']; 
                                    ?>
                                </h2>
                            </div>
                            <div class="stat-icon-wrap stat-icon-green">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Revenue -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card-modern">
                            <div class="stat-card-info">
                                <h6>Total Pendapatan</h6>
                                <h2 style="font-size: 22px;">
                                    <?php 
                                        $query = "SELECT SUM(d.harga_destinasi * p.jumlah_orang) AS total_revenue 
                                                 FROM pemesanan p 
                                                 JOIN destinasi d ON p.id_destinasi = d.id_destinasi"; 
                                        $res = mysqli_query($conn, $query); 
                                        $data = mysqli_fetch_assoc($res); 
                                        echo 'Rp ' . number_format($data['total_revenue'] ?? 0, 0, ',', '.'); 
                                    ?>
                                </h2>
                            </div>
                            <div class="stat-icon-wrap stat-icon-purple">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row g-4 mb-4">
                    <!-- Booking Trend Line Chart -->
                    <div class="col-lg-8">
                        <div class="admin-card-modern h-100">
                            <div class="card-header-modern">
                                <h5><i class="fas fa-chart-line text-warning me-2"></i>Tren Booking Bulanan (<?= date('Y') ?>)</h5>
                                <span class="badge bg-light text-muted border">Realtime</span>
                            </div>
                            <div class="card-body-modern">
                                <canvas id="bookingChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top Destinations Doughnut Chart -->
                    <div class="col-lg-4">
                        <div class="admin-card-modern h-100">
                            <div class="card-header-modern">
                                <h5><i class="fas fa-fire text-danger me-2"></i>Top 5 Destinasi</h5>
                                <span class="badge bg-light text-muted border">Terpopuler</span>
                            </div>
                            <div class="card-body-modern">
                                <canvas id="destinasiChart" height="230"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings & Monthly Summary -->
                <div class="row g-4">
                    <!-- Recent Bookings Table -->
                    <div class="col-lg-8">
                        <div class="admin-card-modern mb-0">
                            <div class="card-header-modern">
                                <h5><i class="fas fa-clock text-primary me-2"></i>Pemesanan Terbaru</h5>
                                <a href="kelolaBooking.php" class="btn btn-sm btn-outline-primary" style="border-radius: 20px; font-size: 12px;">
                                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table admin-table-modern mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama Pemesan</th>
                                            <th>Destinasi</th>
                                            <th>Tanggal Berangkat</th>
                                            <th>Peserta</th>
                                            <th>Total Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $query = "SELECT p.*, a.nama_lengkap, d.nama_destinasi, d.harga_destinasi 
                                                     FROM pemesanan p 
                                                     JOIN akun a ON p.id_akun = a.id_akun 
                                                     JOIN destinasi d ON p.id_destinasi = d.id_destinasi 
                                                     ORDER BY p.id_pemesanan DESC 
                                                     LIMIT 5"; 
                                            $res = mysqli_query($conn, $query);
                                            
                                            if(mysqli_num_rows($res) > 0) {
                                                while($booking = mysqli_fetch_assoc($res)) {
                                                    $total = $booking['harga_destinasi'] * $booking['jumlah_orang'];
                                                    echo "<tr>
                                                            <td>
                                                                <div class='d-flex align-items-center gap-2'>
                                                                    <div style='width:32px;height:32px;border-radius:50%;background:#e8ecf0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#1a1a2e;'>
                                                                        " . strtoupper(substr($booking['nama_lengkap'], 0, 1)) . "
                                                                    </div>
                                                                    <strong>" . htmlspecialchars($booking['nama_lengkap']) . "</strong>
                                                                </div>
                                                            </td>
                                                            <td>" . htmlspecialchars($booking['nama_destinasi']) . "</td>
                                                            <td><span class='badge bg-light text-dark border'>" . date('d M Y', strtotime($booking['tanggal_berangkat'])) . "</span></td>
                                                            <td><strong>{$booking['jumlah_orang']}</strong> orang</td>
                                                            <td class='text-primary fw-bold'>Rp " . number_format($total, 0, ',', '.') . "</td>
                                                          </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center text-muted py-4'>Belum ada pemesanan terbaru.</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Stats -->
                    <div class="col-lg-4">
                        <div class="admin-card-modern mb-0">
                            <div class="card-header-modern">
                                <h5><i class="fas fa-calendar-alt text-success me-2"></i>Statistik Bulan Ini</h5>
                                <span class="badge bg-primary-light text-primary border" style="font-size: 11px;"><?= date('F Y') ?></span>
                            </div>
                            <div class="card-body-modern">
                                <?php 
                                    // Booking bulan ini
                                    $query_month = "SELECT COUNT(*) AS total FROM pemesanan WHERE MONTH(tanggal_berangkat) = MONTH(CURDATE()) AND YEAR(tanggal_berangkat) = YEAR(CURDATE())";
                                    $result_month = mysqli_query($conn, $query_month);
                                    $data_month = mysqli_fetch_assoc($result_month);
                                    
                                    // Revenue bulan ini
                                    $query_revenue_month = "SELECT SUM(d.harga_destinasi * p.jumlah_orang) AS total 
                                                           FROM pemesanan p 
                                                           JOIN destinasi d ON p.id_destinasi = d.id_destinasi 
                                                           WHERE MONTH(p.tanggal_berangkat) = MONTH(CURDATE()) 
                                                           AND YEAR(p.tanggal_berangkat) = YEAR(CURDATE())";
                                    $result_revenue_month = mysqli_query($conn, $query_revenue_month);
                                    $data_revenue_month = mysqli_fetch_assoc($result_revenue_month);
                                    
                                    // User baru bulan ini
                                    $data_user_month = ['total' => 0];
                                    $query_check = "SHOW COLUMNS FROM akun LIKE 'created_at'";
                                    $result_check = mysqli_query($conn, $query_check);
                                    if(mysqli_num_rows($result_check) > 0) {
                                        $query_user_month = "SELECT COUNT(*) AS total FROM akun WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                                        $result_user_month = mysqli_query($conn, $query_user_month);
                                        $data_user_month = mysqli_fetch_assoc($result_user_month);
                                    }
                                ?>
                                
                                <div class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(59,130,246,0.12);display:flex;align-items:center;justify-content:center;color:#3b82f6;">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <span class="text-muted" style="font-size:13.5px;">Booking Baru</span>
                                    </div>
                                    <strong class="text-dark fs-6"><?= $data_month['total'] ?> booking</strong>
                                </div>
                                
                                <div class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,0.12);display:flex;align-items:center;justify-content:center;color:#10b981;">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <span class="text-muted" style="font-size:13.5px;">Revenue Bulanan</span>
                                    </div>
                                    <strong class="text-success fs-6">Rp <?= number_format($data_revenue_month['total'] ?? 0, 0, ',', '.') ?></strong>
                                </div>
                                
                                <div class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(139,92,246,0.12);display:flex;align-items:center;justify-content:center;color:#8b5cf6;">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <span class="text-muted" style="font-size:13.5px;">Pengguna Baru</span>
                                    </div>
                                    <strong class="text-dark fs-6"><?= $data_user_month['total'] ?> user</strong>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center pt-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,165,0,0.12);display:flex;align-items:center;justify-content:center;color:#ffa500;">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <span class="text-muted" style="font-size:13.5px;">Rata-rata/Hari</span>
                                    </div>
                                    <strong class="text-dark fs-6"><?= round($data_month['total'] / (int)date('d'), 1) ?> booking</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Chart.js Scripts -->
    <script>
        // Data Booking per Bulan
        <?php
            $bookingPerMonth = array_fill(0, 12, 0);
            $query_chart = "SELECT MONTH(tanggal_berangkat) as bulan, COUNT(*) as total 
                           FROM pemesanan 
                           WHERE YEAR(tanggal_berangkat) = YEAR(CURDATE()) 
                           GROUP BY MONTH(tanggal_berangkat)";
            $result_chart = mysqli_query($conn, $query_chart);
            while($row = mysqli_fetch_assoc($result_chart)) {
                $bookingPerMonth[$row['bulan'] - 1] = (int)$row['total'];
            }
        ?>
        
        const bookingData = <?= json_encode($bookingPerMonth) ?>;
        const ctx1 = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: bookingData,
                    borderColor: '#ffa500',
                    backgroundColor: 'rgba(255, 165, 0, 0.12)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffa500',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f2f5' },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
        
        // Data Top Destinasi
        <?php
            $destinasiNames = [];
            $destinasiCounts = [];
            $query_destinasi = "SELECT d.nama_destinasi, COUNT(p.id_pemesanan) as total 
                               FROM destinasi d 
                               LEFT JOIN pemesanan p ON d.id_destinasi = p.id_destinasi 
                               GROUP BY d.id_destinasi 
                               ORDER BY total DESC 
                               LIMIT 5";
            $result_destinasi = mysqli_query($conn, $query_destinasi);
            while($row = mysqli_fetch_assoc($result_destinasi)) {
                $destinasiNames[] = $row['nama_destinasi'];
                $destinasiCounts[] = (int)$row['total'];
            }
        ?>
        
        const ctx2 = document.getElementById('destinasiChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($destinasiNames) ?>,
                datasets: [{
                    data: <?= json_encode($destinasiCounts) ?>,
                    backgroundColor: [
                        '#ffa500',
                        '#1a1a2e',
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11, family: 'Poppins' }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
