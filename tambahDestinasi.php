<?php
include "koneksi.php";
include "config_security.php";

requireAdmin(); // Pastikan hanya admin yang bisa mengakses

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_destinasi = trim($_POST['nama_destinasi'] ?? '');
    $harga_destinasi = (int)($_POST['harga_destinasi'] ?? 0);
    $kategori_destinasi = trim($_POST['kategori_destinasi'] ?? '');
    $kota_destinasi = trim($_POST['kota_destinasi'] ?? '');
    $latitude = (float)($_POST['latitude'] ?? -8.409518);
    $longitude = (float)($_POST['longitude'] ?? 115.188919);
    $deskripsi_destinasi = trim($_POST['deskripsi_destinasi'] ?? '');

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
        $stmt->close();
        header("Location: kelolaDestinasi.php?status=sukses_tambah");
        exit();
    } else {
        $errorMessage = "Gagal menyimpan data destinasi: " . $stmt->error;
        $stmt->close();
    }
}

$activePage = 'tambah_destinasi';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Destinasi — Travel Admin</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Global CSS -->
    <link rel="stylesheet" href="style.css">

    <style>
        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 6px;
        }

        .form-control-custom {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            font-size: 13.5px;
            transition: all 0.25s;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.15);
        }

        #map {
            height: 380px;
            width: 100%;
            border-radius: 12px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
            z-index: 1;
        }

        .map-card-wrapper {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .city-presets {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .btn-city-preset {
            background: #f1f3f7;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 500;
            color: var(--secondary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-city-preset:hover, .btn-city-preset.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .image-preview-container {
            width: 100%;
            height: 180px;
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            overflow: hidden;
            position: relative;
            transition: all 0.3s;
        }

        .image-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .image-preview-placeholder {
            text-align: center;
            color: #94a3b8;
        }

        .image-preview-placeholder i {
            font-size: 32px;
            margin-bottom: 6px;
            color: #cbd5e1;
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
                    <h4>Tambah Destinasi</h4>
                    <span>Tambahkan paket wisata baru ke dalam katalog</span>
                </div>
                <div class="admin-topbar-actions">
                    <a href="kelolaDestinasi.php" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 20px; font-size: 13px; font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Destinasi
                    </a>
                </div>
            </header>

            <!-- Admin Body -->
            <div class="admin-body">

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($errorMessage) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="tambahDestinasi.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- Left Column: Destination Details -->
                        <div class="col-lg-6">
                            <div class="admin-card-modern h-100">
                                <div class="card-header-modern">
                                    <h5><i class="fas fa-info-circle text-warning me-2"></i> Informasi Destinasi</h5>
                                </div>
                                <div class="card-body-modern">
                                    <div class="mb-3">
                                        <label for="nama_destinasi" class="form-label-custom">Nama Destinasi <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_destinasi" id="nama_destinasi" class="form-control form-control-custom" placeholder="Contoh: Pantai Kuta Bali" required>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <label for="harga_destinasi" class="form-label-custom">Harga (Rp) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px; font-size: 13px;">Rp</span>
                                                <input type="number" name="harga_destinasi" id="harga_destinasi" class="form-control form-control-custom" placeholder="150000" style="border-radius: 0 10px 10px 0;" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="kategori_destinasi" class="form-label-custom">Kategori <span class="text-danger">*</span></label>
                                            <select name="kategori_destinasi" id="kategori_destinasi" class="form-select form-control-custom" required>
                                                <option value="" disabled selected>Pilih Kategori</option>
                                                <option value="Wisata Alam">Wisata Alam</option>
                                                <option value="Wisata Budaya">Wisata Budaya</option>
                                                <option value="Wisata Religi">Wisata Religi</option>
                                                <option value="Wisata Kampung">Wisata Kampung</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="kota_destinasi" class="form-label-custom">Kota / Wilayah <span class="text-danger">*</span></label>
                                        <select name="kota_destinasi" id="kota_destinasi" class="form-select form-control-custom" required>
                                            <option value="" disabled selected>Pilih Kota Destinasi</option>
                                            <option value="Bali">Bali</option>
                                            <option value="Banyuwangi">Banyuwangi</option>
                                            <option value="Malang">Malang</option>
                                            <option value="Surabaya">Surabaya</option>
                                            <option value="Yogyakarta">Yogyakarta</option>
                                        </select>
                                        <div class="form-text" style="font-size: 11.5px;">Memilih kota akan otomatis menggeser fokus peta di samping.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="gambar_destinasi" class="form-label-custom">Upload Foto Destinasi <span class="text-danger">*</span></label>
                                        <input type="file" name="gambar_destinasi" id="gambar_destinasi" class="form-control form-control-custom mb-2" accept="image/*" required>
                                        
                                        <!-- Image Preview Container -->
                                        <div class="image-preview-container" id="imagePreviewContainer">
                                            <img id="imagePreview" src="" alt="Preview Gambar">
                                            <div class="image-preview-placeholder" id="previewPlaceholder">
                                                <i class="fas fa-image d-block"></i>
                                                <span style="font-size: 12px;">Preview foto akan muncul di sini</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi_destinasi" class="form-label-custom">Deskripsi Lengkap <span class="text-danger">*</span></label>
                                        <textarea name="deskripsi_destinasi" id="deskripsi_destinasi" rows="4" class="form-control form-control-custom" placeholder="Tuliskan daya tarik, fasilitas, dan deskripsi destinasi wisata ini..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Map Coordinates & Picker -->
                        <div class="col-lg-6">
                            <div class="admin-card-modern h-100 d-flex flex-column">
                                <div class="card-header-modern d-flex justify-content-between align-items-center">
                                    <h5><i class="fas fa-map-marker-alt text-warning me-2"></i> Lokasi Peta (Leaflet.js)</h5>
                                    <span class="badge bg-light text-dark fw-normal" style="font-size: 11px;">Interaktif</span>
                                </div>
                                <div class="card-body-modern flex-fill d-flex flex-column">
                                    <p class="text-muted mb-2" style="font-size: 12.5px;">
                                        <i class="fas fa-mouse-pointer text-warning me-1"></i> Klik pada peta atau geser pin marker untuk menandai lokasi destinasi secara presisi.
                                    </p>

                                    <!-- Quick City Presets -->
                                    <div class="city-presets mb-3">
                                        <span class="align-self-center text-muted me-1" style="font-size: 11.5px;">Fokus cepat:</span>
                                        <button type="button" class="btn-city-preset" data-lat="-8.409518" data-lng="115.188919" data-name="Bali">Bali</button>
                                        <button type="button" class="btn-city-preset" data-lat="-8.219233" data-lng="114.369227" data-name="Banyuwangi">Banyuwangi</button>
                                        <button type="button" class="btn-city-preset" data-lat="-7.979700" data-lng="112.630400" data-name="Malang">Malang</button>
                                        <button type="button" class="btn-city-preset" data-lat="-7.257500" data-lng="112.752100" data-name="Surabaya">Surabaya</button>
                                        <button type="button" class="btn-city-preset" data-lat="-7.795600" data-lng="110.369500" data-name="Yogyakarta">Yogyakarta</button>
                                    </div>

                                    <!-- Leaflet Map Container -->
                                    <div class="map-card-wrapper mb-3">
                                        <div id="map"></div>
                                    </div>

                                    <!-- Coordinates Row -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-sm-6">
                                            <label for="latitude" class="form-label-custom">Latitude</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px; font-size: 12px;"><i class="fas fa-arrows-alt-v"></i></span>
                                                <input type="text" name="latitude" id="latitude" class="form-control form-control-custom" value="-8.409518" style="border-radius: 0 10px 10px 0;" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="longitude" class="form-label-custom">Longitude</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px; font-size: 12px;"><i class="fas fa-arrows-alt-h"></i></span>
                                                <input type="text" name="longitude" id="longitude" class="form-control form-control-custom" value="115.188919" style="border-radius: 0 10px 10px 0;" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Action Buttons -->
                                    <div class="mt-auto pt-3 border-top d-flex gap-2">
                                        <button type="submit" name="submitTambahDestinasi" class="btn btn-warning text-white fw-bold px-4 py-2 flex-fill" style="border-radius: 25px; background: var(--primary); border: none; box-shadow: 0 4px 15px rgba(255,165,0,0.35);">
                                            <i class="fas fa-save me-1"></i> Simpan Destinasi
                                        </button>
                                        <a href="kelolaDestinasi.php" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 25px;">
                                            Batal
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </main>
    </div>

    <!-- Leaflet & Bootstrap Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Preset coordinates
        const cityCoords = {
            'Bali': { lat: -8.409518, lng: 115.188919, zoom: 11 },
            'Banyuwangi': { lat: -8.219233, lng: 114.369227, zoom: 12 },
            'Malang': { lat: -7.979700, lng: 112.630400, zoom: 12 },
            'Surabaya': { lat: -7.257500, lng: 112.752100, zoom: 12 },
            'Yogyakarta': { lat: -7.795600, lng: 110.369500, zoom: 12 }
        };

        // Inisialisasi peta Leaflet
        const defaultLat = -8.409518;
        const defaultLng = 115.188919;
        const map = L.map('map').setView([defaultLat, defaultLng], 11);

        // Tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Custom marker icon (FontAwesome styled or default Leaflet)
        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        function updateCoords(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        }

        // Marker drag event
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateCoords(pos.lat, pos.lng);
        });

        // Map click event
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });

        // City presets click
        document.querySelectorAll('.btn-city-preset').forEach(btn => {
            btn.addEventListener('click', function() {
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                const name = this.dataset.name;

                map.flyTo([lat, lng], 12);
                marker.setLatLng([lat, lng]);
                updateCoords(lat, lng);

                const citySelect = document.getElementById('kota_destinasi');
                if (citySelect) {
                    citySelect.value = name;
                }
            });
        });

        // City dropdown change auto-sync with map
        document.getElementById('kota_destinasi').addEventListener('change', function() {
            const cityName = this.value;
            if (cityCoords[cityName]) {
                const c = cityCoords[cityName];
                map.flyTo([c.lat, c.lng], c.zoom);
                marker.setLatLng([c.lat, c.lng]);
                updateCoords(c.lat, c.lng);
            }
        });

        // Instant Image Preview
        document.getElementById('gambar_destinasi').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('previewPlaceholder');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'block';
            }
        });

        // Manual coordinate input change listener
        ['latitude', 'longitude'].forEach(id => {
            document.getElementById(id).addEventListener('change', function() {
                const lat = parseFloat(document.getElementById('latitude').value);
                const lng = parseFloat(document.getElementById('longitude').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.panTo([lat, lng]);
                }
            });
        });
    </script>
</body>
</html>