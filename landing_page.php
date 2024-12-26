<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Lomba Olahraga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f8ff; /* Light blue background */
        }
        .header {
            background-color: #007bff; /* Dark blue */
            color: #fff;
            padding: 60px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .btn-signup, .btn-login {
            background-color: #ffc107; /* Yellow */
            color: #ffffff;
            border-radius: 5px;
            padding: 8px 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 5px;
        }
        .btn-signup:hover, .btn-login:hover {
            background-color: #e0a800;
            color: #fff;
        }
        .content {
            margin-top: 20px;
        }
        .aside-section {
            background: linear-gradient(135deg, #e7f3ff, #cfe2f3); /* Gradient background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        .aside-section img {
            width: 100%;
            max-width: 150px;
            margin-bottom: 15px;
            border-radius: 50%;
            border: 4px solid #007bff;
        }
        .aside-section h3 {
            color: #007bff;
            font-weight: bold;
        }
        .sport-list {
            text-align: left;
            padding-left: 0;
            list-style: none;
        }
        .sport-list li {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }
        .sport-list li::before {
            content: "\2022"; /* Bullet symbol */
            color: #007bff; /* Blue color for bullet */
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-right: 10px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .footer {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
            margin-top: 30px;
        }
        /* New style for navbar brand */
        .navbar-brand, .navbar-brand i {
            color: #007bff !important; /* Set color to blue */
        }
        /* New styles for features section */
        .features {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <h1>Selamat Datang di Lomba Olahraga Kecamatan</h1>
        <p>Bergabunglah dalam kompetisi olahraga kecamatan dan buktikan kemampuanmu!</p>
        <a href="register.php" class="btn btn-primary btn-lg btn-signup mt-3">Daftar Sekarang</a>
        <a href="login.html" class="btn btn-primary btn-lg btn-login mt-3">Login</a>
    </header>

    <!-- Navigation -->
    <div class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Daftar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container content">
        <div class="row">
            <!-- Article Section -->
            <div class="col-md-8">
                <!-- First Article -->
                <div class="card p-4 mb-4">
                    <h2 class="card-title">Tentang Lomba</h2>
                    <p class="card-text">Lomba ini terbuka untuk semua kalangan dari berbagai desa di kecamatan Blang Bintang. Pilihlah cabang olahraga yang kamu minati dan tunjukkan bakatmu di ajang olahraga ini!</p>
                    <ul>
                        <li><strong>Jadwal acara:</strong> 10 Desember 2025</li>
                        <li><strong>Lokasi:</strong> Stadion Harapan Bangsa</li>
                    </ul>
                </div>

                <!-- Second Article -->
                <div class="card p-4 mb-4">
                    <h2 class="card-title">Syarat dan Ketentuan</h2>
                    <p class="card-text">Untuk mengikuti lomba, peserta harus memenuhi syarat dan ketentuan yang berlaku. Persiapkan dokumen yang diperlukan dan pastikan semua informasi telah diisi dengan benar.</p>
                </div>

                <!-- New Section for Upcoming Events -->
                <div class="card p-4 mb-4">
                    <h2 class="card-title">Acara Mendatang</h2>
                    <p class="card-text">Berikut adalah beberapa acara mendatang yang dapat kamu ikuti:</p>
                    <ul>
                        <li>10 Desember 2025: Sepak Bola</li>
                        <li>15 Desember 2025: Basket</li>
                        <li>20 Desember 2025: Voli</li>
                    </ul>
                </div>
            </div>

            <!-- Aside Section -->
            <aside class="col-md-4">
                <div class="aside-section">
                    <img src="gambar/12345.png" alt="Foto Lomba">
                    <h3>Cabang Olahraga</h3>
                    <p>Daftar cabang olahraga yang tersedia dalam lomba ini:</p>
                    <ul class="sport-list">
                        <li>Sepak Bola</li>
                        <li>Basket</li>
                        <li>Voli</li>
                        <li>Badminton</li>
                        <li>Catur</li>
                        <li>Tenis Meja</li>
                        <li>Futsal</li>
                    </ul>
                </div>
            </aside>
        </div>

        <!-- New Features Section -->
        <div class="features mt-5">
            <h2 class="text-center">Fitur Menarik</h2>
            <div class="row">
                <div class="col-md-4">
                    <h3><i class="bi bi-check-circle"></i> Registrasi Mudah</h3>
                    <p>Daftar dengan cepat dan mudah untuk mengikuti lomba.</p>
                </div>
                <div class="col-md-4">
                    <h3><i class="bi bi-people-fill"></i> Komunitas Olahraga</h3>
                    <p>Bergabung dengan komunitas atlet dan penggemar olahraga.</p>
                </div>
                <div class="col-md-4">
                    <h3><i class="bi bi-trophy-fill"></i> Hadiah Menarik</h3>
                    <p>Dapatkan hadiah dan penghargaan menarik untuk pemenang.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; BY MAULINATUL HAKIKI</p>
    </footer>

    <!-- Bootstrap JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>