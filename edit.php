<?php
include 'koneksi.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM partisipasi WHERE id=$id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $lomba = $_POST['lomba'];

    $conn->query("UPDATE partisipasi SET nama='$nama', email='$email', nomor_telepon='$nomor_telepon', lomba='$lomba' WHERE id=$id");
    header("Location: halaman_partisipasi.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Partisipasi</title>
</head>
<body>
    <h2>Edit Data Partisipasi</h2>
    <form method="POST" action="">
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?php echo $row['nama']; ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>
        <label>Nomor Telepon:</label><br>
        <input type="text" name="nomor_telepon" value="<?php echo $row['nomor_telepon']; ?>" required><br>
        <label>Lomba:</label><br>
        <input type="text" name="lomba" value="<?php echo $row['lomba']; ?>" required><br><br>
        <input type="submit" value="Simpan">
    </form>
</body>
</html>