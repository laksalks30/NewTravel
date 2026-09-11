<?php
include "koneksi.php";
include "config_security.php";
include "auto_login.php";

$loggedIn = isset($_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami — Travel</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%23ffa500'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-family='Poppins,sans-serif' font-size='20' font-weight='800' fill='white'>T</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=2">
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
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftarDestinasi.php"><i class="fas fa-map-marker-alt me-1"></i>Destinasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="listBooking.php"><i class="fas fa-suitcase me-1"></i>My Booking</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php"><i class="fas fa-info-circle me-1"></i>About</a></li>
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

    <!-- Page Hero Banner -->
    <div class="page-hero">
        <div class="container">
            <div class="hero-tag"><i class="fas fa-info-circle me-1"></i> Tentang Kami</div>
            <h1>Dedikasi Kami untuk <span>Petualangan Anda</span></h1>
            <p>Mengenal lebih dekat visi, misi, dan tim yang berdedikasi menghadirkan pengalaman perjalanan terbaik di seluruh Nusantara.</p>
        </div>
    </div>

    <!-- Main About Story Section -->
    <section class="about-page-section">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="about-card-img-wrap">
                        <img src="images/travel indo.jpg" alt="Travel Indonesia">
                        <div class="about-badge-float">
                            <i class="fas fa-certificate"></i> Mitra Wisata Terpercaya
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-header text-start mb-3">
                        <div class="label">🤝 SIAPA KAMI</div>
                        <h2 style="font-size:32px;font-weight:800;color:var(--secondary);margin-top:8px;">Menghubungkan Anda dengan Pesona Indonesia</h2>
                    </div>
                    <p style="font-size:14.5px;color:var(--text-muted);line-height:1.8;margin-bottom:14px;">
                        Selamat datang di <strong>Travel</strong>! Kami adalah agen perjalanan wisata terkemuka yang berdedikasi memperkenalkan pesona keindahan alam dan kekayaan budaya Nusantara kepada para penikmat petualangan.
                    </p>
                    <p style="font-size:14.5px;color:var(--text-muted);line-height:1.8;">
                        Dari kemegahan candi bersejarah di Yogyakarta, keindahan pura terapung di Bali, gemerlap kota Surabaya, hingga fenomena alam magis di Kawah Ijen Banyuwangi, kami merancang setiap paket perjalanan agar aman, nyaman, dan meninggalkan kenangan tak ternilai.
                    </p>

                    <!-- Feature Pills -->
                    <div class="feature-pill-grid">
                        <div class="feature-pill-item">
                            <i class="fas fa-compass"></i>
                            <div>
                                <h6>Destinasi Terkurasi</h6>
                                <p>Rekomendasi tempat wisata terbaik & terpopuler.</p>
                            </div>
                        </div>
                        <div class="feature-pill-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <h6>Keamanan Terjamin</h6>
                                <p>Pemandu profesional & prioritas keselamatan.</p>
                            </div>
                        </div>
                        <div class="feature-pill-item">
                            <i class="fas fa-tag"></i>
                            <div>
                                <h6>Harga Transparan</h6>
                                <p>Tarif jujur tanpa biaya tambahan tersembunyi.</p>
                            </div>
                        </div>
                        <div class="feature-pill-item">
                            <i class="fas fa-bolt"></i>
                            <div>
                                <h6>Reservasi Instan</h6>
                                <p>Pemesanan online praktis dengan konfirmasi cepat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="daftarDestinasi.php" class="btn-primary-custom">
                            <i class="fas fa-compass me-1"></i> Jelajahi Destinasi
                        </a>
                        <a href="index.php#services" class="btn-outline-dark-custom">
                            <i class="fas fa-concierge-bell me-1"></i> Layanan Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="py-5" style="background: var(--bg-soft);">
        <div class="container py-4">
            <div class="section-header">
                <div class="label">🎯 VISI & MISI</div>
                <h1>Prinsip & <span>Komitmen Kami</span></h1>
                <p>Nilai utama yang memandu setiap langkah kami dalam melayani wisatawan Indonesia.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="vision-card">
                        <div class="icon-box">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h4>Visi Kami</h4>
                        <p>Menjadi platform agen perjalanan wisata nomor satu di Indonesia yang menghubungkan keajaiban Nusantara secara mudah, aman, dan berkesan bagi semua kalangan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="vision-card">
                        <div class="icon-box">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h4>Misi Kami</h4>
                        <p>Menyediakan paket wisata berkualitas tinggi, memajukan potensi pariwisata daerah secara berkelanjutan, serta memberikan pelayanan berstandar keramahan Nusantara.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="vision-card">
                        <div class="icon-box">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Nilai Utama</h4>
                        <p>Integritas, kehangatan pelayanan, transparansi harga, dan dedikasi penuh dalam menciptakan momen liburan terbaik yang selalu dirindukan setiap wisatawan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Founder Section -->
    <section class="py-5" style="background: var(--bg);">
        <div class="container py-4">
            <div class="section-header">
                <div class="label">👑 PENDIRI KAMI</div>
                <h1>Sosok di Balik <span>Travel</span></h1>
                <p>Kepemimpinan yang berdedikasi membangun pengalaman wisata Indonesia yang lebih modern dan inklusif.</p>
            </div>

            <div class="founder-card">
                <div class="founder-avatar-wrap">
                    <img src="images/orang.jpg" alt="Laksa - Founder & CEO">
                </div>
                <h3>Laksa</h3>
                <div class="founder-badge-role">Founder & Chief Executive Officer</div>
                <p class="founder-quote">
                    "Perjalanan bukan sekadar berpindah dari satu kota ke kota lain, melainkan tentang menemukan makna baru, merayakan kekayaan budaya Indonesia, dan mengukir kenangan abadi bersama orang-orang tercinta."
                </p>
                <div class="founder-socials">
                    <a href="https://www.instagram.com/" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://github.com/laksalks30" target="_blank" title="GitHub"><i class="fa-brands fa-github"></i></a>
                    <a href="#" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats & CTA Section -->
    <section class="py-5" style="background: var(--bg-soft);">
        <div class="container py-3">
            <div class="about-cta-banner">
                <h2>Siap Memulai Petualangan Anda?</h2>
                <p>Pilih destinasi impian Anda sekarang dan rasakan kemudahan merencanakan liburan sempurna bersama kami.</p>
                <a href="daftarDestinasi.php" class="btn-cta">
                    <i class="fas fa-calendar-check me-2"></i> Jelajahi & Pesan Tiket
                </a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
