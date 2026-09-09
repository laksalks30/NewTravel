<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Travel Website</title>

    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Font Awesome Cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
        .team-section {
            text-align: center;
        }

        .team-section img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .team-section h2 {
            margin-bottom: 20px;
        }

        .team-section p {
            font-size: 1.1rem;
        }
    </style>
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
                        <a class="nav-link active" href="about.php">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- About Section Start -->
    <section class="about-section py-5" style="background-color: #f7f7f7;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="images/travel indo.jpg" alt="About Travel" class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-4 text-warning">About Us</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Selamat datang di website kami! Kami adalah penyedia layanan perjalanan wisata yang bertujuan untuk menghadirkan pengalaman terbaik bagi setiap pelanggan.
                        Jelajahi keindahan Indonesia bersama kami, temukan budaya, tempat eksotis, dan cerita yang akan melekat selamanya.
                    </p>
                    <p style="font-size: 1.1rem; line-height: 1.8;">
                        Misi kami adalah membuat perjalanan Anda nyaman, aman, dan penuh kenangan. Bersama tim profesional, kami siap membantu Anda merencanakan perjalanan dengan sempurna.
                    </p>
                    <a href="index.php#services" class="btn btn-warning mt-3">Explore Our Services</a>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Team Section Start -->
    <section class="team-section py-5">
        <div class="container">
            <h2 class="text-warning">Meet Our Founder</h2>
            <p class="text-muted mb-4">Kami berdedikasi untuk menghadirkan perjalanan yang tak terlupakan bagi Anda.</p>
            <img src="images/orang.jpg" alt="Founder">
            <h5 class="mt-3">Laksa</h5>
            <p class="text-muted">Founder & CEO</p>
        </div>
    </section>
    <!-- Team Section End -->

    <!-- Footer Start -->
    <footer class="py-3" style="background-color: #333;">
        <div class="container text-center text-white">
            <p class="mb-0">© 2024 Travel Website. All rights reserved.</p>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-c6cb72d66e8db8db9b8bb3d27af73613b0b1c33c52543cbf46ecf73cfe3b92f7" crossorigin="anonymous"></script>
</body>

</html>
