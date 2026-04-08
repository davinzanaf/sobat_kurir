<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<h2>Dashboard Admin</h2>
<a href="logout.php">Logout</a>
<br><br>
<a href="tarif.php">Kelola Tarif</a>
<br>
<a href="kurir.php">Kelola Kurir</a>
<br>
<a href="pesanan.php">Data pesanan</a>