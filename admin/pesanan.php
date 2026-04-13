<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT p.*, u.nama_lengkap AS nama_kurir
    FROM tabel_pesanan p
    LEFT JOIN tabel_users u ON p.id_kurir = u.id_user
    ORDER BY p.id_pesanan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">

    <h2>Data Pesanan</h2>
    <p>Selamat datang, <?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; ?></p>

    <a href="dashboard.php">Kembali ke Dashboard</a>
    <a href="logout.php">Logout</a>

    <hr>

    <table>
        <tr>
            <th>No</th>
            <th>Kode Resi</th>
            <th>Pengirim</th>
            <th>Penerima</th>
            <th>Total Harga</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Kurir</th>
        </tr>

        <?php
        $no = 1;
        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {
                $nama_kurir = $row['nama_kurir'];
                if(empty($nama_kurir)) {
                    $nama_kurir = "Belum diambil";
                }

                $label_cod = "";
                if($row['metode_pembayaran'] == "COD") {
                    $label_cod = "<span class='badge-cod'>(COD)</span>";
                }

                echo "<tr>
                        <td>$no</td>
                        <td>{$row['kode_resi']}</td>
                        <td>{$row['nama_pengirim']}</td>
                        <td>{$row['nama_penerima']}</td>
                        <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
                        <td>{$row['metode_pembayaran']} $label_cod</td>
                        <td>{$row['status_pesanan']}</td>
                        <td>{$nama_kurir}</td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='8'>Belum ada data pesanan</td></tr>";
        }
        ?>
    </table>

</div>
</body>
</html>