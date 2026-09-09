<?php
include "koneksi.php";
include "config_security.php";
include "auto_login.php";

if (!isset($_SESSION['id'])) {
  $loggedIn = false;
} else {
  $loggedIn = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel — Jelajahi Indonesia</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg" id="navbar">
    <div class="container">
      <a class="navbar-brand" href="index.php" id="logo"><span>T</span>ravel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span><i class="fa-solid fa-bars"></i></span>
      </button>
      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
          <li class="nav-item"><a class="nav-link" href="daftarDestinasi.php"><i class="fas fa-map-marker-alt me-1"></i>Destinasi</a></li>
          <li class="nav-item"><a class="nav-link" href="listBooking.php"><i class="fas fa-suitcase me-1"></i>My Booking</a></li>
          <li class="nav-item"><a class="nav-link" href="#about"><i class="fas fa-info-circle me-1"></i>About</a></li>
        </ul>
        <ul class="navbar-nav ms-auto align-items-center gap-2">
          <?php if ($loggedIn) : ?>
            <li class="nav-item">
              <a class="nav-link" href="profile.php">
                <i class="fas fa-user-circle me-1"></i>Profile
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn-nav-logout" href="proses.php?logout=true">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
              </a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link btn-nav-login" href="login.php">
                <i class="fas fa-sign-in-alt me-1"></i>Login
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <?php if (isset($_GET['pesanSukses'])) : ?>
    <div class="alert alert-success alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
      <i class="fas fa-check-circle me-2"></i>Pemesanan berhasil dilakukan!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif (isset($_GET['error'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_GET['error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Hero Section -->
  <div class="home">
    <div class="content">
      <div class="badge-hero">✈ Explore Nusantara</div>
      <h5>Welcome To Indonesia</h5>
      <h1>Visit <span class="changecontent"></span></h1>
      <p>Jelajahi keindahan Indonesia, tempat yang memikat dengan pesona budaya<br>dan alamnya yang tiada duanya.</p>
      <div>
        <a href="daftarDestinasi.php" class="btn-hero">
          <i class="fas fa-compass"></i> Jelajahi Destinasi
        </a>
        <a href="#about" class="btn-hero btn-hero-outline">
          <i class="fas fa-play-circle"></i> Tentang Kami
        </a>
      </div>
      <div class="stats-bar">
        <div class="stat"><h3>50+</h3><p>Destinasi Wisata</p></div>
        <div class="stat"><h3>1K+</h3><p>Wisatawan Puas</p></div>
        <div class="stat"><h3>5</h3><p>Kota Tujuan</p></div>
        <div class="stat"><h3>4.9★</h3><p>Rating Rata-rata</p></div>
      </div>
    </div>
  </div>

  <!-- Packages Section -->
  <section class="packages" id="packages">
    <div class="container">
      <div class="section-header">
        <div class="label">🏆 Pilihan Terbaik</div>
        <h1>Paket <span>Wisata</span> Unggulan</h1>
        <p>Temukan destinasi impianmu dengan harga terbaik dan pengalaman tak terlupakan</p>
      </div>

      <div class="row g-4">

        <div class="col-md-4">
          <div class="card">
            <div class="img-wrapper">
              <img src="images/Surabaya/Kelenteng Sanggar Agung.jpg" alt="Kelenteng Saggar">
              <span class="badge-category">Budaya</span>
            </div>
            <div class="card-body">
              <h3>Kelenteng Saggar</h3>
              <p>Nikmati keindahan arsitektur budaya yang megah di Kelenteng Saggra. Tempat ini menawarkan pengalaman spiritual dan visual yang memukau.</p>
              <div class="star mb-1">
                <i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <small class="text-muted ms-1">3.0</small>
              </div>
              <div class="price-tag">Rp250.000 <span>/ orang</span></div>
              <a href="detailDestinasi.php?id=5" class="btn-book"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="img-wrapper">
              <img src="images/Banyuwangi/kawah ijen.jpg" alt="Kawah Ijen">
              <span class="badge-category">Alam</span>
            </div>
            <div class="card-body">
              <h3>Kawah Ijen</h3>
              <p>Rasakan keajaiban alam di Kawah Ijen, dengan danau kawah berwarna biru toska dan fenomena api biru yang langka. Pilihan tepat untuk petualangan.</p>
              <div class="star mb-1">
                <i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <small class="text-muted ms-1">3.0</small>
              </div>
              <div class="price-tag">Rp225.000 <span>/ orang</span></div>
              <a href="detailDestinasi.php?id=4" class="btn-book"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="img-wrapper">
              <img src="images/Malang/gunung bromo.jpg" alt="Gunung Bromo">
              <span class="badge-category">Petualangan</span>
            </div>
            <div class="card-body">
              <h3>Gunung Bromo</h3>
              <p>Keindahan Gunung Bromo yang ikonis dengan lautan pasirnya yang luas. Saksikan matahari terbit yang memukau dari puncak savana.</p>
              <div class="star mb-1">
                <i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <small class="text-muted ms-1">3.0</small>
              </div>
              <div class="price-tag">Rp320.000 <span>/ orang</span></div>
              <a href="detailDestinasi.php?id=7" class="btn-book"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="img-wrapper">
              <img src="images/Yogyakarta/CANDI PRAMBANAN.jpg" alt="Candi Prambanan">
              <span class="badge-category">Sejarah</span>
            </div>
            <div class="card-body">
              <h3>Candi Prambanan</h3>
              <p>Kagumi keindahan Candi Prambanan, kompleks candi Hindu terbesar di Indonesia, yang kaya akan nilai sejarah dan seni ukiran yang menakjubkan.</p>
              <div class="star mb-1">
                <i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <small class="text-muted ms-1">3.0</small>
              </div>
              <div class="price-tag">Rp200.000 <span>/ orang</span></div>
              <a href="detailDestinasi.php?id=8" class="btn-book"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="img-wrapper">
              <img src="images/Bali/Pura ulun danu bratan.png" alt="Pura Ulun">
              <span class="badge-category">Spiritual</span>
            </div>
            <div class="card-body">
              <h3>Pura Ulun Danu</h3>
              <p>Jelajahi Pura Ulun Danu, sebuah pura terapung di Danau Beratan yang dikelilingi oleh pemandangan pegunungan yang mempesona.</p>
              <div class="star mb-1">
                <i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <small class="text-muted ms-1">3.0</small>
              </div>
              <div class="price-tag">Rp200.000 <span>/ orang</span></div>
              <a href="detailDestinasi.php?id=9" class="btn-book"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="img-wrapper">
              <img src="images/Bali/Tanah Lot.jpg" alt="Tanah Lot">
              <span class="badge-category">Sunset</span>
            </div>
            <div class="card-body">
              <h3>Tanah Lot</h3>
              <p>Saksikan keajaiban Tanah Lot, pura di atas batu karang yang berada di tepi laut, sempurna untuk menikmati matahari terbenam yang menakjubkan.</p>
              <div class="star mb-1">
                <i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star checked"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                <small class="text-muted ms-1">3.0</small>
              </div>
              <div class="price-tag">Rp225.000 <span>/ orang</span></div>
              <a href="detailDestinasi.php?id=10" class="btn-book"><i class="fas fa-calendar-check"></i> Pesan Sekarang</a>
            </div>
          </div>
        </div>

      </div>

      <div class="text-center mt-5">
        <a href="daftarDestinasi.php" class="btn-view-all">
          <i class="fas fa-compass me-2 btn-icon-main"></i> Lihat Semua Destinasi <i class="fas fa-arrow-right ms-2 btn-icon-arrow"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="services" id="services">
    <div class="container">
      <div class="section-header">
        <div class="label">⚡ Layanan Kami</div>
        <h1>Apa yang <span>Kami</span> Tawarkan</h1>
        <p>Kami menyediakan layanan terlengkap untuk perjalanan wisata impian Anda</p>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="service-card">
            <div class="icon-wrap"><i class="fas fa-hotel"></i></div>
            <h3>Affordable Hotel</h3>
            <p>Temukan penginapan yang nyaman dan terjangkau untuk memastikan perjalanan Anda tetap hemat tanpa mengurangi kualitas.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card">
            <div class="icon-wrap"><i class="fas fa-utensils"></i></div>
            <h3>Food & Drinks</h3>
            <p>Nikmati hidangan lezat khas daerah dengan berbagai pilihan makanan dan minuman yang menggugah selera.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card">
            <div class="icon-wrap"><i class="fas fa-shield-alt"></i></div>
            <h3>Safety Guide</h3>
            <p>Kami menyediakan panduan keselamatan profesional untuk memastikan perjalanan Anda aman dan tanpa kendala.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card">
            <div class="icon-wrap"><i class="fas fa-globe-asia"></i></div>
            <h3>Around Indonesia</h3>
            <p>Jelajahi destinasi wisata indonesia dengan layanan perjalanan terbaik dan pemandu berpengalaman.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card">
            <div class="icon-wrap"><i class="fas fa-plane"></i></div>
            <h3>Fastest Travel</h3>
            <p>Dapatkan layanan perjalanan tercepat dengan konektivitas transportasi yang efisien dan nyaman.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="service-card">
            <div class="icon-wrap"><i class="fas fa-hiking"></i></div>
            <h3>Adventures</h3>
            <p>Siapkan diri Anda untuk petualangan yang menantang dan mendebarkan bersama pemandu profesional kami.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section class="gallary" id="gallary">
    <div class="container">
      <div class="section-header">
        <div class="label">📸 Galeri</div>
        <h1>Momen <span>Indah</span> Perjalanan</h1>
        <p>Sekilas pandang keindahan destinasi wisata yang menanti Anda</p>
      </div>

      <div class="row g-3">
        <div class="col-md-4">
          <div class="gallery-item">
            <img src="images/Surabaya/gallary.jpg" alt="Surabaya">
            <div class="overlay"><span><i class="fas fa-map-marker-alt me-1"></i>Surabaya</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="gallery-item">
            <img src="images/Banyuwangi/gallary.jpg" alt="Banyuwangi">
            <div class="overlay"><span><i class="fas fa-map-marker-alt me-1"></i>Banyuwangi</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="gallery-item">
            <img src="images/Malang/gallary.jpg" alt="Malang">
            <div class="overlay"><span><i class="fas fa-map-marker-alt me-1"></i>Malang</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="gallery-item">
            <img src="images/Yogyakarta/gallary.jpg" alt="Yogyakarta">
            <div class="overlay"><span><i class="fas fa-map-marker-alt me-1"></i>Yogyakarta</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="gallery-item">
            <img src="images/bali/gallary pura ulun.jpg" alt="Bali - Pura Ulun">
            <div class="overlay"><span><i class="fas fa-map-marker-alt me-1"></i>Bali — Pura Ulun</span></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="gallery-item">
            <img src="images/bali/gallary.jpg" alt="Bali">
            <div class="overlay"><span><i class="fas fa-map-marker-alt me-1"></i>Bali</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="about" id="about">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6">
          <div class="about-img">
            <img src="images/travel indo.jpg" alt="Travel Indonesia">
          </div>
        </div>
        <div class="col-md-6">
          <div class="about-content">
            <div class="section-header text-start mb-3">
              <div class="label">🤝 Tentang Kami</div>
            </div>
            <h2>Cara Kerja<br>Travel Agency Kami</h2>
            <p>Kami adalah agen perjalanan yang berkomitmen untuk memberikan pengalaman perjalanan terbaik. Dengan tim profesional yang berdedikasi, kami merancang setiap perjalanan agar sesuai dengan kebutuhan Anda, mulai dari pemesanan akomodasi, tiket perjalanan, hingga panduan wisata.</p>
            <p>Kami hadir untuk memastikan Anda menikmati perjalanan yang nyaman, aman, dan penuh kenangan indah.</p>
            <a href="about.php">
              <button id="about-btn"><i class="fas fa-arrow-right"></i> Read More</button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="footer">
    <div class="container">
      <h1><span>T</span>ravel</h1>
      <p>Jadikan perjalanan Anda lebih berkesan bersama kami. Temukan destinasi terbaik, layanan terpercaya, dan pengalaman tak terlupakan.</p>
      <div class="social-links">
        <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
      </div>
      <hr class="footer-divider">
      <div class="credit"><p>Designed By King Laksa</p></div>
      <div class="copyright"><p>&copy; Copyright King Laksa. All Rights Reserved</p></div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
