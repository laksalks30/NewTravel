<?php
include "koneksi.php";

// Cek apakah ID diterima dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data destinasi berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM destinasi WHERE id_destinasi = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data tidak ditemukan!');</script>";
        echo "<script>window.location.href='kelolaDestinasi.php';</script>";
        exit();
    }
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_destinasi = $_POST['nama_destinasi'];
    $harga_destinasi = $_POST['harga_destinasi'];
    $kategori_destinasi = $_POST['kategori_destinasi'];
    $kota_destinasi = $_POST['kota_destinasi'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $deskripsi_destinasi = $_POST['deskripsi_destinasi'];

    // Cek apakah ada file baru untuk gambar
    if (isset($_FILES['gambar_destinasi']['tmp_name']) && !empty($_FILES['gambar_destinasi']['tmp_name'])) {
        $gambar_destinasi = file_get_contents($_FILES['gambar_destinasi']['tmp_name']);
        $sql = "UPDATE destinasi SET nama_destinasi = ?, harga_destinasi = ?, kategori_destinasi = ?, kota_destinasi = ?, latitude = ?, longitude = ?, gambar_destinasi = ?, deskripsi_destinasi = ? WHERE id_destinasi = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissddssi", $nama_destinasi, $harga_destinasi, $kategori_destinasi, $kota_destinasi, $latitude, $longitude, $gambar_destinasi, $deskripsi_destinasi, $id);
    } else {
        // Jika tidak ada file gambar baru, hanya update data lain
        $sql = "UPDATE destinasi SET nama_destinasi = ?, harga_destinasi = ?, kategori_destinasi = ?, kota_destinasi = ?, latitude = ?, longitude = ?, deskripsi_destinasi = ? WHERE id_destinasi = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissddsi", $nama_destinasi, $harga_destinasi, $kategori_destinasi, $kota_destinasi, $latitude, $longitude, $deskripsi_destinasi, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate!');</script>";
        echo "<script>window.location.href='kelolaDestinasi.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destinasi - Leaflet.js</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Destinasi</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_destinasi" class="form-label">Nama Destinasi</label>
                <input type="text" name="nama_destinasi" id="nama_destinasi" class="form-control" value="<?php echo htmlspecialchars($row['nama_destinasi']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga_destinasi" class="form-label">Harga Destinasi</label>
                <input type="number" name="harga_destinasi" id="harga_destinasi" class="form-control" value="<?php echo htmlspecialchars($row['harga_destinasi']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="kategori_destinasi" class="form-label">Kategori Destinasi</label>
                <select name="kategori_destinasi" id="kategori_destinasi" class="form-select" required>
                    <option value="Wisata Alam" <?php echo ($row['kategori_destinasi'] == "Wisata Alam") ? "selected" : ""; ?>>Wisata Alam</option>
                    <option value="Wisata Budaya" <?php echo ($row['kategori_destinasi'] == "Wisata Budaya") ? "selected" : ""; ?>>Wisata Budaya</option>
                    <option value="Wisata Religi" <?php echo ($row['kategori_destinasi'] == "Wisata Religi") ? "selected" : ""; ?>>Wisata Religi</option>
                    <option value="Wisata Kampung" <?php echo ($row['kategori_destinasi'] == "Wisata Kampung") ? "selected" : ""; ?>>Wisata Kampung</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="kota_destinasi" class="form-label">Kota Destinasi</label>
                <select name="kota_destinasi" id="kota_destinasi" class="form-select" required>
                    <option value="Bali" <?php echo ($row['kota_destinasi'] == "Bali") ? "selected" : ""; ?>>Bali</option>
                    <option value="Banyuwangi" <?php echo ($row['kota_destinasi'] == "Banyuwangi") ? "selected" : ""; ?>>Banyuwangi</option>
                    <option value="Malang" <?php echo ($row['kota_destinasi'] == "Malang") ? "selected" : ""; ?>>Malang</option>
                    <option value="Surabaya" <?php echo ($row['kota_destinasi'] == "Surabaya") ? "selected" : ""; ?>>Surabaya</option>
                    <option value="Yogyakarta" <?php echo ($row['kota_destinasi'] == "Yogyakarta") ? "selected" : ""; ?>>Yogyakarta</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="peta" class="form-label">Pilih Lokasi pada Peta</label>
                <div id="map" style="height: 400px; border: 1px solid #ddd;"></div>
            </div>
            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo $row['latitude']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control" value="<?php echo $row['longitude']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambar_destinasi" class="form-label">Gambar Destinasi</label>
                <input type="file" name="gambar_destinasi" id="gambar_destinasi" class="form-control">
                <?php if ($row['gambar_destinasi']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['gambar_destinasi']); ?>" alt="Gambar Destinasi" class="img-thumbnail mt-3" width="150">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="deskripsi_destinasi" class="form-label">Deskripsi Destinasi</label>
                <textarea name="deskripsi_destinasi" id="deskripsi_destinasi" rows="5" class="form-control" required><?php echo htmlspecialchars($row['deskripsi_destinasi']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="kelolaDestinasi.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Leaflet.js Script -->
    <script>
        var map = L.map('map').setView([<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker([<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function(e) {
            document.getElementById('latitude').value = e.target.getLatLng().lat;
            document.getElementById('longitude').value = e.target.getLatLng().lng;
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>
</body>

</html>
