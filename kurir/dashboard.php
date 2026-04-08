<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'kurir') {
    header("Location: login.php");
    exit;
}

$data = mysqli_query($conn, 
    "SELECT * FROM tabel_pesanan 
     WHERE status_pesanan='MENUNGGU_KURIR'");
?>

<h2>Dashboard Kurir</h2>
<p>Selamat datang, <?php echo $_SESSION['nama']; ?></p>

<a href="logout.php">Logout</a>

<hr>

<h3>Daftar Tugas Baru</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Kode Resi</th>
    <th>Pengirim</th>
    <th>Penerima</th>
    <th>Total</th>
    <th>Metode</th>
    <th>Aksi</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($data)) {

    $label_cod = "";
    if($row['metode_pembayaran'] == "COD") {
        $label_cod = "<span style='color:red; font-weight:bold;'> (COD)</span>";
    }

    echo "<tr>
            <td>{$row['kode_resi']}</td>
            <td>{$row['nama_pengirim']}</td>
            <td>{$row['nama_penerima']}</td>
            <td>Rp ".number_format($row['total_harga'],0,',','.')."</td>
            <td>{$row['metode_pembayaran']} $label_cod</td>
            <td>
                <a href='update_status.php?id={$row['id_pesanan']}&aksi=ambil' onclick=\"return confirm('Ambil pesanan ini?')\">Ambil Pesanan</a>
            </td>
          </tr>";
}
?>

</table>