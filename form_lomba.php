<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the necessary POST variables are set
    if (isset($_POST['nama'], $_POST['email'], $_POST['nomor_telepon'], $_POST['lomba'])) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $nomor_telepon = $_POST['nomor_telepon'];
        $lomba_id = $_POST['lomba'];

        // Validate lomba_id exists in lomba table
        $checkLomba = $conn->prepare("SELECT COUNT(*) FROM lomba WHERE id = ?");
        $checkLomba->bind_param("i", $lomba_id);
        $checkLomba->execute();
        $checkLomba->bind_result($count);
        $checkLomba->fetch();
        $checkLomba->close();

        if ($count > 0) {
            // Proceed with inserting the data
            $sql = "INSERT INTO partisipasi (nama, email, nomor_telepon, lomba_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nama, $email, $nomor_telepon, $lomba_id);

            if ($stmt->execute()) {
                header("Location: partisipasi.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Lomba yang dipilih tidak valid.";
        }
    } else {
        $error_message = "Semua field harus diisi.";
    }
}

$lomba_sql = "SELECT * FROM lomba";
$lomba_result = $conn->query($lomba_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        footer {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">Form Pendaftaran Lomba Olahraga</h2>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <input type="text" class="form-control" name="nama" id="nama" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon:</label>
                <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" required>
            </div>
            <div class="mb-3">
                <label for="lomba" class="form-label">Pilih Lomba:</label>
                <select class="form-select" name="lomba" id="lomba" required>
                    <?php while ($row = $lomba_result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_lomba'] . ' - ' . $row['jadwal']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="submit" class="btn btn-success w-100" value="Daftar">
        </form>
    </div>

    <footer>
        <p>&copy; BY MAULINATUL HAKIKI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>