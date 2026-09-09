<?php
include "koneksi.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_destinasi = $_POST['nama_destinasi'];
    $harga_destinasi = $_POST['harga_destinasi'];
    $kategori_destinasi = $_POST['kategori_destinasi'];
    $kota_destinasi = $_POST['kota_destinasi'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $deskripsi_destinasi = $_POST['deskripsi_destinasi'];

    if (isset($_FILES['gambar_destinasi']['tmp_name']) && !empty($_FILES['gambar_destinasi']['tmp_name'])) {
        $gambar_destinasi = file_get_contents($_FILES['gambar_destinasi']['tmp_name']);
    } else {
        $gambar_destinasi = null;
    }

    $stmt = $conn->prepare("INSERT INTO destinasi 
        (nama_destinasi, harga_destinasi, kategori_destinasi, kota_destinasi, latitude, longitude, gambar_destinasi, deskripsi_destinasi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sissddss",
        $nama_destinasi,
        $harga_destinasi,
        $kategori_destinasi,
        $kota_destinasi,
        $latitude,
        $longitude,
        $gambar_destinasi,
        $deskripsi_destinasi
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Destinasi - Leaflet.js</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tambah Destinasi</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_destinasi" class="form-label">Nama Destinasi</label>
                <input type="text" name="nama_destinasi" id="nama_destinasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="harga_destinasi" class="form-label">Harga Destinasi</label>
                <input type="number" name="harga_destinasi" id="harga_destinasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="kategori_destinasi" class="form-label">Kategori Destinasi</label>
                <select name="kategori_destinasi" id="kategori_destinasi" class="form-select" required>
                    <option value="" disabled selected>Pilih Kategori Destinasi</option>
                    <option value="Wisata Alam">Wisata Alam</option>
                    <option value="Wisata Budaya">Wisata Budaya</option>
                    <option value="Wisata Religi">Wisata Religi</option>
                    <option value="Wisata Kampung">Wisata Kampung</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="kota_destinasi" class="form-label">Kota Destinasi</label>
                <select name="kota_destinasi" id="kota_destinasi" class="form-select" required>
                    <option value="" disabled selected>Pilih Kota Destinasi</option>
                    <option value="Bali">Bali</option>
                    <option value="Banyuwangi">Banyuwangi</option>
                    <option value="Malang">Malang</option>
                    <option value="Surabaya">Surabaya</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                </select>
            </div>

            <!-- Map Section -->
            <div class="mb-3">
                <label for="peta" class="form-label">Pilih Lokasi pada Peta</label>
                <div id="map" style="height: 400px; border: 1px solid #ddd;"></div>
            </div>

            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="gambar_destinasi" class="form-label">Gambar Destinasi</label>
                <input type="file" name="gambar_destinasi" id="gambar_destinasi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi_destinasi" class="form-label">Deskripsi Destinasi</label>
                <textarea name="deskripsi_destinasi" id="deskripsi_destinasi" rows="5" class="form-control" required></textarea>
            </div>
            <button type="submit" name="submitTambahDestinasi" class="btn btn-primary">Tambah</button>
        </form>
        <a href="kelolaDestinasi.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <!-- Leaflet.js Script -->
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-8.409518, 115.188919], 13); // Koordinat default (Bali)

        // Tambahkan layer peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Marker yang bisa dipindahkan
        var marker = L.marker([-8.409518, 115.188919], {
            draggable: true
        }).addTo(map);

        // Saat marker dipindahkan, update koordinat latitude dan longitude
        marker.on('dragend', function(e) {
            var lat = e.target.getLatLng().lat;
            var lng = e.target.getLatLng().lng;

            // Masukkan ke input form
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Saat peta diklik, pindahkan marker ke lokasi baru dan update koordinat
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            marker.setLatLng([lat, lng]);

            // Masukkan ke input form
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    </script>
</body>

</html>