<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi.php tersedia

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// Menangani jumlah entri per halaman
$limitOptions = [5, 7, 10, 15];
$limit = isset($_GET['limit']) && in_array($_GET['limit'], $limitOptions) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk mendapatkan data partisipasi
$sql = "SELECT p.*, l.nama_lomba, l.jadwal FROM partisipasi p JOIN lomba l ON p.lomba_id = l.id LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Hitung total data
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM partisipasi");
$totalData = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

// Tambah partisipasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['email'], $_POST['nomor_telepon'], $_POST['lomba'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $lomba_id = $_POST['lomba'];

    // Periksa konflik jadwal
    $checkConflict = $conn->prepare("SELECT COUNT(*) FROM partisipasi WHERE lomba_id = ? AND email = ?");
    $checkConflict->bind_param("is", $lomba_id, $email);
    $checkConflict->execute();
    $checkConflict->bind_result($conflictCount);
    $checkConflict->fetch();
    $checkConflict->close();

    if ($conflictCount == 0) {
        // Masukkan partisipasi baru
        $sqlInsert = "INSERT INTO partisipasi (nama, email, nomor_telepon, lomba_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param("sssi", $nama, $email, $nomor_telepon, $lomba_id);

        if ($stmt->execute()) {
            header("Location: partisipasi.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Jadwal bentrok, tidak dapat menambah data.";
    }
}

// Menangani penghapusan
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $deleteSql = "DELETE FROM partisipasi WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: partisipasi.php");
    exit();
}

// Ambil data untuk edit
$editData = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $editSql = "SELECT * FROM partisipasi WHERE id = ?";
    $stmt = $conn->prepare($editSql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Perbarui partisipasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $nama = $_POST['nama'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $lomba_id = $_POST['lomba'];

    // Perbarui data partisipasi
    $updateSql = "UPDATE partisipasi SET nama = ?, nomor_telepon = ?, lomba_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssii", $nama, $nomor_telepon, $lomba_id, $update_id);

    if ($stmt->execute()) {
        header("Location: partisipasi.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil data lomba untuk dropdown
$lomba_sql = "SELECT * FROM lomba";
$lomba_result = $conn->query($lomba_sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Partisipasi Lomba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            margin-top: 20px;
            flex: 1;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .footer {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tentang_lomba.html">Tentang Lomba</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.html">Beranda</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1>Daftar Lomba yang Diikuti</h1>
            <p>Berikut adalah daftar lomba yang Anda ikuti:</p>
        </div>

        <div class="mb-3">
            <label for="limit" class="form-label">Tampilkan:</label>
            <select id="limit" class="form-select" onchange="location = this.value;">
                <?php foreach ($limitOptions as $opt): ?>
                    <option value="?limit=<?php echo $opt; ?>&page=<?php echo $page; ?>" <?php echo ($opt == $limit) ? 'selected' : ''; ?>>
                        <?php echo $opt; ?> per halaman
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="search" class="form-control mt-2" placeholder="Cari lomba..." oninput="filterTable()">
        </div>

        <table class="table table-bordered" id="participationTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Lomba</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                            <td>' . htmlspecialchars($row['id']) . '</td>
                            <td>' . htmlspecialchars($row['nama']) . '</td>
                            <td>' . htmlspecialchars($row['email']) . '</td>
                            <td>' . htmlspecialchars($row['nomor_telepon']) . '</td>
                            <td>' . htmlspecialchars($row['nama_lomba']) . '</td>
                            <td>' . htmlspecialchars($row['jadwal']) . '</td>
                            <td>
                                <a href="?edit_id=' . htmlspecialchars($row['id']) . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete_id=' . htmlspecialchars($row['id']) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus?\')">Hapus</a>
                            </td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="text-center">Tidak ada data pendaftaran.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Lomba</button>
    </div>

    <!-- Modal Tambah Lomba -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Partisipasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <input type="submit" class="btn btn-success" value="Daftar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Lomba -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Partisipasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($editData): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="update_id" value="<?php echo $editData['id']; ?>">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama:</label>
                                <input type="text" class="form-control" name="nama" id="nama" value="<?php echo htmlspecialchars($editData['nama']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($editData['email']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon:</label>
                                <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" value="<?php echo htmlspecialchars($editData['nomor_telepon']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="lomba" class="form-label">Pilih Lomba:</label>
                                <select class="form-select" name="lomba" id="lomba" required>
                                    <?php
                                    // Ambil data lomba
                                    $lomba_sql = "SELECT * FROM lomba";
                                    $lomba_result = $conn->query($lomba_sql);
                                    while ($row = $lomba_result->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $editData['lomba_id']) ? 'selected' : ''; ?>>
                                            <?php echo $row['nama_lomba'] . ' - ' . $row['jadwal']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <input type="submit" class="btn btn-success" value="Perbarui">
                            </div>
                        </form>
                    <?php else: ?>
                        <p>Tidak ada data untuk diedit.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; BY MAULINATUL HAKIKI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const searchValue = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let rowContainsSearchTerm = false;

                for (let i = 1; i < cells.length - 1; i++) { // Lewati kolom ID dan Aksi
                    if (cells[i].innerText.toLowerCase().includes(searchValue)) {
                        rowContainsSearchTerm = true;
                        break;
                    }
                }

                row.style.display = rowContainsSearchTerm ? '' : 'none';
            });
        }

        <?php if ($editData): ?>
        // Tampilkan modal edit
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
        <?php endif; ?>
    </script>
</body>
</html>