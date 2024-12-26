<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'crud_db');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Jika tombol register diklik
if (isset($_POST['register'])) {
    // Ambil data dari form
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Periksa apakah username atau email sudah ada
    $check_user = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if ($check_user->num_rows > 0) {
        echo "<script>alert('Username atau Email sudah digunakan. Silakan gunakan yang lain.'); window.location.href = 'register.html';</script>";
        exit();
    }

    // Masukkan data ke database
    $sql = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'login.html';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $conn->error . "'); window.location.href = 'register.html';</script>";
    }
}

$conn->close();
?>
