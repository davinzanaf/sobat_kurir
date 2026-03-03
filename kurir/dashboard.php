<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'kurir') {
    header("Location: login.php");
    exit;
}
?>

<h2>Dashboard Kurir</h2>
<p>Selamat datang, <?php echo $_SESSION['nama']; ?></p>

<a href="logout.php">Logout</a>