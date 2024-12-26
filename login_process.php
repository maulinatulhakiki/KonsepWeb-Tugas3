<?php
// Memulai sesi
session_start();

// Menghubungkan ke database
// Menghubungkan ke database
$host = 'localhost'; // Host database
$dbname = 'crud_db'; // Nama database Anda
$username = 'root'; // Username database Anda
$password = ''; // Password database Anda (kosong jika tidak ada)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Memeriksa apakah data telah dikirim
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $pass = $_POST['password'];

        // Validasi input
        if (empty($email) || empty($pass)) {
            $_SESSION['error'] = "Semua kolom harus diisi!";
            header("Location: login.html");
            exit();
        }

        // Mencari pengguna di database berdasarkan email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Memeriksa apakah pengguna ditemukan
        if ($user_data) {
            // Memverifikasi kata sandi
            if (password_verify($pass, $user_data['password'])) {
                // Menyimpan informasi pengguna di sesi
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];
                header("Location: dashboard.html"); // Ganti dengan halaman yang sesuai setelah login
                exit();
            } else {
                $_SESSION['error'] = "Kata sandi salah!";
                header("Location: login.html");
                exit();
            }
        } else {
            $_SESSION['error'] = "Pengguna tidak ditemukan!";
            header("Location: login.html");
            exit();
        }
    }
} catch (PDOException $e) {
    // Menangani kesalahan koneksi database
    $_SESSION['error'] = "Koneksi gagal: " . $e->getMessage();
    header("Location: login.html");
    exit();
}
?>