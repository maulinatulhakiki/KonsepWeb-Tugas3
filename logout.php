<?php
session_start();
session_unset(); // Menghapus semua variabel sesi
session_destroy(); // Menghancurkan sesi
header("Location: index.html"); // Arahkan kembali ke halaman login
exit();
?>