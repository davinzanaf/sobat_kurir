<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'kurir') {
    header("Location: login.php");
    exit;
}

$id_kurir = $_SESSION['id_user'];

$data_baru = mysqli_query($conn,
    "SELECT * FROM tabel_pesanan
     WHERE status_pesanan='MENUNGGU_KURIR'
     ORDER BY id_pesanan DESC");

$data_saya = mysqli_query($conn,
    "SELECT * FROM tabel_pesanan
     WHERE id_kurir='$id_kurir'
     ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kurir</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

<h2>Dashboard Kurir</h2>
<p>Selamat datang, <?php echo $_SESSION['nama']; ?></p>

<a href="logout.php">Logout</a>

<hr>

<h3>Daftar Tugas Baru</h3>

<table>
    <tr>
        <th>Kode Resi</th>
        <th>Pengirim</th>
        <th>Penerima</th>
        <th>Total</th>
        <th>Metode</th>
        <th>Aksi</th>
    </tr>

    <?php
    if(mysqli_num_rows($data_baru) > 0) {
        while($row = mysqli_fetch_assoc($data_baru)) {

            $label_cod = "";
            if($row['metode_pembayaran'] == "COD") {
                $label_cod = "<span class='badge-cod'>(COD)</span>";
            }

            echo "<tr>
                    <td>{$row['kode_resi']}</td>
                    <td>{$row['nama_pengirim']}</td>
                    <td>{$row['nama_penerima']}</td>
                    <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                    <td>{$row['metode_pembayaran']} $label_cod</td>
                    <td>
                        <a class='btn' href='update_status.php?id={$row['id_pesanan']}&aksi=ambil'
                           onclick=\"return confirm('Yakin ingin mengambil pesanan ini?')\">
                           Ambil Pesanan
                        </a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Belum ada tugas baru.</td></tr>";
    }
    ?>
</table>

<hr>

<h3>Pesanan Saya</h3>

<table>
    <tr>
        <th>Kode Resi</th>
        <th>Pengirim</th>
        <th>Penerima</th>
        <th>Total</th>
        <th>Metode</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php
    if(mysqli_num_rows($data_saya) > 0) {
        while($row = mysqli_fetch_assoc($data_saya)) {

            $label_cod = "";
            if($row['metode_pembayaran'] == "COD") {
                $label_cod = "<span class='badge-cod'>(COD)</span>";
            }

            echo "<tr>
                    <td>{$row['kode_resi']}</td>
                    <td>{$row['nama_pengirim']}</td>
                    <td>{$row['nama_penerima']}</td>
                    <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                    <td>{$row['metode_pembayaran']} $label_cod</td>
                    <td>{$row['status_pesanan']}</td>
                    <td>
                        <a class='btn' href='detail_pesanan.php?id={$row['id_pesanan']}'>Detail</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Belum ada pesanan yang Anda ambil.</td></tr>";
    }
    ?>
</table>

</div>
</body>
</html>