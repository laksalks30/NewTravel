<?php
include "koneksi.php";
include "config_security.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Travel Website</title>
    
    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php" id="logo"><span>T</span>ravel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span><i class="fa-solid fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Forgot Password Section -->
    <section class="vh-100" style="background-image: url('background.jpg'); background-size: cover; background-position: center;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5">
                            <h3 class="mb-4 text-center">Lupa Password</h3>
                            <p class="text-muted text-center mb-4">
                                Masukkan email Anda untuk menerima kode OTP
                            </p>
                            
                            <form action="proses_otp.php" method="POST">
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="email">
                                        <i class="fas fa-envelope"></i> Email
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           placeholder="Masukkan email Anda" required />
                                </div>

                                <button class="btn btn-warning btn-block w-100 mb-3" type="submit" name="submitForgotPassword">
                                    <i class="fas fa-paper-plane"></i> Kirim Kode OTP
                                </button>
                                
                                <a href="login.php" class="btn btn-outline-secondary btn-block w-100">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                                </a>
                            </form>

                            <!-- Alert Messages -->
                            <?php if (isset($_GET['sukses'])): ?>
                                <div class="alert alert-success mt-3" role="alert">
                                    <i class="fas fa-check-circle"></i> 
                                    Link reset password telah dikirim ke email Anda!
                                </div>
                            <?php elseif (isset($_GET['error'])): ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> 
                                    <?php echo htmlspecialchars($_GET['error']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
