<?php
$conn = new mysqli("localhost", "root", "", "crud_db");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

$limit = isset($_POST['limit']) ? $_POST['limit'] : 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) FROM users WHERE name LIKE '%$search%'";
$result_count = $conn->query($sql_count);
$total_records = $result_count->fetch_row()[0];
$total_pages = ceil($total_records / $limit);

$sql = "SELECT * FROM users WHERE name LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Daftar Pengguna</h2>
        <form method="POST" class="form-inline mb-3">
            <input type="text" class="form-control mr-2" name="search" value="<?php echo $search; ?>" placeholder="Cari berdasarkan Nama" style="width: 60%;">
            <button type="submit" class="btn btn-light">Cari</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>