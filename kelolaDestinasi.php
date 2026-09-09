<?php
include('koneksi.php');

// Ambil data destinasi dari database
$sql = "SELECT * FROM destinasi";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Destinasi - Admin</title>

    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 240px;
            background-color: #343a40;
            min-height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
        }

        .content {
            margin-left: 260px;
            margin-left: 260px;
            padding: 20px;
            max-width: 100%;
            /* Pastikan tidak ada batasan lebar */
            box-sizing: border-box;
            /* Atasi masalah perhitungan ukuran */
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            /* Susun horizontal */
            gap: 20px;
            /* Jarak antar item */
            width: 100%;
            /* Gunakan seluruh lebar */
            max-width: 100%;
            /* Pastikan tidak dibatasi */
            padding: 10px;
            /* Tambahkan ruang dalam jika diperlukan */
            box-sizing: border-box;
            /* Konsistensi */
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            /* Pastikan fleksibel */
            overflow: hidden;
        }

        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-footer {
            padding: 10px;
            text-align: center;
            border-top: 1px solid #eaeaea;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar">
            <a class="text-warning navbar-brand px-3 py-2" href="dashboardAdmin.php">Admin Panel</a>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="kelolaDestinasi.php" class="nav-link active">Kelola Destinasi</a>
                </li>
                <li class="nav-item">
                    <a href="kelolaBooking.php" class="nav-link">Kelola Booking</a>
                </li>
                <li class="nav-item">
                    <a href="proses.php?logoutAdmin=true" class="nav-link">Logout</a>
                </li>
            </ul>
        </nav>

        <div class="content">
            <h2 class="mb-4">Kelola Destinasi</h2>
            <a href="tambahDestinasi.php" class="btn btn-success mb-3">Tambah Destinasi</a>

            <div class="grid-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $id = $row['id_destinasi'];
                        $nama_destinasi = $row['nama_destinasi'];
                        $gambar_destinasi = $row['gambar_destinasi'];
                        $harga_destinasi = $row['harga_destinasi'];
                        $deskripsi_destinasi = $row['deskripsi_destinasi'];
                        ?>
                        <div class="card">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($gambar_destinasi); ?>" alt="Destinasi">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $nama_destinasi; ?></h5>
                                <p class="card-text"><?php echo substr($deskripsi_destinasi, 0, 100) . (strlen($deskripsi_destinasi) > 100 ? '...' : ''); ?></p>
                                <p class="text-muted">Rp <?php echo number_format($harga_destinasi, 0, ',', '.'); ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="editDestinasi.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm mx-1">Edit</a>
                                <a href="proses.php?deleteId=<?php echo $id; ?>" class="btn btn-danger btn-sm mx-1">Hapus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada destinasi yang ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php $conn->close(); ?>