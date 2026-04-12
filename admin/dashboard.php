<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; ?></p>

    <div class="menu">
        <a href="kurir.php">Kelola Kurir</a>
        <a href="tarif.php">Kelola Tarif</a>
        <a href="pesanan.php">Data Pesanan</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>