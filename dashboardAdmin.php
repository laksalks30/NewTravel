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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex" style="height: 100vh;">
        <!-- Sidebar -->
        <nav class="bg-dark text-white p-3" style="width: 250px;">
            <a class="text-warning navbar-brand" href="dashboardAdmin.php" >Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="kelolaDestinasi.php" class="nav-link text-white">Kelola Destinasi</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelolaBooking.php" class="nav-link text-white">Kelola Booking</a>
                </li>
                <li class="nav-item">
                    <a href="proses.php?logoutAdmin=true" class="nav-link text-white">Logout</a>
                </li>
            </ul>
        </nav>
        <!-- Main Content -->
        <div class="flex-grow-1 p-4" style="background-color: #f8f9fa;">
            <h1 class="mb-4">Dashboard Statistik</h1>
            <p class="text-muted mb-4">Selamat datang, <strong><?=$dataAdmin['nama_admin'] ?></strong>!</p>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <!-- Total Destinasi -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-white bg-warning shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Destinasi</h6>
                                    <h2 class="mb-0 mt-2">
                                        <?php 
                                            $query = "SELECT COUNT(*) AS total FROM destinasi"; 
                                            $result = mysqli_query($conn, $query); 
                                            $data = mysqli_fetch_assoc($result); 
                                            echo $data['total']; 
                                        ?>
                                    </h2>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Booking -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-white bg-primary shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Booking</h6>
                                    <h2 class="mb-0 mt-2">
                                        <?php 
                                            $query = "SELECT COUNT(*) AS total FROM pemesanan"; 
                                            $result = mysqli_query($conn, $query); 
                                            $data = mysqli_fetch_assoc($result); 
                                            echo $data['total']; 
                                        ?>
                                    </h2>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total User -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-white bg-success shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total User</h6>
                                    <h2 class="mb-0 mt-2">
                                        <?php 
                                            $query = "SELECT COUNT(*) AS total FROM akun"; 
                                            $result = mysqli_query($conn, $query); 
                                            $data = mysqli_fetch_assoc($result); 
                                            echo $data['total']; 
                                        ?>
                                    </h2>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Revenue -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-white bg-danger shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Revenue</h6>
                                    <h2 class="mb-0 mt-2">
                                        <?php 
                                            $query = "SELECT SUM(d.harga_destinasi * p.jumlah_orang) AS total_revenue 
                                                     FROM pemesanan p 
                                                     JOIN destinasi d ON p.id_destinasi = d.id_destinasi"; 
                                            $result = mysqli_query($conn, $query); 
                                            $data = mysqli_fetch_assoc($result); 
                                            echo 'Rp ' . number_format($data['total_revenue'] ?? 0, 0, ',', '.'); 
                                        ?>
                                    </h2>
                                </div>
                                <div class="fs-1">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Booking per Bulan -->
                <div class="col-lg-8 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Booking per Bulan (2025)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="bookingChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Destinasi Terpopuler -->
                <div class="col-lg-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Top 5 Destinasi</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="destinasiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings & User Activity -->
            <div class="row">
                <!-- Recent Bookings -->
                <div class="col-lg-7 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Booking Terbaru</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Destinasi</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Total</th>
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
                                            $result = mysqli_query($conn, $query);
                                            
                                            if(mysqli_num_rows($result) > 0) {
                                                while($booking = mysqli_fetch_assoc($result)) {
                                                    $total = $booking['harga_destinasi'] * $booking['jumlah_orang'];
                                                    echo "<tr>
                                                            <td>{$booking['nama_lengkap']}</td>
                                                            <td>{$booking['nama_destinasi']}</td>
                                                            <td>{$booking['tanggal_berangkat']}</td>
                                                            <td>{$booking['jumlah_orang']} orang</td>
                                                            <td>Rp " . number_format($total, 0, ',', '.') . "</td>
                                                          </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center text-muted'>Belum ada booking</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Stats -->
                <div class="col-lg-5 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Statistik Bulan Ini</h5>
                        </div>
                        <div class="card-body">
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
                                
                                // User baru bulan ini (skip jika tidak ada created_at)
                                $data_user_month = ['total' => 0];
                                $query_check = "SHOW COLUMNS FROM akun LIKE 'created_at'";
                                $result_check = mysqli_query($conn, $query_check);
                                if(mysqli_num_rows($result_check) > 0) {
                                    $query_user_month = "SELECT COUNT(*) AS total FROM akun WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                                    $result_user_month = mysqli_query($conn, $query_user_month);
                                    $data_user_month = mysqli_fetch_assoc($result_user_month);
                                }
                            ?>
                            
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-ticket-alt text-primary me-2"></i>Booking Baru</span>
                                    <strong class="text-primary"><?= $data_month['total'] ?> booking</strong>
                                </div>
                            </div>
                            
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-money-bill-wave text-success me-2"></i>Revenue</span>
                                    <strong class="text-success">Rp <?= number_format($data_revenue_month['total'] ?? 0, 0, ',', '.') ?></strong>
                                </div>
                            </div>
                            
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user-plus text-info me-2"></i>User Baru</span>
                                    <strong class="text-info"><?= $data_user_month['total'] ?> user</strong>
                                </div>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-chart-line text-warning me-2"></i>Rata-rata/Hari</span>
                                    <strong class="text-warning"><?= round($data_month['total'] / date('d'), 1) ?> booking</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart.js Scripts -->
    <script>
        // Data untuk Booking per Bulan
        <?php
            $bookingPerMonth = array_fill(0, 12, 0);
            $query_chart = "SELECT MONTH(tanggal_berangkat) as bulan, COUNT(*) as total 
                           FROM pemesanan 
                           WHERE YEAR(tanggal_berangkat) = YEAR(CURDATE()) 
                           GROUP BY MONTH(tanggal_berangkat)";
            $result_chart = mysqli_query($conn, $query_chart);
            while($row = mysqli_fetch_assoc($result_chart)) {
                $bookingPerMonth[$row['bulan'] - 1] = $row['total'];
            }
        ?>
        
        const bookingData = <?= json_encode($bookingPerMonth) ?>;
        const ctx1 = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Booking',
                    data: bookingData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        // Data untuk Top Destinasi
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
                $destinasiCounts[] = $row['total'];
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
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
