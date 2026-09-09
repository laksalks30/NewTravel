<?php
include "koneksi.php";
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['id'])) {
    header("location: loginAdmin.php");
    exit;
}

// Ambil semua data pemesanan dari tabel
$query = "SELECT p.*, a.nama_lengkap, d.nama_destinasi 
          FROM pemesanan p 
          JOIN akun a ON p.id_akun = a.id_akun 
          JOIN destinasi d ON p.id_destinasi = d.id_destinasi";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Kelola Booking</h2>

        <?php if (mysqli_num_rows($result) > 0) : ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Jumlah Orang</th>
                        <th>Tanggal Berangkat</th>
                        <th>Tanggal Pulang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($row['asal']); ?></td>
                            <td><?= htmlspecialchars($row['nama_destinasi']); ?></td>
                            <td><?= $row['jumlah_orang']; ?></td>
                            <td><?= $row['tanggal_berangkat']; ?></td>
                            <td><?= $row['tanggal_pulang']; ?></td>
                            <td>
                                <?php if (isset($row['status'])): ?>
                                    <span class="badge bg-<?= $row['status'] === 'Accepted' ? 'success' : ($row['status'] === 'Rejected' ? 'danger' : 'secondary'); ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="proses.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id_pemesanan" value="<?= $row['id_pemesanan']; ?>">
                                    <button type="submit" name="accept" class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="proses.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id_pemesanan" value="<?= $row['id_pemesanan']; ?>">
                                    <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-center">Belum ada data booking.</p>
        <?php endif; ?>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="dashboardAdmin.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>